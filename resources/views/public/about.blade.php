<x-layouts.public :title="__('web.about.title')" :description="__('web.about.meta')">
    <x-public-hero :title="__('web.about.title')" :lead="__('web.about.lead')" />

    <div class="mx-auto max-w-[1240px] safe-x py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="flex flex-col gap-14">
            @foreach (__('web.about.sections') as $section)
                <section class="grid gap-4 lg:grid-cols-12" aria-labelledby="about-{{ $loop->index }}">
                    <h2 id="about-{{ $loop->index }}" class="text-xl font-extrabold lg:col-span-4">{{ $section['title'] }}</h2>
                    <div class="measure flex flex-col gap-4 text-lg leading-8 lg:col-span-7 lg:col-start-6">
                        @foreach ($section['body'] as $p)<p>{{ $p }}</p>@endforeach
                    </div>
                </section>
            @endforeach
            <section class="grid gap-4 border-t border-line pt-10 lg:grid-cols-12" aria-label="{{ __('web.footer.operated_by', ['business' => '']) }}">
                <p class="measure text-base text-muted lg:col-span-7 lg:col-start-6">{{ __('web.about.operator', ['business' => config('sinowala.business.name'), 'address' => config('sinowala.business.address')]) }}</p>
                <div class="lg:col-span-7 lg:col-start-6"><x-ui.button :href="route('register')" icon-right="arrow-right">{{ __('web.about.cta') }}</x-ui.button></div>
            </section>
        </div>
    </div>
</x-layouts.public>
