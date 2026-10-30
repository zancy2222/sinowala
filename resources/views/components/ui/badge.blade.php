{{-- Status badge. Always carries a word, never colour alone. --}}
@props(['tone' => 'neutral'])
@php
    $tones = [
        'neutral' => 'bg-abo text-tinta',
        'wala' => 'bg-kahel text-tinta',
        'success' => 'bg-success-soft text-success',
        'danger' => 'bg-danger-soft text-danger',
        'outline' => 'border border-line-strong text-tinta',
        'dark' => 'bg-tinta text-white',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex h-6 flex-none items-center gap-1 whitespace-nowrap rounded-sm px-2 text-xs font-semibold '.($tones[$tone] ?? $tones['neutral'])]) }}>{{ $slot }}</span>
