@props(['tone' => 'neutral', 'title' => null, 'icon' => null])
@php
    $tones = [
        'neutral' => ['bg-papel border-line-strong', 'info', 'text-tinta'],
        'pending' => ['bg-abo border-abo', 'clock', 'text-tinta'],
        'success' => ['bg-success-soft border-success', 'circle-check', 'text-success'],
        'danger' => ['bg-danger-soft border-danger', 'triangle-alert', 'text-danger'],
    ];
    [$cls, $defaultIcon, $iconColor] = $tones[$tone] ?? $tones['neutral'];
@endphp
<div {{ $attributes->merge(['class' => 'flex gap-3 rounded-lg border px-4 py-3.5 '.$cls, 'role' => $tone === 'danger' ? 'alert' : 'status']) }}>
    <x-ui.icon :name="$icon ?? $defaultIcon" size="20" class="mt-0.5 {{ $iconColor }}" />
    <div class="min-w-0 flex-1 text-sm leading-6 text-tinta">
        @if ($title)<p class="font-extrabold">{{ $title }}</p>@endif
        <div>{{ $slot }}</div>
    </div>
    @isset($action)<div class="shrink-0 self-center">{{ $action }}</div>@endisset
</div>
