@props(['title' => null, 'description' => null, 'padding' => true, 'as' => 'section', 'headingLevel' => 2, 'id' => null])
@php
    $tag = in_array($as, ['section', 'div', 'article', 'aside'], true) ? $as : 'section';
    $h = 'h'.max(2, min(4, (int) $headingLevel));
    $hid = $id ? $id.'-title' : null;
    $hasHeader = $title || isset($actions);
@endphp
<{{ $tag }} @if ($id) id="{{ $id }}" @endif @if ($title && $hid) aria-labelledby="{{ $hid }}" @endif {{ $attributes->merge(['class' => 'rounded-lg border border-line bg-papel']) }}>
    @if ($hasHeader)
        <div class="flex flex-wrap items-start justify-between gap-x-4 gap-y-2 px-5 pt-5 sm:px-6">
            <div class="min-w-0">
                @if ($title)<{{ $h }} @if ($hid) id="{{ $hid }}" @endif class="text-lg font-extrabold">{{ $title }}</{{ $h }}>@endif
                @if ($description)<p class="mt-1 text-sm text-muted">{{ $description }}</p>@endif
            </div>
            @isset($actions)<div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>@endisset
        </div>
    @endif
    <div @class([
        'px-5 pb-5 sm:px-6' => $padding,
        'pt-4' => $padding && $hasHeader,
        'pt-5' => $padding && ! $hasHeader,
        'pt-3' => ! $padding && $hasHeader,
    ])>
        {{ $slot }}
    </div>
</{{ $tag }}>
