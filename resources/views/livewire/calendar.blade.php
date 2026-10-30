@php
    use App\Support\Fmt;
    $id = $this->getId();
    $isTeam = $scope === 'team';
    $order = [1, 2, 3, 4, 5, 6, 0]; // week starts Monday
    $partLabel = fn ($part) => $part === 'full' ? '' : ' ('.__('app.part_short.'.$part).')';
    $teamCellLabel = function ($day) use ($team, $today, $partLabel) {
        $rows = $team->get($day->toDateString(), collect());
        $names = $rows->map(fn ($r) => $r['name'].$partLabel($r['part']))->implode(', ');
        return trans_choice('app.calendar.cell_team', $rows->count(), ['date' => Fmt::long($day), 'count' => $rows->count(), 'names' => $names])
            .($day->isSameDay($today) ? __('app.calendar.today_suffix') : '');
    };
    $meCellLabel = function ($day) use ($mine, $today, $partLabel) {
        $entry = $mine->get($day->toDateString());
        $label = $entry
            ? __('app.calendar.cell_me_wala', ['date' => Fmt::long($day), 'part' => $partLabel($entry['part'])])
            : __('app.calendar.cell_me_free', ['date' => Fmt::long($day)]);
        return $label.($day->isSameDay($today) ? __('app.calendar.today_suffix') : '');
    };
    $focusDate = $cursorDate->between($from, $to) ? $cursorDate->toDateString() : $from->toDateString();
    if ($today->between($from, $to) && ! $this->cursor) {
        $focusDate = $today->toDateString();
    }
