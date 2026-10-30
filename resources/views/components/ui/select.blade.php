@props(['name', 'id' => null, 'bag' => 'default', 'help' => false, 'errorKey' => null])
@php
    $id ??= $name;
    $invalid = $errors->getBag($bag)->has($errorKey ?? $name);
@endphp
<div class="relative">
    <select name="{{ $name }}" id="{{ $id }}"
        aria-describedby="{{ trim(($help ? $id.'-help ' : '').$id.'-error') }}" @if ($invalid) aria-invalid="true" @endif
        {{ $attributes->merge(['class' => 'block min-h-11 w-full appearance-none rounded border bg-papel py-2 pr-10 pl-3 text-base text-tinta transition-colors duration-150 hover:border-tinta focus-visible:border-tinta disabled:bg-ground disabled:text-muted '.($invalid ? 'border-danger' : 'border-line-strong')]) }}>
        {{ $slot }}
    </select>
    <x-ui.icon name="chevron-down" size="18" class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-muted" />
</div>
