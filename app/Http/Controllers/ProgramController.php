<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramRequest;
use App\Models\Program;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $programs = Program::query()
            ->when($request->string('search')->toString(), fn ($query, string $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($request->string('department')->toString(), fn ($query, string $department) => $query->where('department', $department))
            ->when($request->string('faculty')->toString(), fn ($query, string $faculty) => $query->where('faculty', $faculty))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('programs.index', [
            'programs' => $programs,
            'departments' => Program::query()->whereNotNull('department')->select('department')->distinct()->orderBy('department')->pluck('department'),
            'faculties' => Program::query()->whereNotNull('faculty')->select('faculty')->distinct()->orderBy('faculty')->pluck('faculty'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('programs.create', [
            'program' => new Program(['is_active' => true]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProgramRequest $request): RedirectResponse
    {
        $program = Program::create($this->validatedPayload($request));

        return redirect()
            ->route('programs.show', $program)
            ->with('success', 'The program was created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program): View
    {
        return view('programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program): View
    {
        return view('programs.edit', compact('program'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProgramRequest $request, Program $program): RedirectResponse
    {
        $program->update($this->validatedPayload($request, $program));

        return redirect()
            ->route('programs.show', $program)
            ->with('success', 'The program was updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program): RedirectResponse
    {
        $program->delete();

        return redirect()
            ->route('programs.index')
            ->with('success', 'The program was deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedPayload(ProgramRequest $request, ?Program $program = null): array
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');
        $validated['slug'] = $this->resolveSlug($program, $validated['name'], $validated['slug'] ?? null);

        return $validated;
    }

    protected function resolveSlug(?Program $program, string $name, ?string $slug): string
    {
        $baseSlug = Str::slug($slug ?: $name);
        $resolvedSlug = $baseSlug;
        $counter = 1;

        while (
            Program::query()
                ->when($program, fn ($query) => $query->whereKeyNot($program->id))
                ->where('slug', $resolvedSlug)
                ->exists()
        ) {
            $resolvedSlug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $resolvedSlug;
    }
}
