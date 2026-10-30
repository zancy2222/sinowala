@props(['name', 'size' => 20])
<svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" {{ $attributes->merge(['class' => 'shrink-0']) }}>{!! \App\Support\Icon::inner($name) !!}</svg>
