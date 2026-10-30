{{-- Form field: visible label, optional help, error announced and tied with aria-describedby. --}}
@props(['label', 'for', 'help' => null, 'error' => null, 'bag' => 'default', 'optional' => false])
@php
    $errorKey = $error ?? $for;
    $message = $errors->getBag($bag)->first($errorKey);
@endphp
<div {{ $attributes->merge(['class' => 'flex flex-col gap-2']) }}>
    <label for="{{ $for }}" class="text-sm font-semibold text-tinta">
        {{ $label }}@if ($optional)<span class="font-medium text-muted"> ({{ __('ui.optional') }})</span>@endif
    </label>
    @if ($help)
        <p id="{{ $for }}-help" class="-mt-1 text-sm text-muted">{{ $help }}</p>
    @endif
    {{ $slot }}
    <p id="{{ $for }}-error" class="flex items-start gap-1.5 text-sm text-danger empty:hidden" aria-live="polite">@if ($message)<x-ui.icon name="circle-alert" size="16" class="mt-0.5" /><span>{{ $message }}</span>@endif</p>
</div>
