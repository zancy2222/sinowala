<x-layouts.auth :title="__('auth.reset.title')">
    <h1 class="text-2xl font-extrabold">{{ __('auth.reset.title') }}</h1>
    <p class="mt-2 text-muted">{{ __('auth.reset.subtitle') }}</p>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 flex flex-col gap-4 rounded-lg border border-line bg-papel p-5 sm:p-6" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <x-ui.field :label="__('auth.fields.email')" for="email">
            <x-ui.input type="email" name="email" :value="old('email', $request->email)" autocomplete="username" required />
        </x-ui.field>
        <x-ui.field :label="__('auth.fields.new_password')" for="password" :help="__('app.settings.password.rules')">
            <x-ui.input type="password" name="password" autocomplete="new-password" required :help="true" autofocus />
        </x-ui.field>
        <x-ui.field :label="__('auth.fields.password_confirmation')" for="password_confirmation">
            <x-ui.input type="password" name="password_confirmation" autocomplete="new-password" required />
        </x-ui.field>
        <x-ui.button type="submit" block size="lg">{{ __('auth.reset.submit') }}</x-ui.button>
    </form>
</x-layouts.auth>
