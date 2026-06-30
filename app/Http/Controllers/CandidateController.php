<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateRequest;
use App\Models\Candidate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateController extends Controller
{
    public function index(Request $request): View
    {
        $candidates = Candidate::query()
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('external_student_id', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('course'), fn ($query) => $query->where('course', (int) $request->input('course')))
            ->when($request->string('status')->toString(), fn ($query, string $status) => $query->where('status', $status))
            ->with(['predictions' => fn ($query) => $query->latest()->limit(1)])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('candidates.index', [
            'candidates' => $candidates,
            'courses' => Candidate::query()->whereNotNull('course')->select('course')->distinct()->orderBy('course')->pluck('course'),
            'statuses' => Candidate::STATUSES,
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'candidates_csv' => ['required', 'file', 'mimes:csv,txt', 'max:65536'],
        ]);

        /** @var UploadedFile $file */
        $file = $validated['candidates_csv'];
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return back()->withErrors([
                'candidates_csv' => 'Unable to open the uploaded CSV file.',
            ]);
        }

        $headers = fgetcsv($handle);

        if (! is_array($headers)) {
            fclose($handle);

            return back()->withErrors([
                'candidates_csv' => 'The uploaded CSV file is empty.',
            ]);
        }

        $headers = array_map(fn (string $header): string => $this->normalizeCsvHeader($header), $headers);
        $missingFields = array_values(array_diff(Candidate::PREDICTIVE_FIELDS, $headers));

        if ($missingFields !== []) {
            fclose($handle);

            return back()->withErrors([
                'candidates_csv' => 'The CSV file does not contain all required columns: '.implode(', ', array_slice($missingFields, 0, 8)).(count($missingFields) > 8 ? '...' : ''),
            ]);
        }

        $imported = 0;
        $updated = 0;

        DB::transaction(function () use ($handle, $headers, &$imported, &$updated): void {
            while (($row = fgetcsv($handle)) !== false) {
                if ($this->isEmptyCsvRow($row)) {
                    continue;
                }

                $record = array_combine($headers, array_slice(array_pad($row, count($headers), null), 0, count($headers)));

                if (! is_array($record)) {
                    continue;
                }

                $predictiveData = [];

                foreach (Candidate::PREDICTIVE_FIELDS as $field) {
                    $predictiveData[$field] = $this->normalizeCsvValue($record[$field] ?? null);
                }

                $studentId = $this->normalizeCsvValue($record['ID_NUM'] ?? null);
                $attributes = [
                    'external_student_id' => $studentId !== null ? (string) $studentId : null,
                    'academic_status' => $this->normalizeCsvValue($record['STUDENT_STATUS'] ?? null),
                    'status' => 'pending',
                    'predictive_data' => $predictiveData,
                ];

                if ($attributes['external_student_id']) {
                    $candidate = Candidate::updateOrCreate(
                        ['external_student_id' => $attributes['external_student_id']],
                        $attributes,
                    );

                    $candidate->wasRecentlyCreated ? $imported++ : $updated++;

                    continue;
                }

                Candidate::create($attributes);
                $imported++;
            }
        });

        fclose($handle);

        return redirect()
            ->route('candidates.index')
            ->with('success', "{$imported} candidates imported and {$updated} candidates updated from the CSV file.");
    }

    public function create(): View
    {
        return view('candidates.create', [
            'candidate' => new Candidate([
                'status' => 'pending',
                'tuition_fees_up_to_date' => true,
            ]),
            'statuses' => Candidate::STATUSES,
        ]);
    }

    public function store(CandidateRequest $request): RedirectResponse
    {
        $candidate = Candidate::create($this->validatedPayload($request));

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('success', 'The student was created successfully.');
    }

    public function show(Candidate $candidate): View
    {
        $candidate->load(['predictions' => fn ($query) => $query->with('predictedBy')->latest()]);

        return view('candidates.show', compact('candidate'));
    }

    public function edit(Candidate $candidate): View
    {
        return view('candidates.edit', [
            'candidate' => $candidate,
            'statuses' => Candidate::STATUSES,
        ]);
    }

    public function update(CandidateRequest $request, Candidate $candidate): RedirectResponse
    {
        $candidate->update($this->validatedPayload($request));

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('success', 'The student was updated successfully.');
    }

    public function destroy(Candidate $candidate): RedirectResponse
    {
        $candidate->delete();

        return redirect()
            ->route('candidates.index')
            ->with('success', 'The student was deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedPayload(CandidateRequest $request): array
    {
        $validated = $request->validated();
        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['predictive_data'] = $this->normalizePredictiveData($validated['predictive_data'] ?? []);

        foreach ([
            'displaced',
            'educational_special_needs',
            'debtor',
            'tuition_fees_up_to_date',
            'scholarship_holder',
            'international',
        ] as $field) {
            $validated[$field] = $request->boolean($field);
        }

        return $validated;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function normalizePredictiveData(array $data): array
    {
        return collect(Candidate::PREDICTIVE_FIELDS)
            ->mapWithKeys(fn (string $field): array => [$field => $this->normalizeCsvValue($data[$field] ?? null)])
            ->all();
    }

    protected function normalizeCsvValue(mixed $value): mixed
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        if ($value === '' || $value === null || strtoupper((string) $value) === 'NULL') {
            return null;
        }

        return is_numeric($value) ? $value + 0 : $value;
    }

    protected function normalizeCsvHeader(string $header): string
    {
        return trim(preg_replace('/^\xEF\xBB\xBF/', '', $header) ?? $header);
    }

    /**
     * @param list<mixed> $row
     */
    protected function isEmptyCsvRow(array $row): bool
    {
        return collect($row)
            ->every(fn (mixed $value): bool => trim((string) $value) === '');
    }
}
