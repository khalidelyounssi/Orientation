<x-layouts.app :title="'Programs | Orientation Campus'">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Programs</p>
                <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">Academic program management</h1>
            </div>
            <x-button :href="route('programs.create')">Add program</x-button>
        </div>

        <form method="GET" class="soft-card rounded-[2rem] p-5">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <select name="department" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                    <option value="">All departments</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}</option>
                    @endforeach
                </select>
                <select name="faculty" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                    <option value="">All faculties</option>
                    @foreach ($faculties as $faculty)
                        <option value="{{ $faculty }}" @selected(request('faculty') === $faculty)>{{ $faculty }}</option>
                    @endforeach
                </select>
                <div class="flex gap-3">
                    <x-button type="submit" class="flex-1">Filter</x-button>
                    <x-button :href="route('programs.index')" variant="secondary" class="flex-1">Reset</x-button>
                </div>
            </div>
        </form>

        <x-table>
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                <tr>
                    <th class="px-5 py-4">Program</th>
                    <th class="px-5 py-4">Department</th>
                    <th class="px-5 py-4">Faculty</th>
                    <th class="px-5 py-4">Capacity</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($programs as $program)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-900">{{ $program->name }}</p>
                            <p class="text-xs text-slate-500">{{ $program->slug }}</p>
                        </td>
                        <td class="px-5 py-4">{{ $program->department ?: 'N/A' }}</td>
                        <td class="px-5 py-4">{{ $program->faculty ?: 'N/A' }}</td>
                        <td class="px-5 py-4">{{ $program->capacity ?? 'N/A' }}</td>
                        <td class="px-5 py-4"><x-status-badge :status="$program->is_active ? 'active' : 'inactive'" /></td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap justify-end gap-2">
                                <x-button :href="route('programs.show', $program)" variant="secondary">View</x-button>
                                <x-button :href="route('programs.edit', $program)" variant="ghost">Edit</x-button>
                                <form method="POST" action="{{ route('programs.destroy', $program) }}" onsubmit="return confirm('Delete this program?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" type="submit">Delete</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">No programs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>

        {{ $programs->links() }}
    </div>
</x-layouts.app>
