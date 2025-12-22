@props([
    'active' => false,
    'mobile' => false,
])

@php
    $base = $mobile
        ? 'flex-1 text-center px-3 py-2 rounded-xl text-sm'
        : 'px-3 py-1.5 rounded-lg text-sm';

    $cls = $active
        ? 'bg-indigo-600/20 text-indigo-200 border border-indigo-500/30'
        : 'text-white/70 hover:text-white hover:bg-white/10';
@endphp

<a {{ $attributes->merge(['class' => "$base $cls transition"]) }}>
    {{ $slot }}
</a>
