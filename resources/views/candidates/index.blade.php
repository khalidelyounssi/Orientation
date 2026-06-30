<x-layouts.app :title="'Students | Orientation Campus'">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Students</p>
                <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">Student management</h1>
                <p class="mt-3 text-sm text-slate-600">Add profiles, track status, and calculate a vigilance score.</p>
            </div>
            <x-button :href="route('candidates.create')">Add student</x-button>
        </div>

        <form method="GET" class="soft-card rounded-[2rem] p-5">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <select name="course" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                    <option value="">All courses</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course }}" @selected((string) request('course') === (string) $course)>Course {{ $course }}</option>
                    @endforeach
                </select>
                <select name="status" class="rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <div class="flex gap-3">
                    <x-button type="submit" class="flex-1">Filter</x-button>
                    <x-button :href="route('candidates.index')" variant="secondary" class="flex-1">Reset</x-button>
                </div>
            </div>
        </form>

        <form method="POST" action="{{ route('candidates.import') }}" enctype="multipart/form-data" class="soft-card rounded-[2rem] p-5">
            @csrf
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <label for="candidates_csv" class="text-sm font-semibold text-slate-800">Import students from CSV</label>
                    <p class="mt-1 text-sm text-slate-500">Quickly add multiple records from a structured CSV file.</p>
                    <input id="candidates_csv" name="candidates_csv" type="file" accept=".csv,text/csv" class="mt-3 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-600 focus:outline-none">
                    <x-input-error field="candidates_csv" />
                </div>
                <x-button type="submit">Import CSV</x-button>
            </div>
        </form>

        <x-table>
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                <tr>
                    <th class="px-5 py-4">Student</th>
                    <th class="px-5 py-4">Major</th>
                    <th class="px-5 py-4">Career GPA</th>
                    <th class="px-5 py-4">Latest level</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($candidates as $candidate)
                    @php $latestPrediction = $candidate->predictions->first(); @endphp
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-900">{{ trim(($candidate->first_name ?? '').' '.($candidate->last_name ?? '')) ?: ($candidate->external_student_id ? 'Student '.$candidate->external_student_id : 'Unnamed student') }}</p>
                            <p class="text-xs text-slate-500">{{ $candidate->external_student_id ? 'ID '.$candidate->external_student_id : ($candidate->email ?: 'Email not provided') }}</p>
                        </td>
                        <td class="px-5 py-4">{{ $candidate->predictive_data['MAJOR_1'] ?? 'N/A' }}</td>
                        <td class="px-5 py-4">{{ isset($candidate->predictive_data['CAREER_GPA']) ? number_format((float) $candidate->predictive_data['CAREER_GPA'], 2) : 'N/A' }}</td>
                        <td class="px-5 py-4">
                            @if ($latestPrediction)
                                <x-status-badge :status="strtolower($latestPrediction->risk_level ?? 'low')" />
                            @else
                                <span class="text-sm text-slate-500">None</span>
                            @endif
                        </td>
                        <td class="px-5 py-4"><x-status-badge :status="$candidate->status" /></td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap justify-end gap-2">
                                <x-button :href="route('candidates.show', $candidate)" variant="secondary">View</x-button>
                                <x-button :href="route('candidates.edit', $candidate)" variant="ghost">Edit</x-button>
                                <form method="POST" action="{{ route('candidates.predict', $candidate) }}">
                                    @csrf
                                    <x-button type="submit">Calculate score</x-button>
                                </form>
                                <form method="POST" action="{{ route('candidates.destroy', $candidate) }}" onsubmit="return confirm('Delete this student?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" type="submit">Delete</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>

        {{ $candidates->links() }}
    </div>
</x-layouts.app>
