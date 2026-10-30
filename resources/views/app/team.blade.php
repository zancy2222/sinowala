@php use App\Support\Fmt; $me = auth()->user(); @endphp
<x-layouts.app :title="__('app.team.title')" :description="__('app.team.meta', ['company' => $company->name])">
    <x-page-header :title="__('app.team.title')" :subtitle="__('app.team.meta', ['company' => $company->name])" />

    @include('partials.company-status', ['company' => $company])

    <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
        <x-ui.card :title="__('app.team.members')" :description="trans_choice('app.team.member_count', $memberships->count(), ['count' => $memberships->count()])" :padding="false" id="members">
            <ul class="mt-2 divide-y divide-line border-t border-line">
                @foreach ($memberships as $m)
                    <li class="flex items-center gap-3 px-5 py-3 sm:px-6">
                        <x-ui.avatar :name="$m->user->name" />
                        <div class="min-w-0 flex-1">
                            <p class="flex flex-wrap items-center gap-x-2 gap-y-1 font-semibold">
                                <span class="truncate">{{ $m->user->name }}</span>
                                @if ($m->user_id === $me->id)<span class="text-sm font-medium text-muted">· {{ __('app.team.you') }}</span>@endif
                                <x-ui.badge :tone="$m->role === 'admin' ? 'dark' : 'neutral'">{{ __('app.team.role.'.$m->role) }}</x-ui.badge>
                            </p>
                            <p class="truncate text-sm text-muted">{{ $m->user->email }} · {{ __('app.team.joined', ['date' => Fmt::short($m->joined_at).', '.$m->joined_at->year]) }}</p>
                        </div>
                        @if ($me->can('promote', $m) || $me->can('demote', $m) || $me->can('remove', $m))
                            <x-ui.dropdown :label="__('app.team.actions', ['name' => $m->user->name])" icon-only>
                                @can('promote', $m)
                                    <form method="POST" action="{{ route('app.team.promote', $m) }}">@csrf<x-ui.dropdown-item icon="shield-check">{{ __('app.team.promote') }}</x-ui.dropdown-item></form>
                                @endcan
                                @can('demote', $m)
                                    <form method="POST" action="{{ route('app.team.demote', $m) }}">@csrf<x-ui.dropdown-item icon="user">{{ __('app.team.demote') }}</x-ui.dropdown-item></form>
                                @endcan
                                @can('remove', $m)
                                    <x-ui.dropdown-item type="button" icon="user-minus" danger @click="open = false; $dispatch('open-dialog', { name: 'remove-{{ $m->id }}' })">{{ __('app.team.remove') }}</x-ui.dropdown-item>
                                @endcan
                            </x-ui.dropdown>
                        @endif
                    </li>

                    @can('remove', $m)
                        <x-ui.dialog :name="'remove-'.$m->id" :title="__('app.team.remove_title', ['name' => $m->user->name])" size="sm">
                            <p>{{ __('app.team.remove_body') }}</p>
                            <x-slot:footer>
                                <x-ui.button variant="secondary" @click="close()">{{ __('ui.cancel') }}</x-ui.button>
                                <form method="POST" action="{{ route('app.team.remove', $m) }}">
                                    @csrf @method('DELETE')
                                    <x-ui.button type="submit" variant="danger-solid" block>{{ __('app.team.remove') }}</x-ui.button>
                                </form>
                            </x-slot:footer>
                        </x-ui.dialog>
                    @endcan
                @endforeach
            </ul>
            <p class="border-t border-line px-5 py-4 text-sm text-muted sm:px-6">{{ __('app.team.last_admin_note') }} {{ __('app.team.privacy_note') }}</p>
        </x-ui.card>

        <x-ui.card :title="__('app.team.invite_title')" :description="__('app.team.invite_body')" id="invite">
            @if ($company->isActive())
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <label for="invite-link" class="text-sm font-semibold">{{ __('app.team.invite_link') }}</label>
                        <div class="flex" x-data="copyButton(@js($company->inviteUrl()), @js(__('app.team.link_copied')))">
                            <input id="invite-link" type="text" readonly value="{{ $company->inviteUrl() }}" class="block min-h-11 w-full min-w-0 rounded-l border border-r-0 border-line-strong bg-ground px-3 text-sm" @focus="$el.select()">
                            <button type="button" @click="copy()" class="inline-flex min-h-11 shrink-0 items-center gap-2 rounded-r border border-line-strong bg-papel px-3 text-sm font-semibold transition-colors hover:bg-hover">
                                <x-ui.icon name="copy" size="18" x-show="!done" /><x-ui.icon name="check" size="18" x-show="done" x-cloak />
                                <span>{{ __('ui.copy') }}</span><span class="sr-only">{{ __('app.team.invite_link') }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <p class="text-sm font-semibold" id="invite-code-label">{{ __('app.team.code') }}</p>
                        <div class="flex items-center gap-3" x-data="copyButton(@js($company->code), @js(__('app.team.code_copied')))">
                            <p class="rounded bg-ground px-4 py-2 text-2xl font-extrabold tracking-[0.2em]" aria-labelledby="invite-code-label" data-num>{{ $company->code }}</p>
                            <x-ui.button variant="secondary" size="sm" icon="copy" @click="copy()">{{ __('app.team.copy_code') }}</x-ui.button>
                        </div>
                    </div>
                    <div class="border-t border-line pt-4">
                        <x-ui.button variant="secondary" icon="refresh-cw" block @click="$dispatch('open-dialog', { name: 'regenerate' })">{{ __('app.team.regenerate') }}</x-ui.button>
                        <p class="mt-2 text-sm text-muted">{{ __('app.team.regenerate_help') }}</p>
                        <x-ui.dialog name="regenerate" :title="__('app.team.regenerate')" size="sm">
                            <p>{{ __('app.team.regenerate_confirm') }}</p>
                            <x-slot:footer>
                                <x-ui.button variant="secondary" @click="close()">{{ __('ui.cancel') }}</x-ui.button>
                                <form method="POST" action="{{ route('app.team.regenerate') }}">
                                    @csrf
                                    <x-ui.button type="submit" block>{{ __('app.team.regenerate') }}</x-ui.button>
                                </form>
                            </x-slot:footer>
                        </x-ui.dialog>
                    </div>
                </div>
            @else
                <p class="text-sm">{{ __('app.team.invite_pending') }}</p>
            @endif
        </x-ui.card>
    </div>
</x-layouts.app>
