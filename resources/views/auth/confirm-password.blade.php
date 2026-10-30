<x-layouts.auth :title="__('auth.confirm.title')">
    <h1 class="text-2xl font-extrabold">{{ __('auth.confirm.title') }}</h1>
    <p class="mt-2 text-muted">{{ __('auth.confirm.subtitle') }}</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 flex flex-col gap-4 rounded-lg border border-line bg-papel p-5 sm:p-6" novalidate>
        @csrf
        <x-ui.field :label="__('auth.fields.password')" for="password">
            <x-ui.input type="password" name="password" autocomplete="current-password" required autofocus />
        </x-ui.field>
        <x-ui.button type="submit" block size="lg">{{ __('auth.confirm.submit') }}</x-ui.button>
    </form>
</x-layouts.auth>
