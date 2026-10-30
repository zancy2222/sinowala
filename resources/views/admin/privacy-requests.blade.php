@php use App\Support\Fmt; @endphp
<x-layouts.app :title="__('admin.privacy.title')" admin>
    <x-page-header :title="__('admin.privacy.title')" :subtitle="__('admin.privacy.subtitle')" />

    @include('admin.partials.filters', ['searchLabel' => __('admin.users.search'), 'statuses' => ['open' => __('admin.privacy.open'), 'done' => __('admin.privacy.done')]])

    @if ($requests->isEmpty())
        <x-ui.empty :title="__('admin.privacy.empty')" icon="shield" />
    @else
        <ul class="flex flex-col gap-3">
            @foreach ($requests as $r)
                <li>
                    <article class="rounded-lg border border-line bg-papel p-5 sm:p-6" aria-labelledby="pr-{{ $r->id }}">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h2 id="pr-{{ $r->id }}" class="flex flex-wrap items-center gap-2 text-base font-extrabold">
                                    {{ __('web.privacy_request.types.'.$r->type) }}
                                    <x-ui.badge :tone="$r->status === 'done' ? 'success' : 'outline'">{{ $r->status === 'done' ? __('admin.privacy.done') : __('admin.privacy.open') }}</x-ui.badge>
                                </h2>
                                <p class="text-sm text-muted">{{ $r->name }} · <a href="mailto:{{ $r->email }}" class="link">{{ $r->email }}</a> · {{ Fmt::dateTime($r->created_at) }} · {{ $r->user_id ? __('admin.privacy.has_account') : __('admin.privacy.no_account') }}</p>
                            </div>
                            @if ($r->status !== 'done')
                                <form method="POST" action="{{ route('admin.privacy-requests.done', $r) }}">@csrf<x-ui.button type="submit" variant="secondary" size="sm" icon="check">{{ __('admin.privacy.mark_done') }}</x-ui.button></form>
                            @endif
                        </div>
                        @if ($r->details)<p class="measure mt-3 whitespace-pre-line">{{ $r->details }}</p>@endif
                    </article>
                </li>
            @endforeach
        </ul>
    @endif
    <div class="mt-4">{{ $requests->links() }}</div>
</x-layouts.app>
