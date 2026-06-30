<x-layouts.app :title="'Score details | Orientation Campus'">
    @php
        $candidate = $prediction->candidate;
        $predictiveData = $candidate->predictive_data ?? [];
        $candidateName = trim(($candidate->first_name ?? '').' '.($candidate->last_name ?? '')) ?: ($candidate->external_student_id ? 'Student '.$candidate->external_student_id : 'Unnamed student');
        $statusTitle = strtolower($prediction->prediction_label) === 'dropout' ? 'Needs review' : 'Stable';
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Follow-up score</p>
                <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">{{ $statusTitle }}</h1>
                <p class="mt-3 text-sm text-slate-600">
                    {{ $candidateName }} · score {{ number_format((float) $prediction->dropout_probability_percent, 2) }}%
                </p>
            </div>
            <x-button :href="route('candidates.show', $candidate)" variant="secondary">View student</x-button>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr,0.8fr]">
            <div class="space-y-6">
                <section class="soft-card rounded-2xl p-6">
                    <h2 class="text-xl font-semibold text-slate-950">Student information</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Full name</p>
                            <p class="mt-1 font-medium text-slate-900">{{ $candidateName }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Student ID</p>
                            <p class="mt-1 font-medium text-slate-900">{{ $candidate->external_student_id ?: 'N/A' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Email</p>
                            <p class="mt-1 font-medium text-slate-900">{{ $candidate->email ?: 'Not provided' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Major</p>
                            <p class="mt-1 font-medium text-slate-900">{{ $predictiveData['MAJOR_1'] ?? 'N/A' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Career GPA</p>
                            <p class="mt-1 font-medium text-slate-900">{{ isset($predictiveData['CAREER_GPA']) ? number_format((float) $predictiveData['CAREER_GPA'], 2) : 'N/A' }}</p>
                        </div>
                    </div>
                </section>

                <section class="soft-card rounded-2xl p-6">
                    <h2 class="text-xl font-semibold text-slate-950">Score result</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Status</p>
                            <p class="mt-2"><x-status-badge :status="strtolower(str_replace(' ', '_', $prediction->prediction_label))" /></p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Level</p>
                            <p class="mt-2"><x-status-badge :status="strtolower($prediction->risk_level ?? 'low')" /></p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Score</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ number_format((float) $prediction->dropout_probability_percent, 2) }}%</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Threshold</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ number_format((float) $prediction->threshold, 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Calculated by</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $prediction->predictedBy?->name ?: 'System' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-sm text-slate-500">Date</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $prediction->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </section>

                <section class="recommendation-card rounded-2xl p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-teal-800">Recommendation</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-950">Suggested actions</h2>
                        </div>
                        <x-status-badge :status="strtolower($prediction->risk_level ?? 'low')" />
                    </div>
                    <p class="mt-5 leading-7 text-slate-800">
                        {{ $prediction->recommendation ?: 'No recommendation recorded for this score.' }}
                    </p>
                </section>
            </div>

            <div class="space-y-6">
                <section class="soft-card rounded-2xl p-6">
                    <h2 class="text-xl font-semibold text-slate-950">Score settings</h2>
                    <div class="mt-5 space-y-4 text-sm">
                        <div>
                            <p class="text-slate-500">Vigilance threshold</p>
                            <p class="mt-1 font-medium text-slate-900">{{ number_format((float) $prediction->threshold, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500">Selected level</p>
                            <p class="mt-1"><x-status-badge :status="strtolower($prediction->risk_level ?? 'low')" /></p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>
