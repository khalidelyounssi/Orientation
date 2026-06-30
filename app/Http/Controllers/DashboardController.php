<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Prediction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $latestPredictionIds = Prediction::query()
            ->selectRaw('MAX(id) as id')
            ->groupBy('candidate_id');

        $latestPredictions = Prediction::query()
            ->with(['candidate', 'predictedBy'])
            ->latest()
            ->take(6)
            ->get();

        $riskLevelDistribution = Prediction::query()
            ->select('risk_level', DB::raw('COUNT(*) as total'))
            ->whereNotNull('risk_level')
            ->groupBy('risk_level')
            ->orderByDesc('total')
            ->get();

        $courseDistribution = Candidate::query()
            ->select('course', DB::raw('COUNT(*) as total'))
            ->whereNotNull('course')
            ->groupBy('course')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        return view('dashboard', [
            'stats' => [
                'candidates' => Candidate::count(),
                'predictions' => Prediction::count(),
                'dropout_risk' => Prediction::query()
                    ->whereIn('id', $latestPredictionIds)
                    ->where('is_dropout_risk', true)
                    ->count(),
                'not_dropout' => Prediction::query()
                    ->whereIn('id', $latestPredictionIds)
                    ->where('is_dropout_risk', false)
                    ->count(),
                'average_probability' => (float) (Prediction::query()->avg('dropout_probability_percent') ?? 0),
            ],
            'latestPredictions' => $latestPredictions,
            'riskLevelDistribution' => $riskLevelDistribution,
            'courseDistribution' => $courseDistribution,
        ]);
    }
}