@endphp
<div x-data
    x-init="if ($wire.view === null) { $wire.setView(window.matchMedia('(min-width: 1024px)').matches ? 'month' : (window.matchMedia('(min-width: 640px)').matches ? 'week' : 'agenda')) }"
    class="flex flex-col gap-4">

    {{-- Toolbar: period, navigation, view, search --}}
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-2">
            <h2 id="cal-title-{{ $id }}" class="min-w-0 text-xl font-extrabold" aria-live="polite">{{ $title }}</h2>
            <span wire:loading.delay class="text-sm text-muted">{{ __('ui.loading') }}</span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center rounded border border-line bg-papel p-1" role="group" aria-label="{{ __('app.calendar.period_nav') }}">
                <button type="button" wire:click="prev" class="inline-flex size-10 items-center justify-center rounded-sm text-tinta transition-colors hover:bg-hover"
                    title="{{ __('app.calendar.prev') }} (←)" aria-keyshortcuts="ArrowLeft">
                    <x-ui.icon name="chevron-left" size="20" /><span class="sr-only">{{ __('app.calendar.prev') }}</span>
                </button>
                <button type="button" wire:click="today" class="inline-flex min-h-10 items-center rounded-sm px-3 text-sm font-semibold text-tinta transition-colors hover:bg-hover"
                    title="{{ __('app.calendar.today') }} (T)" aria-keyshortcuts="T">{{ __('app.calendar.today') }}</button>
                <button type="button" wire:click="next" class="inline-flex size-10 items-center justify-center rounded-sm text-tinta transition-colors hover:bg-hover"
                    title="{{ __('app.calendar.next') }} (→)" aria-keyshortcuts="ArrowRight">
                    <x-ui.icon name="chevron-right" size="20" /><span class="sr-only">{{ __('app.calendar.next') }}</span>
                </button>
            </div>
            <x-ui.segmented :options="__('app.calendar.views')" :current="$ready ? $resolvedView : null" action="setView" :label="__('app.calendar.view_label')"
                :shortcuts="['month' => 'M', 'week' => 'W']" />
        </div>
    </div>

    @if ($isTeam)
        <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
            <label for="people-search-{{ $id }}" class="text-sm font-semibold">{{ __('app.calendar.search') }}</label>
            <div class="relative w-full sm:max-w-xs">
                <x-ui.icon name="search" size="18" class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-muted" />
                <input id="people-search-{{ $id }}" type="search" wire:model.live.debounce.250ms="search" data-shortcut-search
                    placeholder="{{ __('app.calendar.search_placeholder') }}" aria-keyshortcuts="/" autocomplete="off"
                    class="block min-h-11 w-full rounded border border-line-strong bg-papel py-2 pr-3 pl-10 text-base transition-colors hover:border-tinta">
            </div>
            @if (trim($search) !== '' && $members->isEmpty())
                <p class="text-sm text-muted" role="status">{{ __('app.calendar.no_match', ['term' => $search]) }}</p>
            @endif
        </div>
    @endif

    @if (! $ready)
        {{-- First paint while the browser picks the view for its width. --}}
        <div class="grid grid-cols-7 gap-1" aria-hidden="true">
            @for ($i = 0; $i < 35; $i++)<div class="skeleton h-16 sm:h-24"></div>@endfor
        </div>
    @elseif ($resolvedView === 'month')
        <div x-data="calendarGrid()" @keydown="key($event)" class="overflow-x-auto">
            <table role="grid" aria-labelledby="cal-title-{{ $id }}" class="w-full min-w-[18rem] table-fixed border-separate border-spacing-1">
                <thead>
                    <tr role="row">
                        @foreach ($order as $dow)
                            <th role="columnheader" scope="col" abbr="{{ __('app.days.'.$dow) }}" class="pb-1 text-left text-xs font-semibold text-muted">
                                {{ __('app.days_short.'.$dow) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weeks as $week)
                        <tr role="row">
                            @foreach ($week as $day)
                                @php
                                    $key = $day->toDateString();
                                    $inMonth = $day->month === $cursorDate->month;
                                    $isToday = $day->isSameDay($today);
                                    $rows = $isTeam ? $team->get($key, collect()) : collect();
                                    $entry = $isTeam ? null : $mine->get($key);
                                    $wala = $isTeam ? $rows->isNotEmpty() : (bool) $entry;
                                @endphp
                                <td role="gridcell" wire:key="m-{{ $key }}" data-date="{{ $key }}" tabindex="{{ $key === $focusDate ? 0 : -1 }}"
                                    aria-label="{{ $isTeam ? $teamCellLabel($day) : $meCellLabel($day) }}"
                                    @if ($isToday) aria-current="date" @endif
                                    wire:click="showDay('{{ $key }}')"
                                    @class([
                                        'tile h-16 cursor-pointer align-top sm:h-24 lg:h-28',
                                        'tile-wala hover:bg-kahel-2' => ! $isTeam && $wala,
                                        'hover:bg-press' => $isTeam || ! $wala,
                                        'bg-papel! border border-line' => $isTeam && $inMonth,
                                        'tile-outside' => ! $inMonth && ! $wala,
                                        'tile-today' => $isToday,
                                    ])>
                                    <span class="flex items-center justify-between gap-1">
                                        <span @class(['text-sm font-semibold', 'text-muted' => ! $inMonth && ! ($wala && ! $isTeam)]) aria-hidden="true">{{ $day->day }}</span>
                                        @if ($isToday)<span class="hidden text-xs font-semibold lg:inline" aria-hidden="true">{{ __('app.today') }}</span>@endif
                                    </span>
                                    @if ($isTeam && $rows->isNotEmpty())
                                        <span class="mt-1 inline-flex w-fit items-center gap-1 rounded-sm bg-kahel px-1.5 text-xs font-semibold text-tinta" aria-hidden="true">
                                            {{ __('app.wala') }}<span class="hidden sm:inline">&nbsp;{{ $rows->count() }}</span>
                                        </span>
                                        <ul class="mt-1 hidden min-w-0 flex-col text-xs leading-4 sm:flex" aria-hidden="true">
                                            @foreach ($rows->take(2) as $r)
                                                <li class="truncate">{{ \Illuminate\Support\Str::before($r['name'], ' ') ?: $r['name'] }}@if ($r['part'] !== 'full') <span class="font-semibold">{{ __('app.part_short.'.$r['part']) }}</span>@endif</li>
                                            @endforeach
                                            @if ($rows->count() > 2)<li class="text-muted">{{ __('app.calendar.more', ['count' => $rows->count() - 2]) }}</li>@endif
                                        </ul>
                                    @elseif (! $isTeam && $entry)
                                        <span class="mt-auto text-xs font-extrabold sm:text-sm" aria-hidden="true">
                                            {{ __('app.wala') }}@if ($entry['part'] !== 'full') <span class="font-semibold">{{ __('app.part_short.'.$entry['part']) }}</span>@endif
                                        </span>
                                        @if ($entry['note'])<span class="hidden truncate text-xs md:block" aria-hidden="true">{{ $entry['note'] }}</span>@endif
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif ($resolvedView === 'week' && $isTeam)
        @if ($members->isEmpty())
            <x-ui.empty :title="trim($search) !== '' ? __('app.calendar.no_match', ['term' => $search]) : __('app.calendar.no_members')" icon="users" />
        @else
            <div x-data="calendarGrid()" @keydown="key($event)" class="overflow-x-auto rounded-lg border border-line bg-papel" data-ui>
                <table role="grid" aria-labelledby="cal-title-{{ $id }}" class="w-full min-w-[36rem] table-fixed border-separate border-spacing-1 p-1">
                    <thead>
                        <tr role="row">
                            <th role="columnheader" scope="col" class="sticky left-0 z-10 w-32 bg-papel px-2 pb-1 text-left text-xs font-semibold text-muted sm:w-48">{{ __('app.calendar.person') }}</th>
                            @foreach ($days as $day)
                                <th role="columnheader" scope="col" abbr="{{ Fmt::long($day) }}" class="pb-1 text-left text-xs font-semibold {{ $day->isSameDay($today) ? 'text-tinta' : 'text-muted' }}">
                                    {{ __('app.days_short.'.$day->dayOfWeek) }} <span class="text-tinta">{{ $day->day }}</span>
                                    @if ($day->isSameDay($today))<span class="block text-tinta">{{ __('app.today') }}</span>@endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $m)
                            <tr role="row" wire:key="row-{{ $m->id }}">
                                <th role="rowheader" scope="row" class="sticky left-0 z-10 bg-papel px-2 text-left text-sm font-semibold">
                                    <span class="flex items-center gap-2">
                                        <x-ui.avatar :name="$m->name" size="sm" class="hidden sm:inline-flex" />
                                        <span class="truncate">{{ $m->name }}@if ($m->id === auth()->id())<span class="font-medium text-muted"> · {{ __('app.team.you') }}</span>@endif</span>
                                    </span>
                                </th>
                                @foreach ($days as $day)
                                    @php
                                        $key = $day->toDateString();
                                        $r = $team->get($key, collect())->firstWhere('user_id', $m->id);
                                        $status = $r ? __('app.wala').$partLabel($r['part']) : __('app.available');
                                    @endphp
                                    <td role="gridcell" wire:key="w-{{ $m->id }}-{{ $key }}" data-date="{{ $key }}" data-row="{{ $m->id }}"
                                        tabindex="{{ $key === $focusDate && $loop->parent->first ? 0 : -1 }}"
                                        aria-label="{{ __('app.calendar.cell_person', ['date' => Fmt::long($day), 'name' => $m->name, 'status' => $status]) }}{{ $day->isSameDay($today) ? __('app.calendar.today_suffix') : '' }}"
                                        wire:click="showDay('{{ $key }}')"
                                        @class(['tile h-12 cursor-pointer align-middle', 'tile-wala hover:bg-kahel-2' => $r, 'hover:bg-press' => ! $r, 'tile-today' => $day->isSameDay($today)])>
                                        @if ($r)
                                            <span class="text-xs font-extrabold sm:text-sm" aria-hidden="true">{{ __('app.wala') }}@if ($r['part'] !== 'full') {{ __('app.part_short.'.$r['part']) }}@endif</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row" class="sticky left-0 z-10 bg-papel px-2 pt-2 text-left text-xs font-semibold text-muted">{{ __('app.summary.present') }}</th>
                            @foreach ($days as $day)
                                @php $out = $team->get($day->toDateString(), collect())->where('part', 'full')->count(); @endphp
                                <td class="px-1.5 pt-2 text-sm font-semibold" data-num>{{ $members->count() - $out }}<span class="font-medium text-muted">/{{ $members->count() }}</span></td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    @elseif ($resolvedView === 'week')
        <div x-data="calendarGrid()" @keydown="key($event)">
            <table role="grid" aria-labelledby="cal-title-{{ $id }}" class="w-full table-fixed border-separate border-spacing-1">
                <thead>
                    <tr role="row">
                        @foreach ($days as $day)
                            <th role="columnheader" scope="col" abbr="{{ Fmt::long($day) }}" class="pb-1 text-left text-xs font-semibold {{ $day->isSameDay($today) ? 'text-tinta' : 'text-muted' }}">
                                {{ __('app.days_short.'.$day->dayOfWeek) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr role="row">
                        @foreach ($days as $day)
                            @php $key = $day->toDateString(); $entry = $mine->get($key); @endphp
                            <td role="gridcell" wire:key="pw-{{ $key }}" data-date="{{ $key }}" tabindex="{{ $key === $focusDate ? 0 : -1 }}"
                                aria-label="{{ $meCellLabel($day) }}" @if ($day->isSameDay($today)) aria-current="date" @endif
                                wire:click="showDay('{{ $key }}')"
                                @class(['tile h-32 cursor-pointer align-top sm:h-44', 'tile-wala hover:bg-kahel-2' => $entry, 'hover:bg-press' => ! $entry, 'tile-today' => $day->isSameDay($today)])>
                                <span class="text-base font-extrabold sm:text-lg" aria-hidden="true">{{ $day->day }}</span>
                                @if ($day->isSameDay($today))<span class="text-xs font-semibold" aria-hidden="true">{{ __('app.today') }}</span>@endif
                                @if ($entry)
                                    <span class="mt-auto text-xs font-extrabold sm:text-sm" aria-hidden="true">{{ __('app.wala') }}@if ($entry['part'] !== 'full') {{ __('app.part_short.'.$entry['part']) }}@endif</span>
                                    @if ($entry['note'])<span class="hidden text-xs leading-4 sm:line-clamp-3" aria-hidden="true">{{ $entry['note'] }}</span>@endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @elseif ($isTeam)
        {{-- Team agenda: one line per day, two weeks. --}}
        <ol class="flex flex-col divide-y divide-line rounded-lg border border-line bg-papel" aria-labelledby="cal-title-{{ $id }}">
            @for ($day = $from; $day->lte($to); $day = $day->addDay())
                @php $key = $day->toDateString(); $rows = $team->get($key, collect()); @endphp
                <li wire:key="ta-{{ $key }}" @class(['flex flex-col gap-2 px-4 py-3 sm:flex-row sm:items-start sm:gap-6', 'bg-ground/60' => $day->isWeekend()])>
                    <button type="button" wire:click="showDay('{{ $key }}')" data-date="{{ $key }}"
                        class="-mx-1 shrink-0 rounded px-1 text-left text-sm font-semibold hover:underline sm:w-56" @if ($day->isSameDay($today)) aria-current="date" @endif>
                        {{ Fmt::date($day) }}@if ($day->isSameDay($today)) <x-ui.badge tone="dark" class="ml-1">{{ __('app.today') }}</x-ui.badge>@endif
                    </button>
                    @if ($rows->isEmpty())
                        <p class="text-sm text-muted">{{ __('app.calendar.everyone_in') }}</p>
                    @else
                        <ul class="flex flex-wrap gap-1.5">
                            @foreach ($rows as $r)
                                <li class="inline-flex min-h-7 items-center gap-1.5 rounded-sm bg-kahel px-2 text-sm font-semibold text-tinta">
                                    <span>{{ __('app.wala') }}</span><span class="font-medium">·</span><span>{{ $r['name'] }}</span>@if ($r['part'] !== 'full')<span>{{ __('app.part_short.'.$r['part']) }}</span>@endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endfor
        </ol>
    @else
        {{-- Personal agenda: only the Wala days, with notes. --}}
        @if ($mine->isEmpty())
            <x-ui.empty :title="__('app.calendar.agenda_me_empty_title')" icon="calendar-days">
                {{ __('app.calendar.agenda_me_empty_body') }}
                <x-slot:action>
                    <x-ui.button icon="calendar-plus" @click="Livewire.dispatch('open-absence-form')">{{ __('app.nav.file') }}</x-ui.button>
                </x-slot:action>
            </x-ui.empty>
        @else
            <ol class="flex flex-col divide-y divide-line rounded-lg border border-line bg-papel" aria-labelledby="cal-title-{{ $id }}">
                @foreach ($mine as $date => $entry)
                    <li wire:key="pa-{{ $date }}" class="flex items-center gap-4 px-4 py-3">
                        <span class="flex h-11 w-16 shrink-0 flex-col items-center justify-center rounded bg-kahel text-tinta" aria-hidden="true">
                            <span class="text-xs leading-4 font-extrabold">{{ __('app.wala') }}</span>
                            @if ($entry['part'] !== 'full')<span class="text-xs leading-4 font-semibold">{{ __('app.part_short.'.$entry['part']) }}</span>@endif
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold">{{ Fmt::date($date) }}<span class="sr-only">: {{ __('app.wala') }}{{ $partLabel($entry['part']) }}</span></p>
                            @if ($entry['note'])<p class="truncate text-sm text-muted">{{ $entry['note'] }}</p>@endif
                        </div>
                        <x-ui.button variant="ghost" size="sm" icon="pencil" @click="Livewire.dispatch('edit-absence', { id: {{ $entry['id'] }} })">
                            {{ __('app.calendar.edit_entry') }}<span class="sr-only">: {{ Fmt::date($date) }}</span>
                        </x-ui.button>
                    </li>
                @endforeach
            </ol>
        @endif
    @endif

    {{-- Legend: never colour alone — every Wala tile also carries the word. --}}
    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-muted">
        <span class="sr-only">{{ __('app.calendar.legend') }}:</span>
        <span class="inline-flex items-center gap-2"><span class="size-4 rounded-sm bg-abo" aria-hidden="true"></span>{{ __('app.calendar.legend_free') }}</span>
        <span class="inline-flex items-center gap-2"><span class="size-4 rounded-sm bg-kahel" aria-hidden="true"></span>{{ __('app.calendar.legend_wala') }}</span>
        <span class="inline-flex items-center gap-2"><span class="size-4 rounded-sm bg-papel outline-2 -outline-offset-2 outline-tinta" aria-hidden="true"></span>{{ __('app.calendar.legend_today') }}</span>
    </div>

    {{-- Day detail --}}
    <x-ui.dialog :name="'day-'.$id" :title="$dayDetail['label'] ?? ' '" size="sm">
        @if ($dayDetail)
            @if ($isTeam)
                <h3 class="mb-2 text-sm font-semibold text-muted">{{ __('app.calendar.day_out') }}</h3>
                @if ($dayDetail['team']->isEmpty())
                    <p>{{ __('app.calendar.day_nobody', ['count' => $dayDetail['memberCount']]) }}</p>
                @else
                    <ul class="flex flex-col divide-y divide-line">
                        @foreach ($dayDetail['team'] as $r)
                            <li class="flex items-center gap-3 py-2.5">
                                <x-ui.avatar :name="$r['name']" size="sm" />
                                <span class="flex-1 font-semibold">{{ $r['name'] }}</span>
                                <x-ui.badge tone="wala">{{ __('app.wala') }}@if ($r['part'] !== 'full') · {{ __('app.part_short.'.$r['part']) }}@endif</x-ui.badge>
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-3 text-sm text-muted" data-num>{{ __('app.summary.present') }}: {{ __('app.summary.present_of', ['present' => $dayDetail['memberCount'] - $dayDetail['team']->where('part', 'full')->count(), 'total' => $dayDetail['memberCount']]) }}</p>
                @endif
            @else
                @if ($dayDetail['mine'])
                    <div class="flex items-start gap-3">
                        <x-ui.badge tone="wala">{{ __('app.wala') }}@if ($dayDetail['mine']['part'] !== 'full') · {{ __('app.part_short.'.$dayDetail['mine']['part']) }}@endif</x-ui.badge>
                        <p>{{ __('app.calendar.day_me_wala', ['part' => $partLabel($dayDetail['mine']['part'])]) }}</p>
                    </div>
                    @if ($dayDetail['mine']['note'])
                        <div class="mt-4 rounded border border-line bg-ground px-4 py-3">
                            <p class="flex items-center gap-1.5 text-xs font-semibold text-muted"><x-ui.icon name="lock" size="14" />{{ __('app.calendar.note_private') }}</p>
                            <p class="mt-1">{{ $dayDetail['mine']['note'] }}</p>
                        </div>
                    @endif
                @else
                    <p>{{ __('app.calendar.day_me_free') }}</p>
                @endif
            @endif
        @endif
        <x-slot:footer>
            <x-ui.button variant="secondary" @click="close()">{{ __('ui.close') }}</x-ui.button>
            @if ($dayDetail && ! $isTeam && $dayDetail['mine'])
                <x-ui.button icon="pencil" @click="close(); Livewire.dispatch('edit-absence', { id: {{ $dayDetail['mine']['id'] }} })">{{ __('app.calendar.edit_entry') }}</x-ui.button>
            @elseif ($dayDetail)
                <x-ui.button icon="calendar-plus" @click="close(); Livewire.dispatch('open-absence-form', { date: '{{ $dayDetail['date']->toDateString() }}' })">{{ __('app.calendar.file_this_day') }}</x-ui.button>
            @endif
        </x-slot:footer>
    </x-ui.dialog>
</div>
