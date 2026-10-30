{{-- Disclosure menu: the button toggles, Escape and an outside click close it, focus returns. --}}
@props(['label', 'align' => 'right', 'icon' => 'ellipsis', 'iconOnly' => false])
<div x-data="{ open: false }" @keydown.escape="if (open) { open = false; $refs.button.focus() }" @click.outside="open = false" {{ $attributes->merge(['class' => 'relative']) }}>
    <button x-ref="button" type="button" @click="open = !open" :aria-expanded="open.toString()"
        class="inline-flex min-h-11 min-w-11 items-center justify-center gap-2 rounded px-3 text-sm font-semibold text-tinta transition-colors hover:bg-press">
        <x-ui.icon :name="$icon" size="18" />
        <span @class(['sr-only' => $iconOnly])>{{ $label }}</span>
    </button>
    <div x-show="open" x-cloak x-transition.opacity.duration.150ms
        class="absolute z-30 mt-1 min-w-56 rounded-lg border border-line bg-papel p-1 shadow-overlay {{ $align === 'right' ? 'right-0' : 'left-0' }}">
        {{ $slot }}
    </div>
</div>
