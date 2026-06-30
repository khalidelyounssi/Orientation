<x-layouts.app :title="'Program details | Orientation Campus'">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Program</p>
                <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">{{ $program->name }}</h1>
                <p class="mt-3 text-sm text-slate-600">{{ $program->faculty ?: 'Faculty not provided' }} · {{ $program->department ?: 'Department not provided' }}</p>
            </div>
            <div class="flex gap-3">
                <x-button :href="route('programs.edit', $program)">Edit</x-button>
                <x-status-badge :status="$program->is_active ? 'active' : 'inactive'" />
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="soft-card rounded-[2rem] p-6 space-y-5">
                <div><p class="text-sm text-slate-500">Slug</p><p class="mt-1 font-medium text-slate-900">{{ $program->slug }}</p></div>
                <div><p class="text-sm text-slate-500">Capacity</p><p class="mt-1 font-medium text-slate-900">{{ $program->capacity ?? 'Not provided' }}</p></div>
                <div><p class="text-sm text-slate-500">Minimum average</p><p class="mt-1 font-medium text-slate-900">{{ $program->minimum_average !== null ? number_format((float) $program->minimum_average, 2).'/20' : 'Not provided' }}</p></div>
                <div><p class="text-sm text-slate-500">Description</p><p class="mt-1 font-medium text-slate-900">{{ $program->description ?: 'No description.' }}</p></div>
            </div>
            <div class="soft-card rounded-[2rem] p-6 space-y-5">
                <div><p class="text-sm text-slate-500">Required subjects</p><p class="mt-1 font-medium text-slate-900 whitespace-pre-line">{{ $program->required_subjects ?: 'Not provided' }}</p></div>
                <div><p class="text-sm text-slate-500">Important subjects</p><p class="mt-1 font-medium text-slate-900 whitespace-pre-line">{{ $program->important_subjects ?: 'Not provided' }}</p></div>
            </div>
        </div>
    </div>
</x-layouts.app>
