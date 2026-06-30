<x-layouts.app :title="'Scores | Orientation Campus'">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Scores</p>
            <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">Vigilance score history</h1>
            <p class="mt-3 text-sm text-slate-600">Review the latest calculated results for student records.</p>
        </div>

        <form method="GET" class="soft-card rounded-[2rem] p-5">
            <div class="grid gap-4 md:grid-cols-[1fr,auto]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student, status, or level" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <x-button type="submit">Search</x-button>
            </div>
        </form>

        <x-table>
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                <tr>
                    <th class="px-5 py-4">Student</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Score</th>
                    <th class="px-5 py-4">Level</th>
                    <th class="px-5 py-4">Date</th>
                    <th class="px-5 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($predictions as $prediction)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-900">{{ trim(($prediction->candidate->first_name ?? '').' '.($prediction->candidate->last_name ?? '')) ?: 'Unnamed student' }}</p>
                            <p class="text-xs text-slate-500">{{ $prediction->candidate->email ?: 'Email not provided' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <x-status-badge :status="strtolower(str_replace(' ', '_', $prediction->prediction_label))" />
                        </td>
                        <td class="px-5 py-4">{{ number_format((float) $prediction->dropout_probability_percent, 2) }}%</td>
                        <td class="px-5 py-4"><x-status-badge :status="strtolower($prediction->risk_level ?? 'low')" /></td>
                        <td class="px-5 py-4 text-slate-500">{{ $prediction->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-4 text-right">
                            <x-button :href="route('predictions.show', $prediction)" variant="secondary">View</x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">No scores recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>

        {{ $predictions->links() }}
    </div>
</x-layouts.app>
