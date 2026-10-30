<x-layouts.public :title="__('web.faq.title')" :description="__('web.faq.meta')">
    <x-public-hero :title="__('web.faq.heading')">
        <p class="mt-5 text-lg">{{ __('web.faq.intro') }} <a href="{{ route('contact') }}" class="link font-semibold">{{ __('web.faq.contact') }}</a></p>
    </x-public-hero>

    <div class="mx-auto max-w-[1240px] safe-x py-16 sm:px-6 lg:px-8 lg:py-24">
        <dl class="grid gap-x-14 gap-y-10 md:grid-cols-2">
            @foreach (__('web.faq.items') as $item)
                <div class="measure">
                    <dt class="text-lg font-extrabold">{{ $item['q'] }}</dt>
                    <dd class="mt-2 text-base leading-7">{{ $item['a'] }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
</x-layouts.public>
