{{--
    Dialog on native <dialog>: focus stays inside while open, Escape closes, focus returns to the trigger.
    Becomes a bottom sheet under 640px (`sheet` makes it a sheet at every width).
    Open with: $dispatch('open-dialog', { name: '...' }) — or from Livewire: $this->dispatch('open-dialog', name: '...')
--}}
@props(['name', 'title', 'description' => null, 'sheet' => false, 'size' => 'md'])
@php $widths = ['sm' => 'max-w-md', 'md' => 'max-w-lg', 'lg' => 'max-w-2xl']; @endphp
<dialog x-data="swDialog(@js($name))" wire:ignore.self
    aria-labelledby="dialog-{{ $name }}-title" @if ($description) aria-describedby="dialog-{{ $name }}-desc" @endif
    {{ $attributes->merge(['class' => 'sw-dialog '.($sheet ? 'sw-sheet ' : '').($widths[$size] ?? $widths['md'])]) }}>
    <div class="flex max-h-[inherit] flex-col">
        <div class="flex items-start justify-between gap-4 border-b border-line px-5 py-4 sm:px-6">
            <div class="min-w-0">
                <h2 id="dialog-{{ $name }}-title" class="text-lg font-extrabold">{{ $title }}</h2>
                @if ($description)<p id="dialog-{{ $name }}-desc" class="mt-0.5 text-sm text-muted">{{ $description }}</p>@endif
            </div>
            <button type="button" @click="close()" class="-mt-1 -mr-2 inline-flex size-11 shrink-0 items-center justify-center rounded text-tinta transition-colors hover:bg-press">
                <x-ui.icon name="x" size="20" />
                <span class="sr-only">{{ __('ui.close') }}</span>
            </button>
        </div>
        <div class="min-h-0 flex-1 overflow-y-auto px-5 py-5 sm:px-6">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="flex flex-col-reverse gap-2 border-t border-line px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                {{ $footer }}
            </div>
        @endisset
    </div>
</dialog>
