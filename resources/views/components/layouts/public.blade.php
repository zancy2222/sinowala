@props(['title' => null, 'description' => null, 'structured' => false, 'noindex' => false, 'ground' => 'bg-papel', 'landing' => false])
<!DOCTYPE html>
<html lang="fil">
<head>
    <x-head :title="$title" :description="$description" :structured="$structured" :noindex="$noindex" />
</head>
<body x-data class="{{ $ground }} min-h-dvh">
    <a href="#main" class="sr-only z-50 rounded bg-tinta px-4 py-3 font-semibold text-white focus:not-sr-only focus:fixed focus:top-3 focus:left-3">{{ __('ui.skip') }}</a>
    @include('partials.cookie-consent')

    {{-- A floating bar: the wordmark, the links and the action sit on one frosted pill. --}}
    <header class="sticky top-0 z-30 safe-x py-3 sm:px-6 lg:px-8" x-data="{ menu: false }" @keydown.escape.window="if (menu) { menu = false; $refs.menuButton.focus() }" @click.outside="menu = false">
        <div class="glass mx-auto flex h-16 max-w-[1240px] items-center gap-6 rounded-full pr-2 pl-5 shadow-overlay sm:pl-6">
            <a href="{{ route('home') }}" class="-m-2 shrink-0 rounded p-2" aria-label="{{ __('web.nav.home_label') }}">
                <x-ui.logo :height="28" class="hidden sm:block" alt="" />
                <x-ui.logo variant="mark" :height="28" class="sm:hidden" alt="" />
            </a>

            <nav aria-label="{{ __('web.nav.label') }}" class="hidden flex-1 lg:block">
                <ul class="flex items-center gap-1">
                    @foreach (__('web.nav.items') as $item)
                        @php $href = str_starts_with($item['href'], '#') ? route('home').$item['href'] : route($item['href']); @endphp
                        <li><a href="{{ $href }}" class="inline-flex min-h-11 items-center rounded-full px-4 text-sm font-semibold text-tinta transition-colors hover:bg-papel" @if (! str_starts_with($item['href'], '#') && request()->routeIs($item['href'])) aria-current="page" @endif>{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <div class="ml-auto flex items-center gap-2">
                @auth
                    <x-ui.button :href="route('app.home')" size="sm" icon-right="arrow-right" class="rounded-full!">{{ __('web.nav.open_app') }}</x-ui.button>
                @else
                    <a href="{{ route('login') }}" class="hidden min-h-11 items-center gap-2 rounded-full bg-papel px-4 text-sm font-semibold text-tinta transition-colors hover:bg-hover min-[400px]:inline-flex"><x-ui.icon name="user" size="16" />{{ __('web.nav.login') }}</a>
                    <x-ui.button :href="route('register')" size="sm" class="rounded-full!">{{ __('web.nav.signup') }}</x-ui.button>
                @endauth
                <button x-ref="menuButton" type="button" class="inline-flex size-11 items-center justify-center rounded-full text-tinta transition-colors hover:bg-papel lg:hidden"
                    @click="menu = !menu" :aria-expanded="menu.toString()" aria-expanded="false" aria-controls="mobile-nav">
                    <x-ui.icon name="menu" size="22" x-show="!menu" />
                    <x-ui.icon name="x" size="22" x-show="menu" x-cloak />
                    <span class="sr-only" x-text="menu ? @js(__('app.nav.close_menu')) : @js(__('app.nav.menu'))">{{ __('app.nav.menu') }}</span>
                </button>
            </div>
        </div>

        <nav id="mobile-nav" x-show="menu" x-cloak aria-label="{{ __('web.nav.label') }}" class="glass mx-auto mt-2 max-w-[1240px] rounded-3xl shadow-overlay lg:hidden">
            <ul class="flex flex-col p-2">
                @foreach (__('web.nav.items') as $item)
                    @php $href = str_starts_with($item['href'], '#') ? route('home').$item['href'] : route($item['href']); @endphp
                    <li><a href="{{ $href }}" @click="menu = false" class="flex min-h-12 items-center rounded-2xl px-4 text-base font-semibold text-tinta hover:bg-papel">{{ $item['label'] }}</a></li>
                @endforeach
                <li x-show="! $store.pwa.installed" x-cloak>
                    <button type="button" @click="menu = false; $store.pwa.install()" aria-haspopup="dialog" class="flex min-h-12 w-full items-center gap-3 rounded-2xl px-4 text-base font-semibold text-tinta hover:bg-papel">
                        <x-ui.icon name="smartphone" size="20" />{{ __('ui.install.button') }}
                    </button>
                </li>
                @guest
                    <li><a href="{{ route('login') }}" class="flex min-h-12 items-center rounded-2xl px-4 text-base font-semibold text-tinta hover:bg-papel">{{ __('web.nav.login') }}</a></li>
                @endguest
            </ul>
        </nav>
    </header>

    <main id="main" tabindex="-1" class="focus:outline-none">
        {{ $slot }}
    </main>


    @if ($landing)
        {{-- Section index, wide screens only: follows the scroll, like a map legend. --}}
        <nav x-data="pageNav" aria-label="{{ __('web.home.page_nav.label') }}" class="glass fixed top-1/2 right-4 z-20 hidden -translate-y-1/2 rounded-3xl px-2 py-3 shadow-overlay min-[1700px]:block">
            <ul class="flex flex-col">
                @foreach (__('web.home.page_nav.items') as $item)
                    <li>
                        <a href="{{ $item['href'] }}" class="flex min-h-10 items-center gap-2.5 rounded-full px-3 text-sm font-semibold text-tinta transition-colors hover:bg-papel"
                            :aria-current="active === @js($item['href']) ? 'true' : null">
                            <span class="size-2 rounded-full transition-colors" :class="active === @js($item['href']) ? 'bg-kahel' : 'bg-line-strong'"></span>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        @guest
            {{-- Floating sign-up bar: shows once the hero is passed; the X hides it on this device. --}}
            <aside x-data="promoBar('sw_promo_closed')" x-show="shown" x-cloak aria-label="{{ __('web.home.promo.label') }}"
                x-transition:enter="transition duration-300" x-transition:enter-start="translate-y-6 opacity-0" x-transition:leave="transition duration-200" x-transition:leave-end="translate-y-6 opacity-0"
                class="fixed inset-x-3 bottom-3 z-30 mx-auto max-w-2xl sm:bottom-5">
                <div class="glass flex items-center gap-3 rounded-2xl py-2.5 pr-2 pl-4 shadow-overlay sm:gap-4 sm:pl-5">
                    <x-ui.icon name="calendar-plus" size="22" class="hidden sm:block" />
                    <p class="min-w-0 flex-1 text-sm leading-5 sm:text-base"><strong class="font-extrabold">{{ __('web.home.promo.title') }}</strong> <span class="hidden sm:inline">{{ __('web.home.promo.body') }}</span></p>
                    <x-ui.button :href="route('register')" size="sm" class="shrink-0 rounded-full!">{{ __('web.home.promo.cta') }}</x-ui.button>
                    <button type="button" @click="dismiss()" class="inline-flex size-11 shrink-0 items-center justify-center rounded-full text-tinta transition-colors hover:bg-papel">
                        <x-ui.icon name="x" size="18" /><span class="sr-only">{{ __('web.home.promo.close') }}</span>
                    </button>
                </div>
            </aside>
        @endguest
    @endif

    @include('partials.public-footer')
    @include('partials.install-dialog')
    <x-ui.toasts :initial="[session('toast')]" />
    @livewireScripts
</body>
</html>
