{{-- Screen title row: one h1 (a noun), an optional line about the data, and real actions only. --}}
@props(['title', 'subtitle' => null])
<div {{ $attributes->merge(['class' => 'mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between']) }}>
    <div class="min-w-0">
        <h1 class="text-xl font-extrabold sm:text-2xl">{{ $title }}</h1>
        @if ($subtitle)<p class="mt-1 text-base text-muted">{{ $subtitle }}</p>@endif
    </div>
    @isset($actions)
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">{{ $actions }}</div>
    @endisset
</div>
