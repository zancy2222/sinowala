@props(['title' => null, 'description' => null, 'noindex' => false, 'structured' => false])
@php
    $fullTitle = $title ? $title.' · SinoWala' : 'SinoWala — '.__('web.tagline');
    $desc = $description ?? __('web.meta_default');
    $canonical = url()->current();
    $ogImage = asset('og-image.png');
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ $desc }}">
@if ($noindex)
    <meta name="robots" content="noindex, nofollow">
@else
    <link rel="canonical" href="{{ $canonical }}">
@endif

<meta property="og:site_name" content="SinoWala">
<meta property="og:type" content="website">
<meta property="og:locale" content="fil_PH">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="SinoWala">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ $desc }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<link rel="icon" href="/favicon.ico" sizes="48x48">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#12161F">
{{-- Installed as an app: iPhone and iPad read these tags rather than the manifest. --}}
<meta name="application-name" content="SinoWala">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="SinoWala">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
@include('partials.ios-splash')
<link rel="preload" href="/fonts/bricolage-grotesque-latin.woff2" as="font" type="font/woff2" crossorigin>

@if ($structured)
    <script type="application/ld+json" nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => url('/').'#organization',
                    'name' => 'SinoWala',
                    'url' => url('/'),
                    'logo' => asset('icon-512.png'),
                    'email' => config('sinowala.business.email'),
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/').'#website',
                    'name' => 'SinoWala',
                    'url' => url('/'),
                    'inLanguage' => 'fil-PH',
                    'publisher' => ['@id' => url('/').'#organization'],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
@endif

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
