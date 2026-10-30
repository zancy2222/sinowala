@php
    $status = session('status');
    $twoFactorPending = $user->two_factor_secret && ! $user->two_factor_confirmed_at;
    $showCodes = $user->hasTwoFactorEnabled() && in_array($status, ['two-factor-authentication-confirmed', 'recovery-codes-generated'], true);
    $membership = $user->membership;
@endphp
<x-layouts.app :title="__('app.settings.title')" :description="__('app.settings.meta')">
    <x-page-header :title="__('app.settings.title')" :subtitle="__('app.settings.meta')" />

    <nav aria-label="{{ __('app.settings.sections') }}" class="mb-8 -mx-1 overflow-x-auto">
        <ul class="flex gap-1 px-1 pb-1">
            @foreach (['profile' => __('app.settings.profile.title'), 'password' => __('app.settings.password.title'), 'security' => __('app.settings.two_factor.title'), 'sessions' => __('app.settings.sessions.title'), 'share' => __('app.settings.share.title'), 'shortcuts' => __('app.settings.shortcuts.title'), 'company' => __('app.settings.company.title'), 'data' => __('app.settings.data.title'), 'delete' => __('app.settings.delete.title')] as $anchor => $label)
                <li><a href="#{{ $anchor }}" class="inline-flex min-h-11 items-center whitespace-nowrap rounded border border-line bg-papel px-3 text-sm font-semibold transition-colors hover:bg-hover lg:min-h-9">{{ $label }}</a></li>
            @endforeach
        </ul>
    </nav>

    <div class="flex flex-col">
        {{-- Profile --}}
        <x-settings-section id="profile" :title="__('app.settings.profile.title')" :description="__('app.settings.profile.description')">
            <form method="POST" action="{{ route('user-profile-information.update') }}" class="flex max-w-lg flex-col gap-4" novalidate>
                @csrf @method('PUT')
                <x-ui.field :label="__('app.settings.profile.name')" for="profile-name" error="name" bag="updateProfileInformation">
                    <x-ui.input name="name" id="profile-name" bag="updateProfileInformation" :value="old('name', $user->name)" autocomplete="name" required maxlength="80" />
                </x-ui.field>
                <x-ui.field :label="__('app.settings.profile.email')" for="profile-email" error="email" bag="updateProfileInformation" :help="__('app.settings.profile.email_change_note')">
                    <x-ui.input type="email" name="email" id="profile-email" bag="updateProfileInformation" :value="old('email', $user->email)" autocomplete="email" required :help="true" />
                </x-ui.field>
                <div><x-ui.button type="submit">{{ __('ui.save') }}</x-ui.button></div>
            </form>
            @unless ($user->hasVerifiedEmail())
                <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                    @csrf
                    <x-ui.alert>{{ __('app.settings.profile.unverified') }} <button type="submit" class="link font-semibold">{{ __('app.settings.profile.resend') }}</button></x-ui.alert>
                </form>
            @endunless
        </x-settings-section>

        {{-- Password --}}
        <x-settings-section id="password" :title="__('app.settings.password.title')" :description="__('app.settings.password.description')">
            <form method="POST" action="{{ route('user-password.update') }}" class="flex max-w-lg flex-col gap-4" novalidate>
                @csrf @method('PUT')
                <x-ui.field :label="__('app.settings.password.current')" for="current_password" bag="updatePassword">
                    <x-ui.input type="password" name="current_password" bag="updatePassword" autocomplete="current-password" required />
                </x-ui.field>
                <x-ui.field :label="__('app.settings.password.new')" for="new-password" error="password" bag="updatePassword" :help="__('app.settings.password.rules')">
                    <x-ui.input type="password" name="password" id="new-password" bag="updatePassword" autocomplete="new-password" required :help="true" minlength="8" />
                </x-ui.field>
                <x-ui.field :label="__('app.settings.password.confirm')" for="password_confirmation" bag="updatePassword">
                    <x-ui.input type="password" name="password_confirmation" bag="updatePassword" autocomplete="new-password" required />
                </x-ui.field>
                <div><x-ui.button type="submit">{{ __('app.settings.password.save') }}</x-ui.button></div>
            </form>
        </x-settings-section>

        {{-- Two-factor --}}
        <x-settings-section id="security" :title="__('app.settings.two_factor.title')" :description="__('app.settings.two_factor.description')">
            <div class="flex flex-col gap-4">
                <p class="flex items-center gap-2 font-semibold">
                    @if ($user->hasTwoFactorEnabled())
                        <x-ui.badge tone="success"><x-ui.icon name="shield-check" size="14" />{{ __('app.settings.two_factor.status_on') }}</x-ui.badge>
                    @elseif ($twoFactorPending)
                        <x-ui.badge tone="outline">{{ __('app.settings.two_factor.status_pending') }}</x-ui.badge>
                    @else
                        <x-ui.badge>{{ __('app.settings.two_factor.status_off') }}</x-ui.badge>
                    @endif
                </p>
                @if ($user->is_super_admin && ! $user->hasTwoFactorEnabled())
                    <x-ui.alert tone="danger">{{ __('app.settings.two_factor.required_admin') }}</x-ui.alert>
                @endif

                @if ($twoFactorPending)
                    <p class="measure">{{ __('app.settings.two_factor.scan') }}</p>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                        <div class="w-fit rounded border border-line bg-white p-3 [&_svg]:size-44">{!! $user->twoFactorQrCodeSvg() !!}</div>
                        <div class="min-w-0">
                            <p class="text-sm text-muted">{{ __('app.settings.two_factor.setup_key') }}</p>
                            <p class="mt-1 break-all font-semibold" data-num>{{ decrypt($user->two_factor_secret) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('two-factor.confirm') }}" class="flex max-w-sm flex-col gap-4" novalidate>
                        @csrf
                        <x-ui.field :label="__('app.settings.two_factor.code')" for="tfa-code" error="code" bag="confirmTwoFactorAuthentication">
                            <x-ui.input name="code" id="tfa-code" bag="confirmTwoFactorAuthentication" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]*" maxlength="6" required />
                        </x-ui.field>
                        <div class="flex flex-wrap gap-2">
                            <x-ui.button type="submit">{{ __('app.settings.two_factor.confirm') }}</x-ui.button>
                        </div>
                    </form>
                @endif

                @if ($showCodes)
                    <div class="rounded border border-line bg-ground p-4">
                        <p class="font-extrabold">{{ __('app.settings.two_factor.recovery_title') }}</p>
                        <p class="mt-1 text-sm text-muted">{{ __('app.settings.two_factor.recovery_body') }}</p>
                        <ul class="mt-3 grid grid-cols-1 gap-1 font-semibold min-[420px]:grid-cols-2" data-num>
                            @foreach ($user->recoveryCodes() as $code)<li>{{ $code }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-wrap gap-2">
                    @if (! $user->two_factor_secret)
                        <form method="POST" action="{{ route('two-factor.enable') }}">@csrf<x-ui.button type="submit" icon="shield">{{ __('app.settings.two_factor.enable') }}</x-ui.button></form>
                    @else
                        @if ($user->hasTwoFactorEnabled())
                            <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}">@csrf<x-ui.button type="submit" variant="secondary" icon="refresh-cw">{{ __('app.settings.two_factor.regenerate_recovery') }}</x-ui.button></form>
                        @endif
                        <form method="POST" action="{{ route('two-factor.disable') }}">@csrf @method('DELETE')<x-ui.button type="submit" variant="destructive">{{ __('app.settings.two_factor.disable') }}</x-ui.button></form>
                    @endif
                </div>
            </div>
        </x-settings-section>

        {{-- Sessions --}}
        <x-settings-section id="sessions" :title="__('app.settings.sessions.title')" :description="__('app.settings.sessions.description')">
            <ul class="flex flex-col divide-y divide-line">
                @forelse ($sessions as $s)
                    <li class="flex items-center gap-3 py-3 first:pt-0">
                        <x-ui.icon :name="$s->mobile ? 'smartphone' : 'monitor'" size="22" class="text-muted" />
                        <div class="min-w-0">
                            <p class="font-semibold">{{ $s->device }}</p>
                            <p class="text-sm text-muted">{{ $s->ip }} ·
                                @if ($s->current)<span class="font-semibold text-success">{{ __('app.settings.sessions.this_device') }}</span>@else{{ __('app.settings.sessions.last_active', ['time' => $s->last_active]) }}@endif
                            </p>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-muted">{{ __('app.settings.sessions.none') }}</li>
                @endforelse
            </ul>
            <div class="mt-4">
                <x-ui.button variant="secondary" icon="log-out" @click="$dispatch('open-dialog', { name: 'logout-others' })">{{ __('app.settings.sessions.logout_others') }}</x-ui.button>
            </div>
            <x-ui.dialog name="logout-others" :title="__('app.settings.sessions.logout_title')" :description="__('app.settings.sessions.logout_body')" size="sm"
                x-init="{{ $errors->has('password') && old('_form') === 'sessions' ? '$nextTick(() => open())' : '' }}">
                <form method="POST" action="{{ route('settings.sessions.destroy') }}" id="logout-others-form" novalidate>
                    @csrf
                    <input type="hidden" name="_form" value="sessions">
                    <x-ui.field :label="__('auth.fields.password')" for="sessions-password" error="password">
                        <x-ui.input type="password" name="password" id="sessions-password" autocomplete="current-password" required data-autofocus />
                    </x-ui.field>
                </form>
                <x-slot:footer>
                    <x-ui.button variant="secondary" @click="close()">{{ __('ui.cancel') }}</x-ui.button>
                    <x-ui.button type="submit" form="logout-others-form">{{ __('app.settings.sessions.logout_others') }}</x-ui.button>
                </x-slot:footer>
            </x-ui.dialog>
        </x-settings-section>

        {{-- Share link --}}
        <x-settings-section id="share" :title="__('app.settings.share.title')" :description="__('app.settings.share.description')">
            <div class="flex flex-col gap-4">
                <form method="POST" action="{{ route('settings.share') }}" class="flex flex-wrap items-center justify-between gap-3">
                    @csrf
                    <input type="hidden" name="enabled" value="{{ $user->share_enabled ? 0 : 1 }}">
                    <p class="flex items-center gap-2 font-semibold">
                        <x-ui.badge :tone="$user->share_enabled ? 'success' : 'neutral'">{{ $user->share_enabled ? __('ui.on') : __('ui.off') }}</x-ui.badge>
                        {{ $user->share_enabled ? __('app.settings.share.on_label') : __('app.settings.share.off_label') }}
                    </p>
                    <x-ui.button type="submit" :variant="$user->share_enabled ? 'secondary' : 'primary'" size="sm">{{ $user->share_enabled ? __('app.settings.share.turn_off') : __('app.settings.share.turn_on') }}</x-ui.button>
                </form>
                @if ($user->share_enabled && $user->share_token)
                    @php $shareUrl = route('share.show', $user->share_token); @endphp
                    <div class="flex flex-col gap-2">
                        <label for="share-link" class="text-sm font-semibold">{{ __('app.settings.share.link') }}</label>
                        <div class="flex" x-data="copyButton(@js($shareUrl), @js(__('app.settings.share.copied')))">
                            <input id="share-link" type="text" readonly value="{{ $shareUrl }}" class="block min-h-11 w-full min-w-0 rounded-l border border-r-0 border-line-strong bg-ground px-3 text-sm" @focus="$el.select()">
                            <button type="button" @click="copy()" class="inline-flex min-h-11 shrink-0 items-center gap-2 rounded-r border border-line-strong bg-papel px-3 text-sm font-semibold transition-colors hover:bg-hover">
                                <x-ui.icon name="copy" size="18" /><span>{{ __('ui.copy') }}</span><span class="sr-only">{{ __('app.settings.share.link') }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <x-ui.button variant="secondary" size="sm" :href="$shareUrl" icon="eye" target="_blank" rel="noopener">{{ __('app.settings.share.preview') }}</x-ui.button>
                        <form method="POST" action="{{ route('settings.share.regenerate') }}">
                            @csrf
                            <x-ui.button type="submit" variant="ghost" size="sm" icon="refresh-cw">{{ __('app.settings.share.regenerate') }}</x-ui.button>
                        </form>
                    </div>
                    <p class="text-sm text-muted">{{ __('app.settings.share.regenerate_help') }}</p>
                @endif
            </div>
        </x-settings-section>

        {{-- Shortcuts --}}
        <x-settings-section id="shortcuts" :title="__('app.settings.shortcuts.title')" :description="__('app.settings.shortcuts.description')">
            <form method="POST" action="{{ route('settings.shortcuts') }}" class="flex flex-wrap items-center justify-between gap-3">
                @csrf
                <input type="hidden" name="enabled" value="{{ $user->shortcuts_enabled ? 0 : 1 }}">
                <p class="flex items-center gap-2 font-semibold">
                    <x-ui.badge :tone="$user->shortcuts_enabled ? 'success' : 'neutral'">{{ $user->shortcuts_enabled ? __('ui.on') : __('ui.off') }}</x-ui.badge>
                    {{ __('app.settings.shortcuts.toggle') }}
                </p>
                <div class="flex flex-wrap gap-2">
                    <x-ui.button variant="ghost" size="sm" icon="keyboard" @click="$dispatch('open-dialog', { name: 'shortcuts' })">{{ __('app.settings.shortcuts.show') }}</x-ui.button>
                    <x-ui.button type="submit" :variant="$user->shortcuts_enabled ? 'secondary' : 'primary'" size="sm">{{ $user->shortcuts_enabled ? __('app.settings.share.turn_off') : __('app.settings.share.turn_on') }}</x-ui.button>
                </div>
            </form>
        </x-settings-section>

        {{-- Company --}}
        <x-settings-section id="company" :title="__('app.settings.company.title')"
            :description="$company ? __('app.settings.company.description_member', ['company' => $company->name, 'role' => __('app.team.role.'.$membership->role)]) : __('app.settings.company.description_none')">
            @error('company')<x-ui.alert tone="danger" class="mb-4">{{ $message }}</x-ui.alert>@enderror
            @if (! $company)
                <div class="grid gap-6 md:grid-cols-2">
                    <form method="POST" action="{{ route('company.store') }}" class="flex flex-col gap-3" novalidate>
                        @csrf
                        <h3 class="font-extrabold">{{ __('app.settings.company.create_title') }}</h3>
                        <p class="text-sm text-muted">{{ __('app.settings.company.create_body') }}</p>
                        <x-ui.field :label="__('app.settings.company.name')" for="company_name">
                            <x-ui.input name="company_name" :value="old('company_name')" autocomplete="organization" maxlength="120" required />
                        </x-ui.field>
                        <div><x-ui.button type="submit" icon="building-2">{{ __('app.settings.company.create') }}</x-ui.button></div>
                    </form>
                    <form method="POST" action="{{ route('company.join') }}" class="flex flex-col gap-3 border-t border-line pt-6 md:border-t-0 md:border-l md:pt-0 md:pl-6" novalidate>
                        @csrf
                        <h3 class="font-extrabold">{{ __('app.settings.company.join_title') }}</h3>
                        <p class="text-sm text-muted">{{ __('app.settings.company.join_body') }}</p>
                        <x-ui.field :label="__('auth.register.code')" for="code">
                            <x-ui.input name="code" :value="old('code')" maxlength="6" autocomplete="off" autocapitalize="characters" spellcheck="false" class="uppercase tracking-[0.2em]" required />
                        </x-ui.field>
                        <div><x-ui.button type="submit" variant="secondary" icon="user-plus">{{ __('app.settings.company.join') }}</x-ui.button></div>
                    </form>
                </div>
            @else
                <div class="flex flex-col gap-6">
                    <p class="flex flex-wrap items-center gap-2 text-sm">
                        <span class="text-muted">{{ __('app.settings.company.status') }}:</span>
                        <x-ui.badge :tone="$company->isActive() ? 'success' : ($company->status === 'suspended' ? 'danger' : 'neutral')">{{ __('app.company.status.'.$company->status) }}</x-ui.badge>
                    </p>
                    @can('update', $company)
                        <form method="POST" action="{{ route('company.update') }}" class="flex max-w-lg flex-col gap-3" novalidate>
                            @csrf @method('PUT')
                            <x-ui.field :label="__('app.settings.company.name')" for="company-rename" error="name">
                                <x-ui.input name="name" id="company-rename" :value="old('name', $company->name)" maxlength="120" required />
                            </x-ui.field>
                            <div><x-ui.button type="submit" variant="secondary">{{ __('app.settings.company.rename') }}</x-ui.button></div>
                        </form>
                    @endcan
                    <div class="flex flex-wrap gap-2 border-t border-line pt-5">
                        <x-ui.button variant="secondary" icon="log-out" @click="$dispatch('open-dialog', { name: 'leave-company' })">{{ __('app.settings.company.leave') }}</x-ui.button>
                        @can('delete', $company)
                            <x-ui.button variant="destructive" icon="trash-2" @click="$dispatch('open-dialog', { name: 'delete-company' })">{{ __('app.settings.company.delete') }}</x-ui.button>
                        @endcan
                    </div>
                </div>

                <x-ui.dialog name="leave-company" :title="__('app.settings.company.leave_title', ['company' => $company->name])" size="sm">
                    <p>{{ __('app.settings.company.leave_body') }}</p>
                    <x-slot:footer>
                        <x-ui.button variant="secondary" @click="close()">{{ __('ui.cancel') }}</x-ui.button>
                        <form method="POST" action="{{ route('company.leave') }}">@csrf<x-ui.button type="submit" block>{{ __('app.settings.company.leave') }}</x-ui.button></form>
                    </x-slot:footer>
                </x-ui.dialog>

                @can('delete', $company)
                    <x-ui.dialog name="delete-company" :title="__('app.settings.company.delete_title', ['company' => $company->name])" size="sm"
                        x-init="{{ $errors->has('confirm_name') ? '$nextTick(() => open())' : '' }}">
                        <p class="mb-4">{{ __('app.settings.company.delete_body') }}</p>
                        <form method="POST" action="{{ route('company.destroy') }}" id="delete-company-form" novalidate>
                            @csrf @method('DELETE')
                            <x-ui.field :label="__('app.company.confirm_name_label').': '.$company->name" for="confirm_name">
                                <x-ui.input name="confirm_name" autocomplete="off" required data-autofocus />
                            </x-ui.field>
                        </form>
                        <x-slot:footer>
                            <x-ui.button variant="secondary" @click="close()">{{ __('ui.cancel') }}</x-ui.button>
                            <x-ui.button type="submit" form="delete-company-form" variant="danger-solid">{{ __('app.settings.company.delete') }}</x-ui.button>
                        </x-slot:footer>
                    </x-ui.dialog>
                @endcan
            @endif
        </x-settings-section>

        {{-- Data --}}
        <x-settings-section id="data" :title="__('app.settings.data.title')" :description="__('app.settings.data.description')">
            <p class="mb-4 text-sm">{{ __('app.settings.data.download_body') }}</p>
            <x-ui.button :href="route('settings.export')" variant="secondary" icon="download">{{ __('app.settings.data.download') }}</x-ui.button>
        </x-settings-section>

        {{-- Delete account --}}
        <x-settings-section id="delete" :title="__('app.settings.delete.title')" :description="__('app.settings.delete.description')">
            @if ($errors->deleteAccount->any())
                <x-ui.alert tone="danger" class="mb-4">{{ $errors->deleteAccount->first() }}</x-ui.alert>
            @endif
            <x-ui.button variant="destructive" icon="trash-2" @click="$dispatch('open-dialog', { name: 'delete-account' })">{{ __('app.settings.delete.button') }}</x-ui.button>
            <x-ui.dialog name="delete-account" :title="__('app.settings.delete.dialog_title')" :description="__('app.settings.delete.dialog_body')" size="sm"
                x-init="{{ $errors->deleteAccount->any() ? '$nextTick(() => open())' : '' }}">
                <form method="POST" action="{{ route('settings.account.destroy') }}" id="delete-account-form" class="flex flex-col gap-4" novalidate>
                    @csrf @method('DELETE')
                    <x-ui.field :label="__('auth.fields.password')" for="delete-password" error="password" bag="deleteAccount">
                        <x-ui.input type="password" name="password" id="delete-password" bag="deleteAccount" autocomplete="current-password" required data-autofocus />
                    </x-ui.field>
                    @if ($user->hasTwoFactorEnabled())
                        <x-ui.field :label="__('auth.fields.two_factor_code')" for="delete-code" error="code" bag="deleteAccount" :help="__('app.settings.delete.code_help')">
                            <x-ui.input name="code" id="delete-code" bag="deleteAccount" autocomplete="one-time-code" required :help="true" />
                        </x-ui.field>
                    @endif
                </form>
                <x-slot:footer>
                    <x-ui.button variant="secondary" @click="close()">{{ __('ui.cancel') }}</x-ui.button>
                    <x-ui.button type="submit" form="delete-account-form" variant="danger-solid">{{ __('app.settings.delete.confirm') }}</x-ui.button>
                </x-slot:footer>
            </x-ui.dialog>
        </x-settings-section>
    </div>
</x-layouts.app>
