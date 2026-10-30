@php use App\Support\Fmt; $order = [1, 2, 3, 4, 5, 6, 0]; @endphp
<!DOCTYPE html>
<html lang="fil">
<head>
    <x-head :title="__('web.share.title', ['name' => $firstName])" :description="__('web.share.meta')" noindex />
</head>
<body x-data class="min-h-dvh bg-ground">
    <a href="#main" class="sr-only z-50 rounded bg-tinta px-4 py-3 font-semibold text-white focus:not-sr-only focus:fixed focus:top-3 focus:left-3">{{ __('ui.skip') }}</a>
    <header class="safe-x mx-auto flex h-16 max-w-5xl items-center sm:px-6">
        <a href="{{ route('home') }}" class="-m-2 rounded p-2"><x-ui.logo :height="28" /></a>
    </header>
    <main id="main" tabindex="-1" class="safe-x mx-auto max-w-5xl pt-4 pb-16 focus:outline-none sm:px-6">
        <h1 class="text-2xl font-extrabold sm:text-3xl">{{ __('web.share.title', ['name' => $firstName]) }}</h1>
        <p class="mt-2 text-base text-muted">{{ __('web.share.subtitle', ['range' => Fmt::range($from, $to)]) }}</p>

        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm text-muted">
            <span class="inline-flex items-center gap-2"><span class="size-4 rounded-sm bg-abo" aria-hidden="true"></span>{{ __('app.available') }}</span>
            <span class="inline-flex items-center gap-2"><span class="size-4 rounded-sm bg-kahel" aria-hidden="true"></span>{{ __('app.wala') }}</span>
        </div>

        <div class="mt-8 grid gap-8 md:grid-cols-2">
            @foreach ($months as $month)
                <section aria-labelledby="m-{{ $month['cursor']->format('Ym') }}">
                    <h2 id="m-{{ $month['cursor']->format('Ym') }}" class="mb-2 text-lg font-extrabold">{{ Fmt::monthYear($month['cursor']) }}</h2>
                    <table class="w-full table-fixed border-separate border-spacing-1">
                        <thead>
                            <tr>@foreach ($order as $dow)<th scope="col" abbr="{{ __('app.days.'.$dow) }}" class="text-left text-xs font-semibold text-muted">{{ __('app.days_short.'.$dow) }}</th>@endforeach</tr>
                        </thead>
                        <tbody>
                            @foreach ($month['weeks'] as $week)
                                <tr>
                                    @foreach ($week as $day)
                                        @php
                                            $inRange = $day->month === $month['cursor']->month && $day->between($from, $to);
                                            $entry = $inRange ? $absences->get($day->toDateString()) : null;
                                        @endphp
                                        @if ($day->month !== $month['cursor']->month)
                                            <td aria-hidden="true"></td>
                                        @else
                                            <td @class(['tile h-12 align-top', 'tile-wala' => $entry, 'tile-outside' => ! $inRange])>
                                                <span class="text-sm font-semibold">{{ $day->day }}</span>
                                                @if ($entry)
                                                    <span class="text-xs font-extrabold">{{ __('app.wala') }}@if ($entry['part'] !== 'full') {{ __('app.part_short.'.$entry['part']) }}@endif</span>
                                                @elseif ($inRange)
                                                    <span class="sr-only">{{ __('app.available') }}</span>
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            @endforeach
        </div>

        <p class="mt-10 max-w-xl text-sm text-muted">{{ __('web.share.footnote') }} <a href="{{ route('home') }}" class="link">{{ __('web.share.what') }}</a></p>
    </main>
    @livewireScripts
</body>
</html>
