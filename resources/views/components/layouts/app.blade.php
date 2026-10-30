{{--
    App shell ("open plane"): a labelled sidebar on the page ground at ≥1024px; a 56px top bar with a
    menu sheet below that. One canvas with a 16 / 24 / 32px gutter. Admin pages pass :admin="true".
--}}
@props(['title', 'description' => null, 'admin' => false])
@php
    $user = auth()->user();
    $company = $user->company();
    $nav = $admin
        ? [
            ['route' => 'admin.dashboard', 'label' => __('admin.nav.dashboard'), 'icon' => 'house'],
            ['route' => 'admin.companies', 'label' => __('admin.nav.companies'), 'icon' => 'building-2'],
            ['route' => 'admin.users', 'label' => __('admin.nav.users'), 'icon' => 'users'],
            ['route' => 'admin.messages', 'label' => __('admin.nav.messages'), 'icon' => 'inbox'],
            ['route' => 'admin.privacy-requests', 'label' => __('admin.nav.privacy'), 'icon' => 'shield'],
            ['route' => 'admin.audit', 'label' => __('admin.nav.audit'), 'icon' => 'scroll-text'],
        ]
        : array_values(array_filter([
            ['route' => 'app.home', 'label' => __('app.nav.home'), 'icon' => 'house'],
            ['route' => 'app.calendar', 'label' => __('app.nav.calendar'), 'icon' => 'calendar'],
            $company ? ['route' => 'app.team-calendar', 'label' => __('app.nav.team_calendar'), 'icon' => 'calendar-range'] : null,
            $company && $user->isCompanyAdmin() ? ['route' => 'app.team', 'label' => __('app.nav.team'), 'icon' => 'users'] : null,
            ['route' => 'settings', 'label' => __('app.nav.settings'), 'icon' => 'settings'],
        ]));
    $scope = $admin ? __('admin.scope') : ($company ? $company->name : __('app.nav.personal'));
@endphp
<!DOCTYPE html>
<html lang="fil">
<head>
    <x-head :title="$title" :description="$description" noindex />
</head>
<body x-data class="min-h-dvh bg-ground" data-shortcuts="{{ $user->shortcuts_enabled ? 'on' : 'off' }}">
    <a href="#main" class="sr-only z-50 rounded bg-tinta px-4 py-3 font-semibold text-white focus:not-sr-only focus:fixed focus:top-3 focus:left-3">{{ __('ui.skip') }}</a>
    @include('partials.cookie-consent')

    <div class="lg:grid lg:min-h-dvh lg:grid-cols-[248px_minmax(0,1fr)]">
        {{-- Sidebar (desktop) --}}
        <aside class="hidden lg:sticky lg:top-0 lg:flex lg:h-dvh lg:flex-col lg:border-r lg:border-line lg:px-3 lg:py-5" aria-label="{{ __('app.nav.main') }}">
            @include('partials.app-nav', ['nav' => $nav, 'scope' => $scope, 'admin' => $admin, 'user' => $user, 'company' => $company, 'idPrefix' => 'side'])
        </aside>

        <div class="flex min-h-dvh min-w-0 flex-col">
            {{-- Top bar (phone + tablet) --}}
            <header class="sticky top-0 z-30 flex h-14 items-center gap-3 border-b border-line bg-ground/95 safe-x sm:px-6 lg:hidden">
                <a href="{{ $admin ? route('admin.dashboard') : route('app.home') }}" class="-m-2 rounded p-2">
                    <x-ui.logo variant="mark" :height="24" class="sm:hidden" />
                    <x-ui.logo :height="24" class="hidden sm:block" />
                </a>
                <span class="min-w-0 truncate text-sm font-semibold text-muted">{{ $scope }}</span>
                <button type="button" class="ml-auto inline-flex min-h-11 items-center gap-2 rounded px-3 text-sm font-semibold text-tinta transition-colors hover:bg-press"
                    @click="$dispatch('open-dialog', { name: 'app-menu' })" aria-haspopup="dialog">
                    <x-ui.icon name="menu" size="20" />
                    <span>{{ __('app.nav.menu') }}</span>
                </button>
            </header>

            <main id="main" tabindex="-1" class="flex-1 px-4 pt-6 pb-12 focus:outline-none sm:px-6 sm:pt-8 lg:px-8 lg:pt-9">
                <div class="mx-auto w-full max-w-[1200px]">
                    {{ $slot }}
                </div>
            </main>

            <footer class="border-t border-line px-4 py-5 text-sm text-muted sm:px-6 lg:px-8">
                <div class="mx-auto flex max-w-[1200px] flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <p>{{ __('app.footer.rights', ['year' => now()->year]) }}</p>
                    <nav aria-label="{{ __('web.footer.legal') }}">
                        <ul class="flex flex-wrap gap-x-4 gap-y-1">
                            @foreach (['privacy', 'terms', 'cookies', 'privacy-request', 'accessibility'] as $name)
                                <li><a href="{{ route($name) }}" class="inline-flex min-h-11 items-center text-tinta underline-offset-4 hover:underline md:min-h-0">{{ __('web.footer.links.'.$name) }}</a></li>
                            @endforeach
                            <li><button type="button" @click="$dispatch('open-cookie-settings')" class="inline-flex min-h-11 items-center text-tinta underline-offset-4 hover:underline md:min-h-0">{{ __('web.footer.links.cookie_settings') }}</button></li>
                        </ul>
                    </nav>
                </div>
            </footer>
        </div>
    </div>

    {{-- Menu sheet (phone + tablet) --}}
    <x-ui.dialog name="app-menu" :title="__('app.nav.menu')" sheet size="sm" class="lg:hidden">
        <div class="flex flex-col">
            @include('partials.app-nav', ['nav' => $nav, 'scope' => $scope, 'admin' => $admin, 'user' => $user, 'company' => $company, 'idPrefix' => 'sheet'])
        </div>
    </x-ui.dialog>

    @include('partials.install-dialog')

    @unless ($admin)
        <livewire:absence-form />
    @endunless

    <x-ui.dialog name="shortcuts" :title="__('app.shortcuts.title')" :description="__('app.shortcuts.description')">
        @unless ($user->shortcuts_enabled)
            <x-ui.alert class="mb-4">{{ __('app.shortcuts.off_note') }} <a href="{{ route('settings') }}#shortcuts" class="link">{{ __('app.shortcuts.settings_link') }}</a></x-ui.alert>
        @endunless
        <dl class="divide-y divide-line">
            @foreach (__('app.shortcuts.list') as $key => $label)
                <div class="flex items-center justify-between gap-4 py-2.5"><dt class="text-sm">{{ $label }}</dt><dd><x-ui.kbd>{{ $key }}</x-ui.kbd></dd></div>
            @endforeach
        </dl>
        <h3 class="mt-6 mb-1 text-base font-extrabold">{{ __('app.shortcuts.grid_title') }}</h3>
        <dl class="divide-y divide-line">
            @foreach (__('app.shortcuts.grid') as $key => $label)
                <div class="flex items-center justify-between gap-4 py-2.5"><dt class="text-sm">{{ $label }}</dt><dd><x-ui.kbd>{{ $key }}</x-ui.kbd></dd></div>
            @endforeach
        </dl>
    </x-ui.dialog>

    {{-- Fortify statuses (profile-information-updated, password-updated, …) map to friendly toasts. --}}
    <x-ui.toasts :initial="[session('toast'), session('status') ? __('auth.status.'.session('status')) : null]" />
    @livewireScripts
</body>
</html>
