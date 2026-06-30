<x-layouts.app :title="'Edit program | Orientation Campus'">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Program</p>
            <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">Edit {{ $program->name }}</h1>
        </div>

        <form method="POST" action="{{ route('programs.update', $program) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('programs._form')
            <div class="flex flex-col gap-3 sm:flex-row">
                <x-button type="submit">Update</x-button>
                <x-button :href="route('programs.show', $program)" variant="secondary">Back</x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
