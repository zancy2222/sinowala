<x-layouts.auth :title="__('auth.two_factor.title')">
    <div x-data="{ recovery: {{ $errors->has('recovery_code') ? 'true' : 'false' }} }">
        <h1 class="text-2xl font-extrabold">{{ __('auth.two_factor.title') }}</h1>
        <p class="mt-2 text-muted" x-show="!recovery">{{ __('auth.two_factor.subtitle_code') }}</p>
        <p class="mt-2 text-muted" x-show="recovery" x-cloak>{{ __('auth.two_factor.subtitle_recovery') }}</p>

        <form method="POST" action="{{ route('two-factor.login') }}" class="mt-6 flex flex-col gap-4 rounded-lg border border-line bg-papel p-5 sm:p-6" novalidate>
            @csrf
            <div x-show="!recovery">
                <x-ui.field :label="__('auth.fields.two_factor_code')" for="code">
                    <x-ui.input name="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]*" maxlength="6" autofocus x-bind:disabled="recovery" />
                </x-ui.field>
            </div>
            <div x-show="recovery" x-cloak>
                <x-ui.field :label="__('auth.fields.recovery_code')" for="recovery_code">
                    <x-ui.input name="recovery_code" autocomplete="one-time-code" x-bind:disabled="!recovery" />
                </x-ui.field>
            </div>
            <x-ui.button type="submit" block size="lg">{{ __('auth.two_factor.submit') }}</x-ui.button>
            <button type="button" class="link text-sm" @click="recovery = !recovery; $nextTick(() => document.getElementById(recovery ? 'recovery_code' : 'code').focus())">
                <span x-show="!recovery">{{ __('auth.two_factor.use_recovery') }}</span>
                <span x-show="recovery" x-cloak>{{ __('auth.two_factor.use_code') }}</span>
            </button>
        </form>
    </div>
</x-layouts.auth>
