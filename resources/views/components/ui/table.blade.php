{{-- Table: rules and alignment, no zebra. Scrolls inside its own frame on small screens. --}}
@props(['caption' => null, 'minWidth' => 'min-w-[40rem]'])
<div {{ $attributes->merge(['class' => 'overflow-x-auto']) }} @if ($caption) tabindex="0" role="region" aria-label="{{ $caption }}" @endif>
    <table class="w-full {{ $minWidth }} border-collapse text-left text-sm [&_td]:border-t [&_td]:border-line [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle [&_th]:px-4 [&_th]:pt-1 [&_th]:pb-2 [&_th]:text-xs [&_th]:font-semibold [&_th]:text-muted [&_tbody_tr]:transition-colors [&_tbody_tr:hover]:bg-hover">
        @if ($caption)<caption class="sr-only">{{ $caption }}</caption>@endif
        {{ $slot }}
    </table>
</div>
