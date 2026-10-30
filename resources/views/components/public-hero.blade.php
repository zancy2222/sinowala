{{-- Title band for inner public pages: one h1 and the line that says what the page is for. --}}
@props(['title', 'lead' => null])
<section class="bg-ground">
    <div class="mx-auto max-w-[1240px] safe-x pt-14 pb-12 sm:px-6 lg:px-8 lg:pt-20 lg:pb-16">
        <h1 class="max-w-[22ch] text-2xl font-extrabold sm:text-3xl">{{ $title }}</h1>
        @if ($lead)<p class="measure mt-5 text-lg leading-8">{{ $lead }}</p>@endif
        {{ $slot }}
    </div>
</section>
