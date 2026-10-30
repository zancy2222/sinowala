@php use App\Support\Fmt; @endphp
<x-layouts.app :title="__('admin.messages.title')" admin>
    <x-page-header :title="__('admin.messages.title')" :subtitle="__('admin.messages.subtitle')">
        <x-slot:actions>
            <x-ui.tabs :label="__('admin.messages.title')" :items="[
                ['label' => __('admin.messages.unread_only'), 'href' => route('admin.messages', ['status' => 'unread']), 'active' => request('status') === 'unread'],
                ['label' => __('admin.messages.all'), 'href' => route('admin.messages'), 'active' => request('status') !== 'unread'],
            ]" />
        </x-slot:actions>
    </x-page-header>

    @if ($messages->isEmpty())
        <x-ui.empty :title="__('admin.messages.empty')" icon="inbox" />
    @else
        <ul class="flex flex-col gap-3">
            @foreach ($messages as $m)
                <li>
                    <article class="rounded-lg border border-line bg-papel p-5 sm:p-6" aria-labelledby="msg-{{ $m->id }}">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h2 id="msg-{{ $m->id }}" class="text-base font-extrabold">{{ $m->name }}</h2>
                                <p class="text-sm text-muted"><a href="mailto:{{ $m->email }}" class="link">{{ $m->email }}</a> · {{ Fmt::dateTime($m->created_at) }}</p>
                            </div>
                            @if ($m->read_at)
                                <x-ui.badge>{{ __('admin.messages.read') }}</x-ui.badge>
                            @else
                                <form method="POST" action="{{ route('admin.messages.read', $m) }}">@csrf<x-ui.button type="submit" variant="secondary" size="sm" icon="check">{{ __('admin.messages.mark_read') }}</x-ui.button></form>
                            @endif
                        </div>
                        <p class="measure mt-3 whitespace-pre-line">{{ $m->message }}</p>
                    </article>
                </li>
            @endforeach
        </ul>
    @endif
    <div class="mt-4">{{ $messages->links() }}</div>
</x-layouts.app>
