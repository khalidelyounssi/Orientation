<x-layouts.app :title="'Admission history | Orientation Campus'">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Historical data</p>
                <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">Admission history</h1>
            </div>
            <x-button :href="route('historical-admissions.create')">Add record</x-button>
        </div>

        <form method="GET" class="soft-card rounded-[2rem] p-5">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by program" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <select name="bac_type" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                    <option value="">All baccalaureates</option>
                    @foreach ($bacTypes as $bacType)
                        <option value="{{ $bacType }}" @selected(request('bac_type') === $bacType)>{{ $bacType }}</option>
                    @endforeach
                </select>
                <select name="recommended_program" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                    <option value="">All recommended programs</option>
                    @foreach ($recommendedPrograms as $recommendedProgram)
                        <option value="{{ $recommendedProgram }}" @selected(request('recommended_program') === $recommendedProgram)>{{ $recommendedProgram }}</option>
                    @endforeach
                </select>
                <select name="final_status" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                    <option value="">All final statuses</option>
                    @foreach ($finalStatuses as $finalStatus)
                        <option value="{{ $finalStatus }}" @selected(request('final_status') === $finalStatus)>{{ $finalStatus }}</option>
                    @endforeach
                </select>
                <div class="flex gap-3">
                    <x-button type="submit" class="flex-1">Filter</x-button>
                    <x-button :href="route('historical-admissions.index')" variant="secondary" class="flex-1">Reset</x-button>
                </div>
            </div>
        </form>

        <x-table>
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                <tr>
                    <th class="px-5 py-4">Bac</th>
                    <th class="px-5 py-4">Average</th>
                    <th class="px-5 py-4">Admitted program</th>
                    <th class="px-5 py-4">Recommended program</th>
                    <th class="px-5 py-4">Final status</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($historicalAdmissions as $historicalAdmission)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-4">{{ $historicalAdmission->bac_type }}</td>
                        <td class="px-5 py-4">{{ number_format((float) $historicalAdmission->general_average, 2) }}/20</td>
                        <td class="px-5 py-4">{{ $historicalAdmission->admitted_program ?: 'N/A' }}</td>
                        <td class="px-5 py-4">{{ $historicalAdmission->recommended_program ?: 'N/A' }}</td>
                        <td class="px-5 py-4">{{ $historicalAdmission->final_status ?: 'N/A' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap justify-end gap-2">
                                <x-button :href="route('historical-admissions.show', $historicalAdmission)" variant="secondary">View</x-button>
                                <x-button :href="route('historical-admissions.edit', $historicalAdmission)" variant="ghost">Edit</x-button>
                                <form method="POST" action="{{ route('historical-admissions.destroy', $historicalAdmission) }}" onsubmit="return confirm('Delete this historical record?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" type="submit">Delete</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">No historical records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>

        {{ $historicalAdmissions->links() }}
    </div>
</x-layouts.app>
