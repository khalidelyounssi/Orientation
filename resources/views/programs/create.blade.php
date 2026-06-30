<x-layouts.app :title="'New program | Orientation Campus'">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Program</p>
            <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">Add program</h1>
        </div>

        <form method="POST" action="{{ route('programs.store') }}" class="space-y-6">
            @csrf
            @include('programs._form')
            <div class="flex flex-col gap-3 sm:flex-row">
                <x-button type="submit">Save program</x-button>
                <x-button :href="route('programs.index')" variant="secondary">Cancel</x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
