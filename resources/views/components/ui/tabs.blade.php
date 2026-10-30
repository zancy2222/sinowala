{{--
    Tabs as links: each tab is its own URL, so they work without script and with the back button.
    $items = [['label' => '', 'href' => '', 'active' => bool]]
--}}
@props(['items', 'label'])
<nav aria-label="{{ $label }}" {{ $attributes->merge(['class' => 'inline-flex rounded border border-line bg-papel p-1']) }}>
    @foreach ($items as $item)
        <a href="{{ $item['href'] }}" @if ($item['active']) aria-current="page" @endif
            class="inline-flex min-h-10 items-center rounded-sm px-4 text-sm font-semibold transition-colors {{ $item['active'] ? 'bg-tinta text-white' : 'text-tinta hover:bg-hover' }}">
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
