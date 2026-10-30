@php
    use App\Support\Fmt;
    $max = max(1, $weeks->max('count'));
    $step = max(1, (int) ceil($max / 4));
    $top = $step * 4;
@endphp
<x-layouts.app :title="__('admin.dashboard.title')" admin>
    <x-page-header :title="__('admin.dashboard.title')" :subtitle="__('admin.counts_only')" />

    {{-- KPI strip: one container split by dividers. --}}
    <section aria-label="{{ __('admin.dashboard.title') }}" class="grid grid-cols-2 rounded-lg border border-line bg-papel lg:grid-cols-4">
        @foreach (['personal', 'companies', 'members', 'pending'] as $i => $key)
            <div @class(['flex flex-col gap-1 p-5 sm:p-6', 'border-l border-line' => $i % 2 === 1, 'lg:border-l' => $i > 0, 'border-t border-line lg:border-t-0' => $i > 1])>
                <p class="text-sm font-semibold text-muted">{{ __('admin.dashboard.'.$key) }}</p>
                <p class="text-2xl font-extrabold" data-num>{{ number_format($totals[$key]) }}</p>
            </div>
        @endforeach
    </section>

    <div class="mt-6 grid items-start gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
        <x-ui.card :title="__('admin.dashboard.signups')" :description="__('admin.dashboard.signups_caption')">
            {{-- One series, square columns, a scale with round steps above the maximum. --}}
            <figure>
                <div class="grid grid-cols-[2rem_minmax(0,1fr)] gap-2">
                    <div class="flex h-48 flex-col justify-between text-right text-xs text-muted" aria-hidden="true" data-num>
                        @for ($t = 4; $t >= 0; $t--)<span class="-translate-y-1/2 first:translate-y-0 last:translate-y-0">{{ $step * $t }}</span>@endfor
                    </div>
                    <div class="relative h-48 border-b-2 border-tinta">
                        @for ($t = 1; $t <= 4; $t++)
                            <div class="absolute inset-x-0 border-t border-line" style="bottom: {{ $t * 25 }}%" aria-hidden="true"></div>
                        @endfor
                        <ol class="absolute inset-0 grid grid-cols-8 items-end gap-2 px-1">
                            @foreach ($weeks as $w)
                                <li class="flex h-full flex-col justify-end">
                                    <span class="sr-only">{{ __('admin.dashboard.week_of', ['date' => Fmt::short($w['start'])]) }}: {{ trans_choice('admin.dashboard.signups_count', $w['count'], ['count' => $w['count']]) }}</span>
                                    <span class="block bg-tinta" style="height: {{ $w['count'] > 0 ? max(2, round($w['count'] / $top * 100, 2)) : 0 }}%" aria-hidden="true"></span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                    <span></span>
                    <div class="grid grid-cols-8 gap-2 px-1 text-xs text-muted" aria-hidden="true">
                        @foreach ($weeks as $w)<span class="truncate text-center" data-num>{{ Fmt::short($w['start']) }}</span>@endforeach
                    </div>
                </div>
            </figure>
        </x-ui.card>

        <div class="flex flex-col gap-6">
            <x-ui.card :title="__('admin.dashboard.pending_title')">
                <x-slot:actions><a href="{{ route('admin.companies', ['status' => 'pending']) }}" class="link text-sm">{{ __('admin.dashboard.view_all') }}</a></x-slot:actions>
                @if ($pendingCompanies->isEmpty())
                    <p class="text-sm text-muted">{{ __('admin.dashboard.pending_empty') }}</p>
                @else
                    <ul class="flex flex-col divide-y divide-line">
                        @foreach ($pendingCompanies as $c)
                            <li class="flex items-center gap-3 py-2.5 first:pt-0 last:pb-0">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-semibold">{{ $c->name }}</p>
                                    <p class="text-sm text-muted" data-num>{{ Fmt::dateTime($c->created_at) }} · {{ $c->memberships_count }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.companies.approve', $c) }}">@csrf<x-ui.button type="submit" size="sm">{{ __('admin.companies.approve') }}<span class="sr-only">: {{ $c->name }}</span></x-ui.button></form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
            <x-ui.card :title="__('admin.dashboard.inbox')">
                <ul class="flex flex-col gap-2 text-sm">
                    <li><a href="{{ route('admin.messages', ['status' => 'unread']) }}" class="link">{{ trans_choice('admin.dashboard.unread', $unreadMessages, ['count' => $unreadMessages]) }}</a></li>
                    <li><a href="{{ route('admin.privacy-requests', ['status' => 'open']) }}" class="link">{{ trans_choice('admin.dashboard.open_requests', $openRequests, ['count' => $openRequests]) }}</a></li>
                </ul>
            </x-ui.card>
        </div>
    </div>
</x-layouts.app>
