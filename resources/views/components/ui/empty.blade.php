{{-- Empty state: what this is, and the one action to take. --}}
@props(['title', 'icon' => 'calendar'])
<div {{ $attributes->merge(['class' => 'flex flex-col items-start gap-3 rounded-lg border border-dashed border-line-strong bg-papel px-5 py-5 sm:flex-row sm:items-center sm:gap-4']) }}>
    <x-ui.icon :name="$icon" size="24" class="text-muted" />
    <div class="min-w-0 flex-1">
        <p class="font-extrabold">{{ $title }}</p>
        @if ($slot->isNotEmpty())<p class="mt-0.5 text-sm text-muted">{{ $slot }}</p>@endif
    </div>
    @isset($action)<div class="shrink-0">{{ $action }}</div>@endisset
</div>
