{{-- Initials only; no photos. --}}
@props(['name', 'size' => 'md'])
@php
    $initials = \Illuminate\Support\Str::of($name)->explode(' ')->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');
    $sizes = ['sm' => 'size-7 text-xs', 'md' => 'size-9 text-sm', 'lg' => 'size-12 text-base'];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex flex-none items-center justify-center rounded-full bg-abo font-extrabold text-tinta '.($sizes[$size] ?? $sizes['md'])]) }} aria-hidden="true">{{ $initials }}</span>
