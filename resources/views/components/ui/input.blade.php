@props(['name', 'id' => null, 'type' => 'text', 'bag' => 'default', 'help' => false, 'errorKey' => null])
@php
    $id ??= $name;
    $invalid = $errors->getBag($bag)->has($errorKey ?? $name);
    $describedBy = trim(($help ? $id.'-help ' : '').$id.'-error');
    $classes = 'block min-h-11 w-full rounded border bg-papel px-3 py-2 text-base text-tinta transition-colors duration-150 placeholder:text-muted hover:border-tinta focus-visible:border-tinta disabled:cursor-not-allowed disabled:border-line disabled:bg-ground disabled:text-muted read-only:bg-ground '.($invalid ? 'border-danger' : 'border-line-strong');
@endphp
@if ($type === 'password')
    {{-- Show/hide: needs script, so the button starts hidden. Hidden again on submit, so password managers save it. --}}
    <div class="relative" x-data="{ show: false }" x-init="$el.closest('form')?.addEventListener('submit', () => (show = false))">
        <input type="password" :type="show ? 'text' : 'password'" name="{{ $name }}" id="{{ $id }}"
            aria-describedby="{{ $describedBy }}" @if ($invalid) aria-invalid="true" @endif
            {{ $attributes->merge(['class' => $classes.' pr-12']) }}>
        <button type="button" x-cloak @click="show = !show" :aria-pressed="show.toString()" aria-controls="{{ $id }}"
            class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center rounded-r text-muted transition-colors hover:text-tinta">
            <x-ui.icon name="eye" size="20" x-show="!show" />
            <x-ui.icon name="eye-off" size="20" x-show="show" />
            <span class="sr-only" x-text="show ? @js(__('ui.password_hide')) : @js(__('ui.password_show'))">{{ __('ui.password_show') }}</span>
        </button>
    </div>
@else
<input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
    aria-describedby="{{ $describedBy }}" @if ($invalid) aria-invalid="true" @endif
    {{ $attributes->merge(['class' => $classes]) }}>
@endif
