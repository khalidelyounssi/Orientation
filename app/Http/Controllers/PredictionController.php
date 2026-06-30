<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Prediction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class PredictionController extends Controller
{
    public function index(Request $request): View
    {
        $predictions = Prediction::query()
            ->with(['candidate', 'predictedBy'])
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery
                        ->where('prediction_label', 'like', "%{$search}%")
                        ->orWhere('risk_level', 'like', "%{$search}%")
                        ->orWhereHas('candidate', function ($candidateQuery) use ($search): void {
                            $candidateQuery
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('predictions.index', compact('predictions'));
    }

    public function show(Prediction $prediction): View
    {
        $prediction->load(['candidate', 'predictedBy']);

        return view('predictions.show', compact('prediction'));
    }

    public function predict(Candidate $candidate): RedirectResponse
    {
        $payload = $candidate->predictionPayload();

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->post(config('services.ml_api.url'), $payload)
                ->throw();
        } catch (ConnectionException $exception) {
            return back()->withErrors([
                'prediction' => 'The calculation service is temporarily unavailable. Make sure it is running, then try again.',
            ]);
        } catch (RequestException $exception) {
            $status = $exception->response?->status();

            return back()->withErrors([
                'prediction' => 'The score calculation failed'.($status ? " (code {$status})." : '.'),
            ]);
        } catch (\Throwable $exception) {
            return back()->withErrors([
                'prediction' => 'An unexpected error occurred while calculating the score.',
            ]);
        }

        $data = $response->json();

        if (! is_array($data)) {
            return back()->withErrors([
                'prediction' => 'The calculation service returned an invalid response.',
            ]);
        }

        $requiredResponseKeys = [
            'prediction_label',
            'is_dropout_risk',
            'dropout_probability',
            'dropout_probability_percent',
            'threshold',
            'risk_level',
            'model_name',
            'model_version',
        ];

        foreach ($requiredResponseKeys as $key) {
            if (! array_key_exists($key, $data)) {
                return back()->withErrors([
                    'prediction' => 'The calculation service returned an incomplete response.',
                ]);
            }
        }

        $result = [
            'prediction_label' => (string) $data['prediction_label'],
            'is_dropout_risk' => $this->normalizeBoolean($data['is_dropout_risk']),
            'dropout_probability' => $this->normalizeProbability($data['dropout_probability']),
            'dropout_probability_percent' => $this->normalizePercent($data['dropout_probability_percent']),
            'threshold' => $this->normalizeThreshold($data['threshold']),
            'risk_level' => (string) ($data['risk_level'] ?: $this->resolveRiskLevel($data['dropout_probability_percent'])),
            'model_name' => $data['model_name'] !== null ? (string) $data['model_name'] : null,
            'model_version' => $data['model_version'] !== null ? (string) $data['model_version'] : null,
        ];

        $prediction = DB::transaction(function () use ($candidate, $data, $result) {
            $prediction = Prediction::create([
                'candidate_id' => $candidate->id,
                'prediction_label' => $result['prediction_label'],
                'is_dropout_risk' => $result['is_dropout_risk'],
                'dropout_probability' => $result['dropout_probability'],
                'dropout_probability_percent' => $result['dropout_probability_percent'],
                'threshold' => $result['threshold'],
                'risk_level' => $result['risk_level'],
                'recommendation' => $this->generateRecommendation($candidate, $result),
                'model_name' => $result['model_name'],
                'model_version' => $result['model_version'],
                'api_response' => $data,
                'predicted_by' => auth()->id(),
            ]);

            if (Schema::hasColumn('candidates', 'status')) {
                $candidate->update([
                    'status' => 'predicted',
                ]);
            }

            return $prediction;
        });

        return redirect()
            ->route('predictions.show', $prediction)
            ->with('success', 'The vigilance score was calculated and saved successfully.');
    }

    private function generateRecommendation(Candidate $candidate, array $result): string
    {
        $label = (string) $result['prediction_label'];
        $riskLevel = strtolower((string) $result['risk_level']);
        $probability = number_format((float) $result['dropout_probability_percent'], 2);
        $isDropout = strtolower($label) === 'dropout';
        $parts = [];

        if ($isDropout && $riskLevel === 'high') {
            $parts[] = "This student presents a high vigilance level with a score of {$probability}%. Urgent academic and administrative follow-up is recommended.";
            $parts[] = 'A meeting with an academic advisor should be scheduled as soon as possible to review the candidate profile, identify immediate barriers, and define a support plan.';
        } elseif ($isDropout && $riskLevel === 'medium') {
            $parts[] = "This student presents a moderate vigilance level with a score of {$probability}%. Monitoring, academic guidance, and preventive support are recommended.";
            $parts[] = 'The academic and financial indicators should be reviewed before making a final decision, and a follow-up should be planned to confirm whether additional support is needed.';
        } elseif ($isDropout) {
            $parts[] = "A limited vigilance signal was detected with a score of {$probability}%. Light monitoring and verification of the student profile are recommended.";
        } else {
            $parts[] = "This student is currently classified as stable. Normal academic and administrative follow-up is recommended.";
        }

        $predictiveData = $candidate->predictive_data ?? [];
        $currentBalance = (float) ($predictiveData['CURRENT_BALANCE'] ?? 0);
        $balancePaidToDate = (float) ($predictiveData['BALANCE_PAID_TO_DATE'] ?? 0);
        $careerGpa = isset($predictiveData['CAREER_GPA']) ? (float) $predictiveData['CAREER_GPA'] : null;
        $attendancePercent = isset($predictiveData['ATTENDANCE_PERCENT']) ? (float) $predictiveData['ATTENDANCE_PERCENT'] : null;

        if ($currentBalance > $balancePaidToDate) {
            $parts[] = 'The financial indicators should be reviewed because the current balance is higher than the amount paid to date.';
        }

        if ($careerGpa !== null && $careerGpa < 2) {
            $parts[] = 'The career GPA is low, so academic support, tutoring, or a targeted learning review should be considered.';
        }

        if ($attendancePercent !== null && $attendancePercent < 60) {
            $parts[] = 'Attendance is a concern and should be discussed with the candidate during the follow-up review.';
        }

        $parts[] = 'The final follow-up decision remains the responsibility of the academic team.';

        return implode(' ', $parts);
    }

    private function normalizeBoolean(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
    }

    protected function normalizeProbability(mixed $probability): float
    {
        return max(0, min(1, round((float) $probability, 4)));
    }

    protected function normalizePercent(mixed $percent): float
    {
        return max(0, min(100, round((float) $percent, 2)));
    }

    protected function normalizeThreshold(mixed $threshold): float
    {
        return max(0, min(1, round((float) $threshold, 2)));
    }

    protected function resolveRiskLevel(mixed $percent): string
    {
        $percent = $this->normalizePercent($percent ?? 0);

        return match (true) {
            $percent >= 70 => 'High',
            $percent >= 40 => 'Medium',
            default => 'Low',
        };
    }
}
