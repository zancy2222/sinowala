{{-- Segmented control for a Livewire action (calendar views). $options = [value => label] --}}
@props(['options', 'current', 'action', 'label', 'shortcuts' => []])
<div role="group" aria-label="{{ $label }}" {{ $attributes->merge(['class' => 'inline-flex rounded border border-line bg-papel p-1']) }}>
    @foreach ($options as $value => $text)
        <button type="button" wire:click="{{ $action }}('{{ $value }}')" aria-pressed="{{ $current === $value ? 'true' : 'false' }}"
            @if (isset($shortcuts[$value])) aria-keyshortcuts="{{ $shortcuts[$value] }}" title="{{ $text }} ({{ __('ui.shortcut') }}: {{ $shortcuts[$value] }})" @endif
            class="inline-flex min-h-10 items-center rounded-sm px-3 text-sm font-semibold transition-colors sm:px-4 {{ $current === $value ? 'bg-tinta text-white' : 'text-tinta hover:bg-hover' }}">
            {{ $text }}
        </button>
    @endforeach
</div>
