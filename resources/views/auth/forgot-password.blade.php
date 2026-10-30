<x-layouts.auth :title="__('auth.forgot.title')">
    <h1 class="text-2xl font-extrabold">{{ __('auth.forgot.title') }}</h1>
    <p class="mt-2 text-muted">{{ __('auth.forgot.subtitle') }}</p>

    @if (session('status'))
        <x-ui.alert tone="success" class="mt-6">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 flex flex-col gap-4 rounded-lg border border-line bg-papel p-5 sm:p-6" novalidate>
        @csrf
        <x-ui.field :label="__('auth.fields.email')" for="email">
            <x-ui.input type="email" name="email" :value="old('email')" autocomplete="email" required autofocus />
        </x-ui.field>
        <x-ui.button type="submit" block size="lg">{{ __('auth.forgot.submit') }}</x-ui.button>
    </form>
    <p class="mt-4 text-center"><a href="{{ route('login') }}" class="link text-sm">{{ __('auth.forgot.back') }}</a></p>
</x-layouts.auth>
