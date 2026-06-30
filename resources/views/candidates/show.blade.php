<x-layouts.app :title="'Student details | Orientation Campus'">
    @php
        $predictiveData = $candidate->predictive_data ?? [];
        $candidateName = trim(($candidate->first_name ?? '').' '.($candidate->last_name ?? '')) ?: ($candidate->external_student_id ? 'Student '.$candidate->external_student_id : 'Unnamed student');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Student</p>
                <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">{{ $candidateName }}</h1>
                <p class="mt-3 text-sm text-slate-600">Major {{ $predictiveData['MAJOR_1'] ?? 'N/A' }} · career GPA {{ isset($predictiveData['CAREER_GPA']) ? number_format((float) $predictiveData['CAREER_GPA'], 2) : 'N/A' }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('candidates.predict', $candidate) }}">
                    @csrf
                    <x-button type="submit">Calculate score</x-button>
                </form>
                <x-button :href="route('candidates.edit', $candidate)" variant="secondary">Edit</x-button>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.25fr,0.95fr]">
            <div class="space-y-6">
                <section class="soft-card rounded-[2rem] p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-slate-950">Administrative profile</h2>
                        <x-status-badge :status="$candidate->status" />
                    </div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div><p class="text-sm text-slate-500">Student ID</p><p class="mt-1 font-medium text-slate-900">{{ $candidate->external_student_id ?: 'N/A' }}</p></div>
                        <div><p class="text-sm text-slate-500">Academic status</p><p class="mt-1 font-medium text-slate-900">{{ $candidate->academic_status ?: 'N/A' }}</p></div>
                        <div><p class="text-sm text-slate-500">Email</p><p class="mt-1 font-medium text-slate-900">{{ $candidate->email ?: 'Not provided' }}</p></div>
                        <div><p class="text-sm text-slate-500">Phone</p><p class="mt-1 font-medium text-slate-900">{{ $candidate->phone ?: 'Not provided' }}</p></div>
                        <div><p class="text-sm text-slate-500">Division</p><p class="mt-1 font-medium text-slate-900">{{ $predictiveData['DIV_CDE'] ?? 'N/A' }}</p></div>
                        <div><p class="text-sm text-slate-500">Degree</p><p class="mt-1 font-medium text-slate-900">{{ $predictiveData['DEGREE_CDE'] ?? 'N/A' }}</p></div>
                        <div><p class="text-sm text-slate-500">Major</p><p class="mt-1 font-medium text-slate-900">{{ $predictiveData['MAJOR_1'] ?? 'N/A' }}</p></div>
                        <div><p class="text-sm text-slate-500">Career GPA</p><p class="mt-1 font-medium text-slate-900">{{ isset($predictiveData['CAREER_GPA']) ? number_format((float) $predictiveData['CAREER_GPA'], 2) : 'N/A' }}</p></div>
                    </div>
                </section>

                <section class="soft-card rounded-[2rem] p-6">
                    <h2 class="text-xl font-semibold text-slate-950">Follow-up data</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach (\App\Models\Candidate::predictiveFields() as $field => $label)
                            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3">
                                <p class="text-sm text-slate-500">{{ $label }}</p>
                                <p class="mt-1 text-lg font-semibold text-slate-900">{{ $predictiveData[$field] ?? 'N/A' }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="space-y-6">
                <section class="soft-card rounded-[2rem] p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-slate-950">Score history</h2>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $candidate->predictions->count() }}</span>
                    </div>
                    <div class="mt-5 space-y-4">
                        @forelse ($candidate->predictions as $prediction)
                            <a href="{{ route('predictions.show', $prediction) }}" class="block rounded-3xl border border-slate-200 bg-white px-4 py-4 transition hover:border-teal-300">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ strtolower($prediction->prediction_label) === 'dropout' ? 'Needs review' : 'Stable' }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ number_format((float) $prediction->dropout_probability_percent, 2) }}% · {{ $prediction->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <x-status-badge :status="strtolower($prediction->risk_level ?? 'low')" />
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">No scores recorded.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>
