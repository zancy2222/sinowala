{{--
    Button: primary | secondary | ghost | destructive | danger-solid; sizes sm | md | lg.
    Renders <a> when href is given. `loading` = Livewire action name for the busy state.
--}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'iconRight' => null,
    'loading' => null,
    'block' => false,
    'shortcut' => null,
])
@php
    $base = 'relative inline-flex select-none items-center justify-center gap-2 whitespace-nowrap rounded font-semibold transition-[background-color,border-color,color,transform] duration-150 ease-out active:translate-y-px disabled:cursor-not-allowed aria-busy:cursor-wait';
    $sizes = [
        'sm' => 'min-h-11 px-3 text-sm',
        'md' => 'min-h-11 px-4 text-sm',
        'lg' => 'min-h-12 px-5 text-base',
    ];
    $variants = [
        'primary' => 'bg-tinta text-white hover:bg-tinta-2 disabled:bg-abo disabled:text-muted',
        'secondary' => 'border border-line-strong bg-papel text-tinta hover:bg-hover active:bg-press disabled:border-line disabled:text-muted',
        'ghost' => 'text-tinta hover:bg-press active:bg-press disabled:text-muted',
        'destructive' => 'border border-danger bg-papel text-danger hover:bg-danger-soft disabled:border-line disabled:text-muted',
        'danger-solid' => 'bg-danger text-white hover:bg-[#9a1d13] disabled:bg-abo disabled:text-muted',
    ];
    $classes = $base.' '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']).($block ? ' w-full' : '');
    $iconSize = $size === 'lg' ? 20 : 18;
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if ($shortcut) aria-keyshortcuts="{{ $shortcut }}" @endif>
        @if ($icon)<x-ui.icon :name="$icon" :size="$iconSize" />@endif
        <span>{{ $slot }}</span>
        @if ($iconRight)<x-ui.icon :name="$iconRight" :size="$iconSize" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}
        @if ($loading) wire:loading.attr="aria-busy" wire:target="{{ $loading }}" @endif
        @if ($shortcut) aria-keyshortcuts="{{ $shortcut }}" @endif>
        @if ($loading)
            <span wire:loading.flex wire:target="{{ $loading }}" class="absolute inset-0 hidden items-center justify-center" aria-hidden="true">
                <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3"/><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
            </span>
        @endif
        <span class="inline-flex items-center gap-2" @if ($loading) wire:loading.class="invisible" wire:target="{{ $loading }}" @endif>
            @if ($icon)<x-ui.icon :name="$icon" :size="$iconSize" />@endif
            <span>{{ $slot }}</span>
            @if ($iconRight)<x-ui.icon :name="$iconRight" :size="$iconSize" />@endif
        </span>
    </button>
@endif
