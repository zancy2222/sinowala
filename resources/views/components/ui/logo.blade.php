{{-- Brand logo from /public/brand — never redrawn. Height only, so it never stretches. --}}
@props(['variant' => 'horizontal', 'height' => 32, 'white' => false])
@php
    $file = match ($variant) {
        'mark' => $white ? 'sinowala-mark-white.svg' : 'sinowala-mark.svg',
        'stacked' => 'sinowala-logo-stacked.svg',
        default => $white ? 'sinowala-logo-horizontal-white.svg' : 'sinowala-logo-horizontal.svg',
    };
    $heights = [24 => 'h-6', 28 => 'h-7', 32 => 'h-8', 40 => 'h-10', 48 => 'h-12', 64 => 'h-16'];
@endphp
<img src="{{ asset('brand/'.$file) }}" height="{{ $height }}" {{ $attributes->merge(['class' => 'block w-auto '.($heights[$height] ?? 'h-8'), 'alt' => 'SinoWala']) }}>
