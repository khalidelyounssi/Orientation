<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-3xl border border-slate-200 bg-white/80 shadow-lg shadow-slate-200/50']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
            {{ $slot }}
        </table>
    </div>
</div>
