@props(['name', 'id' => null, 'label' => null, 'description' => null, 'bag' => 'default', 'value' => '1'])
@php
    $id ??= $name;
    $invalid = $errors->getBag($bag)->has($name);
    $message = $errors->getBag($bag)->first($name);
@endphp
<div class="flex flex-col gap-1">
    <label for="{{ $id }}" class="flex min-h-11 cursor-pointer items-start gap-3 py-2">
        <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
            aria-describedby="{{ $description ? $id.'-desc ' : '' }}{{ $id }}-error" @if ($invalid) aria-invalid="true" @endif
            {{ $attributes->merge(['class' => 'mt-0.5 size-5 shrink-0 cursor-pointer accent-tinta']) }}>
        <span class="flex flex-col gap-0.5">
            <span class="text-base text-tinta">{{ $label ?? $slot }}</span>
            @if ($description)<span id="{{ $id }}-desc" class="text-sm text-muted">{{ $description }}</span>@endif
        </span>
    </label>
    <p id="{{ $id }}-error" class="flex items-start gap-1.5 text-sm text-danger empty:hidden" aria-live="polite">@if ($message)<x-ui.icon name="circle-alert" size="16" class="mt-0.5" /><span>{{ $message }}</span>@endif</p>
</div>
