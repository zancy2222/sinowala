<x-layouts.auth :title="$company ? __('app.join.title', ['company' => $company->name]) : __('app.join.invalid_title')">
    @if (! $company)
        <h1 class="text-2xl font-extrabold">{{ __('app.join.invalid_title') }}</h1>
        <p class="mt-2">{{ __('app.join.invalid_body') }}</p>
        <div class="mt-6 flex flex-wrap gap-2">
            @auth
                <x-ui.button :href="route('settings').'#company'">{{ __('app.settings.company.join_title') }}</x-ui.button>
            @else
                <x-ui.button :href="route('register', ['path' => 'join'])">{{ __('auth.register.path_join') }}</x-ui.button>
            @endauth
            <x-ui.button :href="route('home')" variant="secondary">{{ __('ui.back_home') }}</x-ui.button>
        </div>
    @else
        <h1 class="text-2xl font-extrabold">{{ __('app.join.title', ['company' => $company->name]) }}</h1>
        <p class="mt-2">{{ __('app.join.body', ['company' => $company->name]) }}</p>
        @if ($alreadyMember)
            <x-ui.alert class="mt-6">{{ __('app.join.already') }}</x-ui.alert>
            <x-ui.button :href="route('settings').'#company'" variant="secondary" class="mt-4">{{ __('app.nav.settings') }}</x-ui.button>
        @else
            <form method="POST" action="{{ route('join.accept', $token) }}" class="mt-6">
                @csrf
                <x-ui.button type="submit" block size="lg" icon="user-plus">{{ __('app.join.button', ['company' => $company->name]) }}</x-ui.button>
            </form>
        @endif
    @endif
</x-layouts.auth>
