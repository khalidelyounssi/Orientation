@props(['status'])

@php
    $status = strtolower((string) $status);

    $map = [
        'pending' => 'bg-amber-100 text-amber-800',
        'predicted' => 'bg-sky-100 text-sky-800',
        'reviewed' => 'bg-indigo-100 text-indigo-800',
        'high' => 'bg-rose-100 text-rose-800',
        'medium' => 'bg-amber-100 text-amber-800',
        'low' => 'bg-emerald-100 text-emerald-800',
        'dropout' => 'bg-rose-100 text-rose-800',
        'not dropout' => 'bg-emerald-100 text-emerald-800',
        'not_dropout' => 'bg-emerald-100 text-emerald-800',
        'active' => 'bg-emerald-100 text-emerald-800',
        'inactive' => 'bg-slate-200 text-slate-700',
    ];

    $labels = [
        'not_dropout' => 'Stable',
        'not dropout' => 'Stable',
        'dropout' => 'Needs review',
        'high' => 'High',
        'medium' => 'Moderate',
        'low' => 'Low',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex rounded-full px-3 py-1 text-xs font-semibold '.($map[$status] ?? 'bg-slate-100 text-slate-700')]) }}>
    {{ $labels[$status] ?? str_replace('_', ' ', ucfirst($status)) }}
</span>
