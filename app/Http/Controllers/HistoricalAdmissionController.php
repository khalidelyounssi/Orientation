<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoricalAdmissionRequest;
use App\Models\HistoricalAdmission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HistoricalAdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $historicalAdmissions = HistoricalAdmission::query()
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery
                        ->where('admitted_program', 'like', "%{$search}%")
                        ->orWhere('followed_program', 'like', "%{$search}%")
                        ->orWhere('recommended_program', 'like', "%{$search}%");
                });
            })
            ->when($request->string('bac_type')->toString(), fn ($query, string $bacType) => $query->where('bac_type', $bacType))
            ->when($request->string('recommended_program')->toString(), fn ($query, string $recommendedProgram) => $query->where('recommended_program', $recommendedProgram))
            ->when($request->string('final_status')->toString(), fn ($query, string $finalStatus) => $query->where('final_status', $finalStatus))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('historical-admissions.index', [
            'historicalAdmissions' => $historicalAdmissions,
            'bacTypes' => HistoricalAdmission::query()->select('bac_type')->distinct()->orderBy('bac_type')->pluck('bac_type'),
            'recommendedPrograms' => HistoricalAdmission::query()->whereNotNull('recommended_program')->select('recommended_program')->distinct()->orderBy('recommended_program')->pluck('recommended_program'),
            'finalStatuses' => HistoricalAdmission::query()->whereNotNull('final_status')->select('final_status')->distinct()->orderBy('final_status')->pluck('final_status'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('historical-admissions.create', [
            'historicalAdmission' => new HistoricalAdmission([
                'number_of_repeated_years' => 0,
            ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HistoricalAdmissionRequest $request): RedirectResponse
    {
        $historicalAdmission = HistoricalAdmission::create($this->validatedPayload($request));

        return redirect()
            ->route('historical-admissions.show', $historicalAdmission)
            ->with('success', 'The historical record was added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoricalAdmission $historicalAdmission): View
    {
        return view('historical-admissions.show', compact('historicalAdmission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoricalAdmission $historicalAdmission): View
    {
        return view('historical-admissions.edit', compact('historicalAdmission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HistoricalAdmissionRequest $request, HistoricalAdmission $historicalAdmission): RedirectResponse
    {
        $historicalAdmission->update($this->validatedPayload($request));

        return redirect()
            ->route('historical-admissions.show', $historicalAdmission)
            ->with('success', 'The historical record was updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoricalAdmission $historicalAdmission): RedirectResponse
    {
        $historicalAdmission->delete();

        return redirect()
            ->route('historical-admissions.index')
            ->with('success', 'The historical record was deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedPayload(HistoricalAdmissionRequest $request): array
    {
        $validated = $request->validated();
        $validated['has_repeated_year'] = $request->boolean('has_repeated_year');
        $validated['number_of_repeated_years'] = $validated['number_of_repeated_years'] ?? 0;

        return $validated;
    }
}
