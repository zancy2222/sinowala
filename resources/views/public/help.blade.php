<x-layouts.public :title="__('web.help.title')" :description="__('web.help.meta')">
    <x-public-hero :title="__('web.help.title')" :lead="__('web.help.lead')" />

    <div class="mx-auto grid max-w-[1240px] grid-cols-[minmax(0,1fr)] gap-10 safe-x py-16 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-24">
        <nav aria-labelledby="help-toc" class="lg:sticky lg:top-24 lg:col-span-3 lg:self-start">
            <h2 id="help-toc" class="text-sm font-semibold text-muted">{{ __('web.help.toc') }}</h2>
            <ul class="mt-2 gap-x-10 md:columns-2 lg:columns-1">
                @foreach (__('web.help.sections') as $s)
                    <li><a href="#{{ $s['id'] }}" class="flex min-h-10 break-inside-avoid items-center text-base font-semibold underline-offset-4 hover:underline">{{ $s['title'] }}</a></li>
                @endforeach
            </ul>
        </nav>
        <div class="flex flex-col gap-14 lg:col-span-8 lg:col-start-5">
            @foreach (__('web.help.sections') as $s)
                <section id="{{ $s['id'] }}" aria-labelledby="{{ $s['id'] }}-title" class="scroll-mt-24">
                    <h2 id="{{ $s['id'] }}-title" class="text-xl font-extrabold">{{ $s['title'] }}</h2>
                    <ol class="measure mt-4 flex list-decimal flex-col gap-3 pl-6 text-base leading-7 marker:font-extrabold">
                        @foreach ($s['items'] as $item)<li>{{ $item }}</li>@endforeach
                    </ol>
                </section>
            @endforeach
        </div>
    </div>
</x-layouts.public>
