{{-- One radio option as a selectable row; group them inside a <fieldset> with a <legend>. --}}
@props(['name', 'value', 'id' => null, 'label', 'description' => null])
@php $id ??= $name.'-'.$value; @endphp
<label for="{{ $id }}" class="flex min-h-11 cursor-pointer items-start gap-3 rounded border border-line bg-papel px-4 py-3 transition-colors duration-150 hover:border-line-strong has-[:checked]:border-tinta has-[:checked]:bg-hover has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-tinta">
    <input type="radio" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}" {{ $attributes->merge(['class' => 'mt-1 size-4 shrink-0 cursor-pointer accent-tinta focus-visible:outline-none']) }}>
    <span class="flex flex-col gap-0.5">
        <span class="text-base font-semibold text-tinta">{{ $label }}</span>
        @if ($description)<span class="text-sm text-muted">{{ $description }}</span>@endif
    </span>
</label>
