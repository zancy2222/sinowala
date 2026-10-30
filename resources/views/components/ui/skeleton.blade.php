{{-- Loading placeholder: flat bars at the real size, no shimmer. --}}
@props(['lines' => 1, 'height' => 'h-4'])
<div {{ $attributes->merge(['class' => 'flex flex-col gap-2']) }} aria-hidden="true">
    @for ($i = 0; $i < $lines; $i++)
        <div class="skeleton {{ $height }} {{ $i === $lines - 1 && $lines > 1 ? 'w-2/3' : 'w-full' }}"></div>
    @endfor
</div>
