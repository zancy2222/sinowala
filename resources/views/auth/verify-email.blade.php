<x-layouts.auth :title="__('auth.verify.title')">
    <h1 class="text-2xl font-extrabold">{{ __('auth.verify.title') }}</h1>
    <p class="mt-2">{{ __('auth.verify.body', ['email' => auth()->user()->email]) }}</p>

    @if (session('status') === 'verification-link-sent')
        <x-ui.alert tone="success" class="mt-6">{{ __('auth.verify.sent') }}</x-ui.alert>
    @endif

    <div class="mt-6 flex flex-col gap-3 rounded-lg border border-line bg-papel p-5 sm:p-6">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-ui.button type="submit" block>{{ __('auth.verify.resend') }}</x-ui.button>
        </form>
        <p class="text-sm text-muted">{{ __('auth.verify.wrong_email') }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-ui.button type="submit" variant="secondary" block>{{ __('app.nav.logout') }}</x-ui.button>
        </form>
    </div>
</x-layouts.auth>
