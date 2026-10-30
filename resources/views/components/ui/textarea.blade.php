@props(['name', 'id' => null, 'bag' => 'default', 'help' => false, 'rows' => 4, 'errorKey' => null])
@php
    $id ??= $name;
    $invalid = $errors->getBag($bag)->has($errorKey ?? $name);
@endphp
<textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}"
    aria-describedby="{{ trim(($help ? $id.'-help ' : '').$id.'-error') }}" @if ($invalid) aria-invalid="true" @endif
    {{ $attributes->merge(['class' => 'block w-full rounded border bg-papel px-3 py-2.5 text-base leading-6 text-tinta transition-colors duration-150 placeholder:text-muted hover:border-tinta focus-visible:border-tinta disabled:bg-ground '.($invalid ? 'border-danger' : 'border-line-strong')]) }}>{{ $slot }}</textarea>
