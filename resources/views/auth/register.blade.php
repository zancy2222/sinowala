@php
    $path = old('path', in_array(request('path'), ['personal', 'company', 'join'], true) ? request('path') : 'personal');
    $hasInvite = (bool) session('join_token');
@endphp
<x-layouts.auth :title="__('auth.register.title')" :description="__('auth.register.meta')" wide>
    <h1 class="text-2xl font-extrabold">{{ __('auth.register.title') }}</h1>
    <p class="mt-2 text-lg">{{ __('auth.register.lead') }}</p>
    <p class="mt-2 text-muted">{{ __('auth.register.subtitle') }} <a href="{{ route('login') }}" class="link font-semibold">{{ __('auth.register.have_account') }}</a></p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 flex flex-col gap-6 rounded-lg border border-line bg-papel p-5 sm:p-6" novalidate x-data="{ path: @js($path) }">
        @csrf
        <fieldset class="flex flex-col gap-2">
            <legend class="mb-2 text-sm font-semibold">{{ __('auth.register.path_legend') }}</legend>
            <x-ui.radio name="path" value="personal" :label="__('auth.register.path_personal')" :description="__('auth.register.path_personal_desc')" x-model="path" :checked="$path === 'personal'" />
            <x-ui.radio name="path" value="company" :label="__('auth.register.path_company')" :description="__('auth.register.path_company_desc')" x-model="path" :checked="$path === 'company'" />
            <x-ui.radio name="path" value="join" :label="__('auth.register.path_join')" :description="__('auth.register.path_join_desc')" x-model="path" :checked="$path === 'join'" />
            @error('path')<p class="text-sm text-danger" role="alert">{{ $message }}</p>@enderror
        </fieldset>

        <div x-show="path === 'company'" @if ($path !== 'company') x-cloak @endif>
            <x-ui.field :label="__('auth.register.company_name')" for="company_name" :help="__('auth.register.company_help')">
                <x-ui.input name="company_name" :value="old('company_name')" autocomplete="organization" maxlength="120" :help="true" x-bind:required="path === 'company'" />
            </x-ui.field>
        </div>

        <div x-show="path === 'join'" @if ($path !== 'join') x-cloak @endif>
            @if ($hasInvite)
                <x-ui.alert icon="link">{{ __('auth.register.invite_ready') }}</x-ui.alert>
            @endif
            <x-ui.field :label="__('auth.register.code')" for="code" :help="$hasInvite ? __('auth.register.code_optional') : __('auth.register.code_help')" :class="$hasInvite ? 'mt-4' : ''">
                <x-ui.input name="code" :value="old('code')" maxlength="6" autocomplete="off" autocapitalize="characters" spellcheck="false" class="uppercase tracking-[0.2em]" :help="true" />
            </x-ui.field>
        </div>

        <div class="flex flex-col gap-4 pt-2">
            <x-ui.field :label="__('auth.fields.name')" for="name" :help="__('auth.register.name_help')">
                <x-ui.input name="name" :value="old('name')" autocomplete="name" maxlength="80" required :help="true" />
            </x-ui.field>
            <x-ui.field :label="__('auth.fields.email')" for="email">
                <x-ui.input type="email" name="email" :value="old('email')" autocomplete="email" required />
            </x-ui.field>
            <x-ui.field :label="__('auth.fields.password')" for="password" :help="__('app.settings.password.rules')">
                <x-ui.input type="password" name="password" autocomplete="new-password" minlength="8" required :help="true" />
            </x-ui.field>
            <x-ui.field :label="__('auth.fields.password_confirmation')" for="password_confirmation">
                <x-ui.input type="password" name="password_confirmation" autocomplete="new-password" required />
            </x-ui.field>
        </div>

        <div class="flex flex-col gap-1">
            <label for="terms" class="flex min-h-11 cursor-pointer items-start gap-3 py-2">
                <input type="checkbox" name="terms" id="terms" value="1" @checked(old('terms')) required
                    aria-describedby="terms-error" @error('terms') aria-invalid="true" @enderror class="mt-0.5 size-5 shrink-0 cursor-pointer accent-tinta">
                <span class="text-base">
                    {{ __('auth.register.terms_prefix') }}
                    <a href="{{ route('terms') }}" target="_blank" rel="noopener" class="link">{{ __('web.footer.links.terms') }}</a>
                    {{ __('auth.register.terms_and') }}
                    <a href="{{ route('privacy') }}" target="_blank" rel="noopener" class="link">{{ __('web.footer.links.privacy') }}</a>.
                </span>
            </label>
            <p id="terms-error" class="text-sm text-danger empty:hidden" aria-live="polite">@error('terms'){{ $message }}@enderror</p>
        </div>

        <x-ui.button type="submit" block size="lg">{{ __('auth.register.submit') }}</x-ui.button>
    </form>
</x-layouts.auth>
