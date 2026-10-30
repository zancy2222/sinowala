{{--
    Cookie banner + settings dialog. Equal weight for all three choices; never blocks reading.
    Analytics stays off until accepted, and only exists at all when ANALYTICS_ID is set.
--}}
@php $hasAnalytics = filled(config('sinowala.analytics.id')); @endphp
<div x-data="cookieConsent({
        version: @js(config('sinowala.cookie_version')),
        endpoint: @js(route('consent.store')),
        csrf: @js(csrf_token()),
        analyticsId: @js(config('sinowala.analytics.id')),
        analyticsSrc: @js(config('sinowala.analytics.src')),
        savedLabel: @js(__('web.cookies_banner.saved')),
    })">
    {{-- In the page flow, above everything: it pushes content down instead of covering it. --}}
    <section x-show="banner" @if (\App\Support\CookieConsent::choices() !== null) x-cloak @endif aria-labelledby="cookie-banner-title" class="bg-abo">
        <div class="mx-auto flex max-w-[1240px] flex-col gap-3 safe-x py-4 sm:px-6 lg:flex-row lg:items-center lg:gap-8 lg:px-8">
            <div class="min-w-0 flex-1">
                <h2 id="cookie-banner-title" class="text-base font-extrabold">{{ __('web.cookies_banner.title') }}</h2>
                <p class="mt-0.5 text-sm leading-6 text-tinta">
                    {{ __('web.cookies_banner.body') }}
                    <a href="{{ route('cookies') }}" class="link">{{ __('web.cookies_banner.policy') }}</a>
                </p>
            </div>
            <div class="grid shrink-0 grid-cols-1 gap-2 min-[420px]:grid-cols-3">
                <x-ui.button variant="secondary" size="sm" @click="acceptAll()">{{ __('web.cookies_banner.accept_all') }}</x-ui.button>
                <x-ui.button variant="secondary" size="sm" @click="necessaryOnly()">{{ __('web.cookies_banner.necessary') }}</x-ui.button>
                <x-ui.button variant="secondary" size="sm" @click="openSettings()">{{ __('web.cookies_banner.settings') }}</x-ui.button>
            </div>
        </div>
    </section>

    <x-ui.dialog name="cookies" :title="__('web.cookies_banner.dialog_title')" :description="__('web.cookies_banner.dialog_body')">
        <div class="flex flex-col gap-3">
            <div class="flex items-start justify-between gap-4 rounded border border-line px-4 py-3">
                <div>
                    <p class="font-semibold">{{ __('web.cookies_banner.necessary_title') }}</p>
                    <p class="mt-0.5 text-sm text-muted">{{ __('web.cookies_banner.necessary_body') }}</p>
                </div>
                <x-ui.badge>{{ __('web.cookies_banner.always_on') }}</x-ui.badge>
            </div>
            <label for="consent-analytics" class="flex cursor-pointer items-start justify-between gap-4 rounded border border-line px-4 py-3 has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-tinta">
                <span>
                    <span class="block font-semibold">{{ __('web.cookies_banner.analytics_title') }}</span>
                    <span class="mt-0.5 block text-sm text-muted">{{ $hasAnalytics ? __('web.cookies_banner.analytics_body') : __('web.cookies_banner.analytics_none') }}</span>
                </span>
                <input id="consent-analytics" type="checkbox" x-model="analytics" class="mt-1 size-5 shrink-0 accent-tinta focus-visible:outline-none">
            </label>
        </div>
        <x-slot:footer>
            <x-ui.button variant="secondary" @click="close()">{{ __('ui.cancel') }}</x-ui.button>
            <x-ui.button @click="saveSettings()">{{ __('web.cookies_banner.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.dialog>
</div>
