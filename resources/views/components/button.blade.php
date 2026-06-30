@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
])

@php
    $variants = [
        'primary' => 'bg-teal-700 text-white hover:bg-teal-800',
        'secondary' => 'bg-white text-slate-800 border border-slate-300 hover:border-slate-900',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700',
        'ghost' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-2xl px-4 py-2.5 text-sm font-semibold transition '.($variants[$variant] ?? $variants['primary'])]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-2xl px-4 py-2.5 text-sm font-semibold transition '.($variants[$variant] ?? $variants['primary'])]) }}>
        {{ $slot }}
    </button>
@endif
