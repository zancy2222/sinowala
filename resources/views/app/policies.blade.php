<x-layouts.auth :title="__('app.policies.title')">
    <h1 class="text-2xl font-extrabold">{{ __('app.policies.title') }}</h1>
    <p class="mt-2 text-base">{{ __('app.policies.body') }}</p>
    <ul class="mt-4 flex flex-col gap-1">
        <li><a href="{{ route('terms') }}" target="_blank" rel="noopener" class="link">{{ __('web.footer.links.terms') }} (v{{ config('sinowala.terms_version') }})</a></li>
        <li><a href="{{ route('privacy') }}" target="_blank" rel="noopener" class="link">{{ __('web.footer.links.privacy') }} (v{{ config('sinowala.privacy_version') }})</a></li>
    </ul>
    <form method="POST" action="{{ route('policies.accept') }}" class="mt-6 flex flex-col gap-4 rounded-lg border border-line bg-papel p-5 sm:p-6" novalidate>
        @csrf
        <x-ui.checkbox name="terms" :label="__('auth.register.terms')" required />
        <x-ui.button type="submit" block>{{ __('app.policies.accept') }}</x-ui.button>
    </form>
    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="link text-sm">{{ __('app.nav.logout') }}</button>
    </form>
</x-layouts.auth>
