<x-layouts.app :title="'New historical record | Orientation Campus'">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Historical data</p>
            <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">Add historical record</h1>
        </div>

        <form method="POST" action="{{ route('historical-admissions.store') }}" class="space-y-6">
            @csrf
            @include('historical-admissions._form')
            <div class="flex flex-col gap-3 sm:flex-row">
                <x-button type="submit">Save</x-button>
                <x-button :href="route('historical-admissions.index')" variant="secondary">Cancel</x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
