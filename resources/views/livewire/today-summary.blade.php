@php use App\Support\Fmt; @endphp
<div>
    @if ($mode === 'team')
        {{-- One container, three answers: who is out, how many are in, who is next. --}}
        <section aria-labelledby="summary-title" class="grid grid-cols-[minmax(0,1fr)] rounded-lg border border-line bg-papel lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)_minmax(0,1.4fr)]">
            <h2 id="summary-title" class="sr-only">{{ Fmt::date($today) }}</h2>

            <div class="flex flex-col gap-3 p-5 sm:p-6">
                <h3 class="text-sm font-semibold text-muted">{{ __('app.summary.wala_today') }}</h3>
                @if ($outToday->isEmpty())
                    <p class="text-lg font-extrabold">{{ __('app.summary.nobody_out') }}</p>
                @else
                    <ul class="flex flex-col gap-2">
                        @foreach ($outToday as $r)
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-7 min-w-14 items-center justify-center rounded-sm bg-kahel px-2 text-xs font-extrabold text-tinta">{{ __('app.wala') }}@if ($r['part'] !== 'full')&nbsp;{{ __('app.part_short.'.$r['part']) }}@endif</span>
                                <span class="truncate text-base font-semibold">{{ $r['name'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="flex flex-col gap-1 border-t border-line p-5 sm:p-6 lg:border-t-0 lg:border-l">
                <h3 class="text-sm font-semibold text-muted">{{ __('app.summary.present') }}</h3>
                <p class="text-2xl font-extrabold" data-num>{{ __('app.summary.present_of', ['present' => $presentCount, 'total' => $memberCount]) }}</p>
                @if ($halfDayCount > 0)
                    <p class="text-sm text-muted">{{ trans_choice('app.summary.half_days', $halfDayCount, ['count' => $halfDayCount]) }}</p>
                @endif
                <p class="mt-auto pt-3 text-sm text-muted">{{ Fmt::date($today) }}</p>
            </div>

            <div class="flex flex-col gap-3 border-t border-line p-5 sm:p-6 lg:border-t-0 lg:border-l">
                <h3 class="text-sm font-semibold text-muted">{{ __('app.summary.next_lakad') }}</h3>
                @if ($upcoming->isEmpty())
                    <p class="text-sm">{{ __('app.summary.no_upcoming') }}</p>
                @else
                    <ul class="flex flex-col gap-2.5">
                        @foreach ($upcoming as $date => $rows)
                            <li class="flex flex-col gap-0.5 sm:flex-row sm:gap-3">
                                <span class="w-24 shrink-0 text-sm font-semibold" data-num>{{ Fmt::dayShort($date) }}, {{ Fmt::short($date) }}</span>
                                <span class="min-w-0 text-sm">{{ $rows->map(fn ($r) => $r['name'].($r['part'] !== 'full' ? ' ('.__('app.part_short.'.$r['part']).')' : ''))->implode(', ') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
    @else
        <section aria-labelledby="summary-title" class="grid grid-cols-[minmax(0,1fr)] rounded-lg border border-line bg-papel lg:grid-cols-[minmax(0,1fr)_minmax(0,2fr)]">
            <h2 id="summary-title" class="sr-only">{{ Fmt::date($today) }}</h2>
            <div class="flex flex-col gap-1 p-5 sm:p-6">
                <h3 class="text-sm font-semibold text-muted">{{ Fmt::date($today) }}</h3>
                @if ($todayEntry)
                    <p class="flex items-center gap-3 text-xl font-extrabold">
                        <span class="inline-flex h-8 items-center rounded-sm bg-kahel px-2.5 text-sm font-extrabold text-tinta">{{ __('app.wala') }}@if ($todayEntry->part !== 'full')&nbsp;{{ __('app.part_short.'.$todayEntry->part) }}@endif</span>
                        {{ __('app.summary.you_out_today') }}
                    </p>
                @else
                    <p class="text-xl font-extrabold">{{ __('app.summary.you_in_today') }}</p>
                @endif
                <p class="mt-3 text-sm text-muted" data-num>{{ __('app.summary.this_month') }}: <span class="font-semibold text-tinta">{{ trans_choice('app.summary.days_count', (int) ceil($monthDays), ['count' => (floor($monthDays) > 0 ? (int) floor($monthDays) : '').(fmod($monthDays, 1) ? '½' : '')]) }}</span></p>
            </div>
            <div class="flex flex-col gap-3 border-t border-line p-5 sm:p-6 lg:border-t-0 lg:border-l">
                <h3 class="text-sm font-semibold text-muted">{{ __('app.summary.my_next') }}</h3>
                @if ($next->isEmpty())
                    <p class="font-extrabold">{{ __('app.summary.no_next_title') }}</p>
                    <p class="measure text-sm text-muted">{{ __('app.summary.no_next_body') }}</p>
                @else
                    <ul class="flex flex-col divide-y divide-line">
                        @foreach ($next as $a)
                            <li class="flex items-center gap-3 py-2 first:pt-0 last:pb-0">
                                <span class="inline-flex h-7 min-w-14 shrink-0 items-center justify-center rounded-sm bg-kahel px-2 text-xs font-extrabold text-tinta">{{ __('app.wala') }}@if ($a->part !== 'full')&nbsp;{{ __('app.part_short.'.$a->part) }}@endif</span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-semibold">{{ Fmt::date($a->date) }}</span>
                                    @if ($a->note)<span class="block truncate text-sm text-muted">{{ $a->note->note }}</span>@endif
                                </span>
                                <button type="button" class="inline-flex size-11 shrink-0 items-center justify-center rounded text-tinta transition-colors hover:bg-press" @click="Livewire.dispatch('edit-absence', { id: {{ $a->id }} })">
                                    <x-ui.icon name="pencil" size="18" /><span class="sr-only">{{ __('app.calendar.edit_entry') }}: {{ Fmt::date($a->date) }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
    @endif
</div>
