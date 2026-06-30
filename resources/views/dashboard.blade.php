<x-layouts.app :title="'Dashboard | Orientation Campus'">
    <div class="space-y-8">
        <section class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Dashboard</p>
                <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">Student pathway overview</h1>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                    Track students, vigilance levels, and the latest recorded scores.
                </p>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            @php
                $cards = [
                    ['label' => 'Students', 'value' => $stats['candidates'], 'tone' => 'from-teal-500 to-teal-700'],
                    ['label' => 'Scores', 'value' => $stats['predictions'], 'tone' => 'from-sky-500 to-sky-700'],
                    ['label' => 'Needs review', 'value' => $stats['dropout_risk'], 'tone' => 'from-rose-500 to-rose-700'],
                    ['label' => 'Stables', 'value' => $stats['not_dropout'], 'tone' => 'from-emerald-500 to-emerald-700'],
                    ['label' => 'Average score', 'value' => number_format($stats['average_probability'], 2).'%', 'tone' => 'from-amber-500 to-orange-600'],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="soft-card overflow-hidden rounded-[2rem]">
                    <div class="bg-gradient-to-br {{ $card['tone'] }} p-5 text-white">
                        <p class="text-sm uppercase tracking-[0.2em] text-white/80">{{ $card['label'] }}</p>
                        <p class="mt-4 text-4xl font-semibold">{{ $card['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.6fr,1fr]">
            <div class="soft-card rounded-[2rem] p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Latest scores</h2>
                        <p class="mt-1 text-sm text-slate-500">Most recent results recorded in the platform.</p>
                    </div>
                    <a href="{{ route('predictions.index') }}" class="text-sm font-semibold text-teal-700">View all</a>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($latestPredictions as $prediction)
                        <div class="rounded-3xl border border-slate-200 bg-white px-5 py-4">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ trim(($prediction->candidate->first_name ?? '').' '.($prediction->candidate->last_name ?? '')) ?: 'Unnamed student' }}</p>
                                    <p class="text-sm text-slate-500">{{ strtolower($prediction->prediction_label) === 'dropout' ? 'Needs review' : 'Stable' }} · {{ number_format((float) $prediction->dropout_probability_percent, 2) }}%</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <x-status-badge :status="strtolower($prediction->risk_level ?? 'low')" />
                                    <span class="text-sm text-slate-500">{{ $prediction->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-3xl border border-dashed border-slate-300 px-5 py-10 text-center text-sm text-slate-500">
                            No scores have been recorded yet.
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <div class="soft-card rounded-[2rem] p-6">
                    <h2 class="text-xl font-semibold text-slate-950">Distribution by level</h2>
                    <div class="mt-5 space-y-3">
                        @forelse ($riskLevelDistribution as $item)
                            <div class="flex items-center justify-between rounded-2xl bg-white px-4 py-3">
                                <x-status-badge :status="strtolower($item->risk_level)" />
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $item->total }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No data available.</p>
                        @endforelse
                    </div>
                </div>

                <div class="soft-card rounded-[2rem] p-6">
                    <h2 class="text-xl font-semibold text-slate-950">Distribution by program</h2>
                    <div class="mt-5 space-y-3">
                        @forelse ($courseDistribution as $item)
                            <div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-slate-800">Course {{ $item->course }}</span>
                                    <span class="text-slate-500">{{ $item->total }}</span>
                                </div>
                                <div class="mt-2 h-2 rounded-full bg-slate-200">
                                    <div class="h-2 rounded-full bg-gradient-to-r from-amber-500 to-teal-600" style="width: {{ $stats['candidates'] > 0 ? ($item->total / $stats['candidates']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No data available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
