{{-- Sign-in, sign-up and account-recovery pages: one narrow column on the page ground. --}}
@props(['title', 'description' => null, 'wide' => false])
<!DOCTYPE html>
<html lang="fil">
<head>
    <x-head :title="$title" :description="$description" />
</head>
<body x-data class="min-h-dvh bg-ground">
    <a href="#main" class="sr-only z-50 rounded bg-tinta px-4 py-3 font-semibold text-white focus:not-sr-only focus:fixed focus:top-3 focus:left-3">{{ __('ui.skip') }}</a>
    @include('partials.cookie-consent')
    <header class="safe-x mx-auto flex h-16 max-w-[1240px] items-center sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="-m-2 rounded p-2"><x-ui.logo :height="28" /></a>
    </header>
    <main id="main" tabindex="-1" class="px-4 pt-6 pb-16 focus:outline-none sm:pt-12">
        <div class="mx-auto w-full {{ $wide ? 'max-w-xl' : 'max-w-md' }}">
            {{ $slot }}
        </div>
    </main>
    <footer class="px-4 pt-2 pb-6 text-sm text-muted">
        <nav aria-label="{{ __('web.footer.legal') }}" class="mx-auto flex max-w-xl flex-wrap justify-center gap-x-4 gap-y-1">
            @foreach (['privacy', 'terms', 'cookies', 'accessibility', 'contact'] as $name)
                <a href="{{ route($name) }}" class="inline-flex min-h-11 items-center text-tinta underline-offset-4 hover:underline">{{ __('web.footer.links.'.$name) }}</a>
            @endforeach
            <button type="button" @click="$dispatch('open-cookie-settings')" class="inline-flex min-h-11 items-center text-tinta underline-offset-4 hover:underline">{{ __('web.footer.links.cookie_settings') }}</button>
        </nav>
        <p class="mt-2 text-center">{{ __('app.footer.rights', ['year' => now()->year]) }}</p>
    </footer>
    <x-ui.toasts :initial="[session('toast')]" />
    @livewireScripts
</body>
</html>
