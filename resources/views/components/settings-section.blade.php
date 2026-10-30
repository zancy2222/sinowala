{{-- One settings row: label column (title + description) beside its panel; stacks on phones. --}}
@props(['id', 'title', 'description' => null])
<section id="{{ $id }}" aria-labelledby="{{ $id }}-title" class="grid scroll-mt-24 gap-4 border-t border-line py-8 first:border-t-0 first:pt-0 lg:grid-cols-[300px_minmax(0,1fr)] lg:gap-10">
    <div>
        <h2 id="{{ $id }}-title" class="text-lg font-extrabold">{{ $title }}</h2>
        @if ($description)<p class="mt-1 text-sm text-muted">{{ $description }}</p>@endif
        {{ $aside ?? '' }}
    </div>
    <div {{ $attributes->merge(['class' => 'min-w-0 rounded-lg border border-line bg-papel p-5 sm:p-6']) }}>
        {{ $slot }}
    </div>
</section>
