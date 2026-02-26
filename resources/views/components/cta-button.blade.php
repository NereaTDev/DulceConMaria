@props(['href' => '#cta', 'variant' => 'primary'])

@php
    $base = 'inline-flex items-center justify-center rounded-full text-sm font-semibold px-5 py-2.5 transition-colors';
    $variants = [
        'primary' => 'bg-rose-600 text-white hover:bg-rose-700 shadow-sm',
        'outline' => 'border border-rose-300 text-rose-700 hover:bg-rose-50',
    ];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $base.' '.($variants[$variant] ?? $variants['primary'])]) }}>
    {{ $slot }}
</a>
