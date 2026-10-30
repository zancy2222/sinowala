@props(['href' => null, 'icon' => null, 'danger' => false, 'type' => 'submit'])
@php $cls = 'flex min-h-11 w-full items-center gap-2.5 rounded px-3 text-left text-sm font-semibold transition-colors hover:bg-hover '.($danger ? 'text-danger' : 'text-tinta'); @endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>@if ($icon)<x-ui.icon :name="$icon" size="18" />@endif<span>{{ $slot }}</span></a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $cls]) }}>@if ($icon)<x-ui.icon :name="$icon" size="18" />@endif<span>{{ $slot }}</span></button>
@endif
