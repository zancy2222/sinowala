<footer class="on-dark bg-tinta text-white" aria-labelledby="footer-title">
    <h2 id="footer-title" class="sr-only">SinoWala</h2>
    <div class="mx-auto max-w-[1240px] safe-x pt-16 pb-10 sm:px-6 lg:px-8 lg:pt-20">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <x-ui.logo :height="40" white />
                <p class="mt-5 max-w-sm text-base leading-7 text-white/80">{{ __('web.footer.description') }}</p>
                <x-install-button class="mt-6 rounded-full!" />
            </div>
            <nav aria-label="{{ __('web.footer.site') }}" class="lg:col-span-3 lg:col-start-7">
                <p class="text-sm font-semibold text-white/70">{{ __('web.footer.site') }}</p>
                <ul class="mt-3 flex flex-col">
                    @foreach (['about', 'faq', 'help', 'contact'] as $name)
                        <li><a href="{{ route($name) }}" class="inline-flex min-h-10 items-center text-base text-white underline-offset-4 hover:underline">{{ __('web.footer.links.'.$name) }}</a></li>
                    @endforeach
                </ul>
            </nav>
            <nav aria-label="{{ __('web.footer.legal') }}" class="lg:col-span-3">
                <p class="text-sm font-semibold text-white/70">{{ __('web.footer.legal') }}</p>
                <ul class="mt-3 flex flex-col">
                    @foreach (['privacy', 'terms', 'cookies', 'privacy-request', 'accessibility'] as $name)
                        <li><a href="{{ route($name) }}" class="inline-flex min-h-10 items-center text-base text-white underline-offset-4 hover:underline">{{ __('web.footer.links.'.$name) }}</a></li>
                    @endforeach
                    <li><button type="button" @click="$dispatch('open-cookie-settings')" class="inline-flex min-h-10 items-center text-base text-white underline-offset-4 hover:underline">{{ __('web.footer.links.cookie_settings') }}</button></li>
                </ul>
            </nav>
        </div>
        <div class="mt-20 flex flex-col gap-2 text-sm text-white/75 sm:flex-row sm:justify-between">
            <p>{{ __('app.footer.rights', ['year' => now()->year]) }}</p>
            <p>{{ __('web.footer.operated_by', ['business' => config('sinowala.business.name')]) }}</p>
        </div>
    </div>
</footer>
