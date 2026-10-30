<x-layouts.auth :title="__('auth.login.title')" :description="__('auth.login.meta')">
    <h1 class="text-2xl font-extrabold">{{ __('auth.login.title') }}</h1>
    <p class="mt-2 text-muted">{{ __('auth.login.subtitle') }} <a href="{{ route('register') }}" class="link font-semibold">{{ __('auth.login.no_account') }}</a></p>

    @if (session('status'))
        <x-ui.alert tone="success" class="mt-6">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-6 flex flex-col gap-4 rounded-lg border border-line bg-papel p-5 sm:p-6" novalidate>
        @csrf
        <x-ui.field :label="__('auth.fields.email')" for="email">
            <x-ui.input type="email" name="email" :value="old('email')" autocomplete="username" required autofocus />
        </x-ui.field>
        <x-ui.field :label="__('auth.fields.password')" for="password">
            <x-ui.input type="password" name="password" autocomplete="current-password" required />
        </x-ui.field>
        <div class="flex flex-wrap items-center justify-between gap-2">
            <x-ui.checkbox name="remember" :label="__('auth.login.remember')" :checked="(bool) old('remember')" />
            <a href="{{ route('password.request') }}" class="link text-sm">{{ __('auth.login.forgot') }}</a>
        </div>
        <x-ui.button type="submit" block size="lg">{{ __('auth.login.submit') }}</x-ui.button>
    </form>
</x-layouts.auth>
