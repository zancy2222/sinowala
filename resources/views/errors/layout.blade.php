{{-- Self-contained: no database, session or auth calls, so it renders even when those are down. --}}
@php $e = __('errors.'.$code); @endphp
<!DOCTYPE html>
<html lang="fil">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex">
    <title>{{ $e['title'] }} · SinoWala</title>
    <link rel="icon" href="/favicon.ico" sizes="48x48">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <meta name="theme-color" content="#12161F">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @endif
</head>
<body class="min-h-dvh bg-ground font-medium text-tinta">
    <header class="safe-x mx-auto flex h-16 max-w-[1240px] items-center sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="-m-2 rounded p-2"><img src="{{ asset('brand/sinowala-logo-horizontal.svg') }}" alt="SinoWala" height="28" class="h-7 w-auto"></a>
    </header>
    <main class="safe-x mx-auto max-w-[1240px] pt-12 pb-24 sm:px-6 lg:px-8 lg:pt-24">
        <div class="grid items-center gap-12 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <h1 class="text-2xl font-extrabold sm:text-3xl">{{ $e['title'] }}</h1>
                <p class="measure mt-4 text-lg leading-8">{{ $e['body'] }}</p>
                <p class="mt-3 text-sm text-muted">{{ __('errors.code', ['code' => $code]) }}</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ url('/') }}" class="inline-flex min-h-12 items-center justify-center rounded bg-tinta px-5 font-semibold text-white transition-colors hover:bg-tinta-2">{{ __('ui.back_home') }}</a>
                    @isset($e['action'])
                        <a href="{{ $e['action_href'] ?? url()->previous() }}" class="inline-flex min-h-12 items-center justify-center rounded border border-line-strong bg-papel px-5 font-semibold transition-colors hover:bg-hover">{{ $e['action'] }}</a>
                    @endisset
                </div>
            </div>
            {{-- The logo's idea, as data: one tile stepped out of the week. --}}
            <div class="hidden lg:col-span-5 lg:col-start-8 lg:block" aria-hidden="true">
                <div class="grid grid-cols-7 gap-2">
                    @for ($i = 0; $i < 21; $i++)
                        <span class="aspect-square rounded {{ $i === 10 ? 'bg-kahel' : 'bg-abo' }}"></span>
                    @endfor
                </div>
            </div>
        </div>
    </main>
</body>
</html>
