@php
    $demo = __('web.home.demo');
    // Sample week (labelled as an example on the page). Full-day absences per day, Mon–Sat.
    $rows = [
        ['name' => $demo['people'][0], 'days' => [4 => 'full']],
        ['name' => $demo['people'][1], 'days' => [3 => 'am']],
        ['name' => $demo['people'][2], 'days' => [5 => 'full']],
        ['name' => $demo['people'][3], 'days' => [2 => 'full', 3 => 'full']],
    ];
    $absent = array_map(fn ($i) => collect($rows)->filter(fn ($r) => ($r['days'][$i] ?? null) === 'full')->count(), range(0, 5));
    $size = count($rows) + 1;
    $todayIdx = 4;
@endphp
<x-layouts.public :title="null" :description="__('web.home.meta')" structured landing>

    {{--
        Hero: the question, over a 3D month of a sample team. Each Wala day stands up as an orange
        block with the person's name on it; pressing a day marks you Wala too.
    --}}
    @php
        $board = __('web.home.board');
        [$ana, $ben, $carlo, $dina] = $board['people'];
        // Sample November 2026 (the 1st is a Sunday). Day => who is Wala.
        $team = [3 => [$ana], 6 => [$ben], 10 => [$carlo], 13 => [$ana], 18 => [$dina], 19 => [$dina], 24 => [$ben, $carlo], 25 => [$ben], 26 => [$ben]];
        $half = [6 => 'AM'];
        $boardToday = 13;
        $boardTotal = count($board['people']) + 1;
        $lead = 6;
        $dowOf = fn (int $d) => ($lead + $d - 1) % 7; // Monday = 0
        $dayNames = [];
        for ($d = 1; $d <= 30; $d++) {
            $dayNames[$d] = __('app.days.'.(($dowOf($d) + 1) % 7));
        }
        $mineForms = array_map(fn ($n) => trans_choice('web.home.board.mine_count', $n, ['count' => ':count']), [0, 1, 2]);
    @endphp
    <section id="top" aria-labelledby="hero-title" class="relative -mt-[5.5rem] overflow-hidden bg-abo pt-[5.5rem]">
        <div class="mx-auto max-w-[1240px] safe-x sm:px-6 lg:px-8">
            <div class="grid items-center gap-6 pt-8 lg:grid-cols-12 lg:gap-0 lg:pt-6">
                <div class="relative z-10 lg:col-span-5">
                    <h1 id="hero-title" class="text-3xl leading-[1] font-extrabold tracking-[-0.03em] sm:text-[4rem] lg:text-[5.25rem] lg:leading-[0.95]">{{ __('web.home.hero_title') }}</h1>
                    <p class="mt-8 max-w-[34ch] text-lg leading-8">{{ __('web.home.hero_body') }}</p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <x-ui.button :href="route('register')" size="lg" icon-right="arrow-right" class="rounded-full!">{{ __('web.home.hero_cta') }}</x-ui.button>
                        <x-ui.button :href="'#paano'" variant="secondary" size="lg" class="rounded-full!">{{ __('web.home.hero_secondary') }}</x-ui.button>
                    </div>
                    <p class="mt-4 text-sm text-tinta">{{ __('web.home.hero_note') }}</p>
                </div>

                <figure data-ui aria-labelledby="board-caption" class="board-stage relative min-w-0 lg:col-span-7 lg:-mr-24"
                    x-data="walaBoard({ total: {{ $boardTotal }}, absent: @js(array_map('count', $team)), today: {{ $boardToday }}, labels: { present: @js($board['present']), days: @js($dayNames), mine: @js($mineForms) } })">
                    <div class="-my-8 flex justify-center sm:my-0 sm:py-8 lg:-mt-16 lg:py-0">
                        <div class="board" :class="ready && 'is-ready'" role="group" aria-label="{{ $board['label'] }}">
                            <p class="board-title" aria-hidden="true">{{ __('app.months.11') }}</p>
                            <div class="board-grid">
                                @foreach ([1, 2, 3, 4, 5, 6, 0] as $dow)<span class="board-dow" aria-hidden="true">{{ __('app.days_short.'.$dow) }}</span>@endforeach
                                @for ($i = 0; $i < $lead; $i++)<span class="cube cube-blank" style="--base: 2px" aria-hidden="true"><span class="face face-top"></span><span class="face face-s"></span><span class="face face-e"></span></span>@endfor
                                @for ($d = 1; $d <= 30; $d++)
                                    @php
                                        $who = $team[$d] ?? [];
                                        $names = implode(', ', $who).(isset($half[$d]) ? ' '.$half[$d] : '');
                                        $label = __('web.home.board.day_label', ['day' => $dayNames[$d], 'date' => $d])
                                            .($d === $boardToday ? ' ('.$board['day_today'].')' : '').': '
                                            .($who ? __('web.home.board.day_wala', ['names' => $names]) : $board['day_free']);
                                    @endphp
                                    <button type="button" @click="toggle({{ $d }})" :aria-pressed="isMine({{ $d }}).toString()" aria-pressed="false"
                                        aria-label="{{ $label }}"
                                        :class="isMine({{ $d }}) && 'is-mine'"
                                        @class(['cube', 'cube-wala' => $who, 'cube-today' => $d === $boardToday])
                                        style="--base: {{ $who ? 8 + 20 * count($who) : 8 }}px; --d: {{ ($lead + $d) * 16 }}ms">
                                        <span class="face face-top">
                                            <span class="font-extrabold">{{ $d }}</span>
                                            @if ($who)<span class="hidden font-extrabold sm:block">{{ __('app.wala') }}</span>@endif
                                        </span>
                                        <span class="face face-s"></span><span class="face face-e"></span>
                                        @if ($who)
                                            <span class="chip" aria-hidden="true" x-text="isMine({{ $d }}) ? @js($names.' + '.$board['you']) : @js($names)">{{ $names }}</span>
                                        @else
                                            <span class="chip chip-you" aria-hidden="true" x-show="isMine({{ $d }})" x-cloak>{{ $board['you'] }}</span>
                                        @endif
                                    </button>
                                @endfor
                                @for ($i = 0; $i < 6; $i++)<span class="cube cube-blank" style="--base: 2px" aria-hidden="true"><span class="face face-top"></span><span class="face face-s"></span><span class="face face-e"></span></span>@endfor
                            </div>
                        </div>
                    </div>
                    <figcaption id="board-caption" class="relative z-10 flex flex-col items-start gap-1 text-sm lg:glass lg:absolute lg:-top-8 lg:left-0 lg:max-w-[17.5rem] lg:rounded-2xl lg:px-5 lg:py-4 lg:shadow-overlay">
                        <span class="font-semibold">{{ $board['caption'] }} · {{ $board['hint'] }}</span>
                        <span class="text-base font-extrabold" aria-live="polite" data-num>
                            <span x-text="status()">{{ __('web.home.board.present', ['day' => $dayNames[$boardToday], 'date' => $boardToday, 'present' => $boardTotal - 1, 'total' => $boardTotal]) }}</span>
                            · <span x-text="mineLabel()">{{ trans_choice('web.home.board.mine_count', 0) }}</span>
                        </span>
                    </figcaption>
                </figure>
            </div>

            {{-- Three ways in, frosted over the foot of the scene. --}}
            <ul class="relative z-10 mt-8 grid gap-3 pb-14 sm:grid-cols-3 lg:-mt-10 lg:gap-4 lg:pb-16">
                @foreach (__('web.home.hero_cards') as $card)
                    <li>
                        <a href="{{ $card['href'] }}" class="glass group flex h-full flex-col rounded-2xl p-5 transition-transform duration-200 hover:-translate-y-1 sm:p-6">
                            <span class="inline-flex size-12 items-center justify-center rounded-full bg-papel text-tinta transition-colors group-hover:bg-tinta group-hover:text-white">
                                <x-ui.icon :name="$card['icon']" size="22" />
                            </span>
                            <span class="mt-5 text-xl font-extrabold">{{ $card['title'] }}</span>
                            <span class="mt-2 text-base leading-7">{{ $card['body'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- How it works: one week, printed as an itinerary slip. --}}
    <section id="paano" aria-labelledby="how-title" class="bg-ground">
        <div class="mx-auto grid max-w-[1240px] gap-10 safe-x py-20 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-32">
            <div class="lg:col-span-5">
                <h2 id="how-title" class="text-2xl font-extrabold lg:text-3xl">{{ __('web.home.how_title') }}</h2>
                <p class="mt-4 max-w-[30ch] text-lg leading-8">{{ __('web.home.how_body') }}</p>
            </div>
            <div class="lg:col-span-6 lg:col-start-7">
                <div class="mx-auto max-w-[30rem] bg-papel px-6 pt-6 pb-10 [clip-path:polygon(0_0,100%_0,100%_calc(100%-12px),95%_100%,90%_calc(100%-12px),85%_100%,80%_calc(100%-12px),75%_100%,70%_calc(100%-12px),65%_100%,60%_calc(100%-12px),55%_100%,50%_calc(100%-12px),45%_100%,40%_calc(100%-12px),35%_100%,30%_calc(100%-12px),25%_100%,20%_calc(100%-12px),15%_100%,10%_calc(100%-12px),5%_100%,0_calc(100%-12px))] sm:px-8 lg:mx-0">
                    <p class="text-sm font-semibold text-muted">{{ __('web.home.how_slip_title') }}</p>
                    <ol class="mt-2">
                        @foreach (__('web.home.how') as $group)
                            <li class="mt-7 first:mt-4">
                                <h3 class="text-base font-extrabold">{{ $group['when'] }}</h3>
                                <ul class="mt-2 flex flex-col gap-2.5">
                                    @foreach ($group['rows'] as $row)
                                        <li class="grid grid-cols-[minmax(0,1fr)_auto] items-baseline gap-3 text-base">
                                            <span class="leading-6">{{ $row['what'] }}</span>
                                            <span class="text-sm whitespace-nowrap text-muted" data-num>{{ $row['time'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ol>
                    <p class="on-dark -mx-6 mt-7 flex min-h-11 items-center bg-tinta px-6 py-2.5 font-extrabold text-white sm:-mx-8 sm:px-8">{{ __('web.home.how_result') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- For individuals: the product's own month, beside what it does. --}}
    <section id="sarili" aria-labelledby="personal-title" class="bg-papel">
        <div class="mx-auto grid max-w-[1240px] items-start gap-12 safe-x py-20 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-32">
            <div class="lg:sticky lg:top-24 lg:col-span-6">
                <figure data-ui class="tilt-in rounded-lg border border-line bg-papel p-4 sm:p-5" aria-labelledby="personal-cap">
                    <figcaption id="personal-cap" class="mb-3 flex items-baseline justify-between">
                        <span class="font-extrabold">{{ __('web.home.personal_month') }}</span>
                        <span class="text-sm text-muted">{{ __('web.home.personal_sample') }}</span>
                    </figcaption>
                    @php
                        // Sample November 2026: the 1st is a Sunday (offset 6 in a Monday-first week), 30 days.
                        $wala = [7 => ['full', __('web.home.personal_note')], 14 => ['am', null], 24 => ['full', null], 25 => ['full', null], 26 => ['full', null]];
                    @endphp
                    <div class="grid grid-cols-7 gap-1 text-xs" aria-hidden="true">
                        @foreach ([1, 2, 3, 4, 5, 6, 0] as $dow)<span class="pb-1 font-semibold text-muted">{{ __('app.days_short.'.$dow) }}</span>@endforeach
                        @for ($i = 0; $i < 6; $i++)<span class="tile tile-outside h-12 sm:h-16"></span>@endfor
                        @for ($d = 1; $d <= 30; $d++)
                            @php $w = $wala[$d] ?? null; @endphp
                            <span @class(['tile h-12 sm:h-16', 'tile-wala' => $w])>
                                <span class="font-semibold">{{ $d }}</span>
                                @if ($w)<span class="mt-auto font-extrabold">{{ __('app.wala') }}@if ($w[0] !== 'full') {{ strtoupper($w[0]) }}@endif</span>@endif
                                @if ($w && $w[1])<span class="hidden truncate sm:block">{{ $w[1] }}</span>@endif
                            </span>
                        @endfor
                        @for ($i = 0; $i < 6; $i++)<span class="tile tile-outside h-12 sm:h-16"></span>@endfor
                    </div>
                    <p class="sr-only">{{ __('web.home.personal_sample') }}</p>
                </figure>
            </div>
            <div class="lg:col-span-5 lg:col-start-8">
                <h2 id="personal-title" class="text-2xl font-extrabold lg:text-3xl">{{ __('web.home.personal_title') }}</h2>
                <p class="mt-4 text-lg leading-8">{{ __('web.home.personal_body') }}</p>
                <dl class="mt-10 flex flex-col gap-8">
                    @foreach (__('web.home.personal') as $item)
                        <div>
                            <dt class="text-xl font-extrabold">{{ $item['title'] }}</dt>
                            <dd class="mt-2 text-base leading-7">{{ $item['body'] }}</dd>
                        </div>
                    @endforeach
                </dl>
                <x-ui.button :href="route('register', ['path' => 'personal'])" variant="secondary" class="mt-10">{{ __('web.home.cta_options.personal.label') }}</x-ui.button>
            </div>
        </div>
    </section>

    {{-- For companies: the three screens, chosen from a list, shown on a stage. --}}
    <section id="kumpanya" aria-labelledby="company-title" class="bg-ground" x-data="{ view: 'ngayon' }">
        <div class="mx-auto grid max-w-[1240px] gap-10 safe-x py-20 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-32">
            <div class="lg:col-span-5">
                <h2 id="company-title" class="text-2xl font-extrabold lg:text-3xl">{{ __('web.home.company_title') }}</h2>
                <p class="mt-4 text-lg leading-8">{{ __('web.home.company_body') }}</p>
                <div role="tablist" aria-label="{{ __('web.home.company_views_label') }}" class="mt-8 flex flex-col gap-1"
                    @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()">
                    @foreach (__('web.home.company_views') as $key => $v)
                        <button type="button" role="tab" id="tab-{{ $key }}" aria-controls="panel-{{ $key }}"
                            :aria-selected="(view === '{{ $key }}').toString()" :tabindex="view === '{{ $key }}' ? 0 : -1"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? 0 : -1 }}"
                            @click="view = '{{ $key }}'" @focus="view = '{{ $key }}'"
                            class="group rounded px-3 py-3 text-left transition-colors hover:bg-papel">
                            <span class="block text-xl font-extrabold transition-colors" :class="view === '{{ $key }}' ? 'text-tinta' : 'text-muted'">{{ $v['name'] }}</span>
                            <span class="mt-1 block text-base leading-7" x-show="view === '{{ $key }}'" @if (! $loop->first) x-cloak @endif>{{ $v['body'] }}</span>
                        </button>
                    @endforeach
                </div>
                <ul class="mt-10 flex flex-col gap-3">
                    @foreach (__('web.home.company_points') as $point)
                        <li class="flex gap-3 text-base"><x-ui.icon name="check" size="20" class="mt-0.5" /><span>{{ $point }}</span></li>
                    @endforeach
                </ul>
            </div>

            <div class="tilt-in min-w-0 overflow-hidden rounded-lg bg-abo p-4 sm:p-8 lg:col-span-7 lg:min-h-[34rem]">
                {{-- Ngayon --}}
                <div id="panel-ngayon" role="tabpanel" aria-labelledby="tab-ngayon" x-show="view === 'ngayon'" data-ui class="rounded-lg border border-line bg-papel">
                    <div class="border-b border-line px-5 py-4"><p class="text-lg font-extrabold">{{ __('app.home.title') }}</p><p class="text-sm text-muted">{{ $demo['days'][4] }} · {{ $demo['company'] }}</p></div>
                    <div class="grid sm:grid-cols-2">
                        <div class="p-5">
                            <p class="text-sm font-semibold text-muted">{{ __('app.summary.wala_today') }}</p>
                            <p class="mt-2 flex items-center gap-3"><span class="inline-flex h-7 items-center rounded-sm bg-kahel px-2 text-xs font-extrabold">{{ __('app.wala') }}</span><span class="font-semibold">{{ $demo['people'][0] }}</span></p>
                        </div>
                        <div class="border-t border-line p-5 sm:border-t-0 sm:border-l">
                            <p class="text-sm font-semibold text-muted">{{ __('app.summary.present') }}</p>
                            <p class="mt-1 text-2xl font-extrabold" data-num>{{ __('app.summary.present_of', ['present' => 4, 'total' => 5]) }}</p>
                        </div>
                    </div>
                    <div class="border-t border-line p-5">
                        <p class="text-sm font-semibold text-muted">{{ __('app.summary.next_lakad') }}</p>
                        <p class="mt-2 text-sm"><span class="font-semibold" data-num>{{ __('app.days_short.6') }}</span> · {{ $demo['people'][2] }}</p>
                    </div>
                </div>
                {{-- Team calendar (week) --}}
                <div id="panel-kalendaryo" role="tabpanel" aria-labelledby="tab-kalendaryo" x-show="view === 'kalendaryo'" x-cloak data-ui class="rounded-lg border border-line bg-papel p-3 sm:p-4">
                    <div class="grid grid-cols-[5rem_repeat(6,minmax(0,1fr))] gap-1 text-xs sm:grid-cols-[7rem_repeat(6,minmax(0,1fr))]" aria-hidden="true">
                        <span></span>
                        @foreach ($demo['days_short'] as $d)<span class="font-semibold text-muted">{{ $d }}</span>@endforeach
                        @foreach ($rows as $r)
                            <span class="truncate self-center text-sm font-semibold">{{ \Illuminate\Support\Str::before($r['name'], ' ') }}</span>
                            @foreach (range(0, 5) as $i)
                                @php $part = $r['days'][$i] ?? null; @endphp
                                <span @class(['tile h-10 justify-center', 'tile-wala' => $part])>@if ($part)<span class="font-extrabold">{{ __('app.wala') }}@if ($part !== 'full') {{ strtoupper($part) }}@endif</span>@endif</span>
                            @endforeach
                        @endforeach
                    </div>
                    <p class="sr-only">{{ __('web.home.company_views.kalendaryo.body') }}</p>
                </div>
                {{-- Team page --}}
                <div id="panel-team" role="tabpanel" aria-labelledby="tab-team" x-show="view === 'team'" x-cloak data-ui class="rounded-lg border border-line bg-papel">
                    <div class="border-b border-line px-5 py-4"><p class="text-lg font-extrabold">{{ __('app.team.invite_title') }}</p></div>
                    <div class="flex flex-col gap-4 p-5">
                        <div>
                            <p class="text-sm font-semibold">{{ __('app.team.code') }}</p>
                            <p class="mt-1 w-fit rounded bg-ground px-4 py-2 text-2xl font-extrabold tracking-[0.2em]" data-num>K7Q2MX</p>
                        </div>
                        <ul class="divide-y divide-line border-t border-line">
                            @foreach (array_merge(['Maria Villanueva'], $demo['people']) as $i => $p)
                                <li class="flex items-center gap-3 py-2.5">
                                    <x-ui.avatar :name="$p" size="sm" />
                                    <span class="flex-1 text-sm font-semibold">{{ $p }}</span>
                                    <x-ui.badge :tone="$i === 0 ? 'dark' : 'neutral'">{{ $i === 0 ? __('app.team.role.admin') : __('app.team.role.member') }}</x-ui.badge>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Privacy promise: the one full-width dark field. --}}
    <section id="privacy" aria-labelledby="privacy-title" class="on-dark bg-tinta text-white">
        <div class="mx-auto max-w-[1240px] safe-x py-20 sm:px-6 lg:px-8 lg:py-32">
            <h2 id="privacy-title" class="max-w-[24ch] text-2xl font-extrabold lg:text-3xl">{{ __('web.home.privacy_title') }}</h2>
            <div class="mt-10 grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <p class="text-lg leading-8 text-white/85">{{ __('web.home.privacy_body') }}</p>
                    <a href="{{ route('privacy') }}" class="mt-6 inline-flex min-h-11 items-center gap-2 font-semibold text-white underline underline-offset-4">{{ __('web.home.privacy_more') }}<x-ui.icon name="arrow-right" size="18" /></a>
                </div>
                <div class="min-w-0 lg:col-span-7">
                    {{-- Phones: one block per row instead of a table that scrolls sideways. --}}
                    <ul class="flex flex-col md:hidden" aria-label="{{ __('web.home.privacy_table_caption') }}">
                        @foreach (__('web.home.privacy_rows') as $row)
                            <li class="border-b border-white/15 py-4">
                                <p class="font-extrabold">{{ $row[0] }}</p>
                                <dl class="mt-2 grid grid-cols-[auto_1fr] gap-x-4 gap-y-1 text-sm">
                                    @foreach (array_slice(__('web.home.privacy_cols'), 1) as $i => $col)
                                        <dt class="text-white/70">{{ $col }}</dt><dd>{{ $row[$i + 1] }}</dd>
                                    @endforeach
                                </dl>
                            </li>
                        @endforeach
                    </ul>
                    <div class="hidden overflow-x-auto md:block">
                        <table class="w-full border-collapse text-left text-base">
                            <caption class="sr-only">{{ __('web.home.privacy_table_caption') }}</caption>
                            <thead>
                                <tr>@foreach (__('web.home.privacy_cols') as $col)<th scope="col" class="border-b border-white/30 pr-4 pb-3 text-sm font-semibold text-white/75">{{ $col }}</th>@endforeach</tr>
                            </thead>
                            <tbody>
                                @foreach (__('web.home.privacy_rows') as $row)
                                    <tr>
                                        <th scope="row" class="border-b border-white/15 py-4 pr-4 align-top font-extrabold">{{ $row[0] }}</th>
                                        @foreach (array_slice($row, 1) as $cell)
                                            <td class="border-b border-white/15 py-4 pr-4 align-top {{ str_starts_with($cell, 'Hindi') ? 'text-white/70' : '' }}">{{ $cell }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ as reading text --}}
    <section id="faq" aria-labelledby="faq-title" class="bg-papel">
        <div class="mx-auto grid max-w-[1240px] gap-8 safe-x py-20 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-32">
            <div class="lg:col-span-3">
                <h2 id="faq-title" class="text-2xl font-extrabold">{{ __('web.home.faq_title') }}</h2>
                <a href="{{ route('faq') }}" class="link mt-4 inline-flex min-h-11 items-center font-semibold">{{ __('web.home.faq_more') }}</a>
            </div>
            <div class="gap-14 md:columns-2 lg:col-span-8 lg:col-start-5">
                @foreach (array_slice(__('web.faq.items'), 0, 8) as $item)
                    <p class="mb-6 break-inside-avoid text-base leading-7"><strong class="font-extrabold">{{ $item['q'] }}</strong> {{ $item['a'] }}</p>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Final call to action: the sign-up's first question, already open. --}}
    <section aria-labelledby="cta-title" class="bg-abo">
        <div class="mx-auto grid max-w-[1240px] items-end gap-10 safe-x py-20 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-28">
            <div class="lg:col-span-5">
                <h2 id="cta-title" class="text-3xl leading-[1.02] font-extrabold lg:text-[4rem]">{{ __('web.home.cta_title') }}</h2>
                <p class="mt-6 max-w-[40ch] text-lg leading-8">{{ __('web.home.cta_body') }}</p>
                <div class="mt-10 flex max-w-md items-start gap-4 border-t border-line-strong pt-6" x-show="! $store.pwa.installed" x-cloak>
                    <span class="inline-flex size-12 shrink-0 items-center justify-center rounded-2xl bg-tinta text-white"><x-ui.icon name="smartphone" size="22" /></span>
                    <div>
                        <h3 class="text-lg font-extrabold">{{ __('web.home.install_title') }}</h3>
                        <p class="mt-1 text-base leading-7">{{ __('web.home.install_body') }} {{ __('ui.install.works_on') }}</p>
                        <x-install-button class="mt-4 rounded-full!" />
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('register') }}" data-ui class="w-full max-w-md rounded-lg bg-papel p-5 shadow-overlay sm:p-6 lg:col-span-5 lg:col-start-8 lg:justify-self-end">
                <fieldset class="flex flex-col gap-2">
                    <legend class="mb-3 text-lg font-extrabold">{{ __('web.home.cta_card_title') }}</legend>
                    @foreach (__('web.home.cta_options') as $value => $opt)
                        <x-ui.radio name="path" :value="$value" :id="'cta-'.$value" :label="$opt['label']" :description="$opt['body']" :checked="$loop->first" />
                    @endforeach
                </fieldset>
                <x-ui.button type="submit" block size="lg" class="mt-5" icon-right="arrow-right">{{ __('web.home.cta_button') }}</x-ui.button>
            </form>
        </div>
    </section>
</x-layouts.public>
