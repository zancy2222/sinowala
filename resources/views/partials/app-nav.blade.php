{{-- Shared by the desktop sidebar and the phone/tablet menu sheet. --}}
<div class="flex h-full flex-col gap-5">
    <div class="hidden px-2 lg:block">
        <a href="{{ $admin ? route('admin.dashboard') : route('app.home') }}" class="-m-1 inline-block rounded p-1">
            <x-ui.logo :height="28" />
        </a>
    </div>

    <div class="px-2">
        <p class="text-xs font-semibold text-muted">{{ $admin ? __('admin.scope_label') : __('app.settings.company.title') }}</p>
        <p class="mt-0.5 flex items-center gap-2 text-sm font-extrabold">
            <span class="truncate">{{ $scope }}</span>
            @if (! $admin && $company && ! $company->isActive())
                <x-ui.badge>{{ __('app.company.status.'.$company->status) }}</x-ui.badge>
            @endif
        </p>
    </div>

    @unless ($admin)
        <div class="px-2">
            <x-ui.button block icon="calendar-plus" shortcut="N" title="{{ __('app.nav.file') }} ({{ __('ui.shortcut') }}: N)"
                @click="$dispatch('close-dialog', { name: 'app-menu' }); Livewire.dispatch('open-absence-form')">
                {{ __('app.nav.file') }}
            </x-ui.button>
        </div>
    @endunless

    <nav aria-label="{{ __('app.nav.main') }}">
        <ul class="flex flex-col gap-0.5">
            @foreach ($nav as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <li>
                    <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif
                        class="flex min-h-11 items-center gap-3 rounded px-3 text-sm font-semibold transition-colors lg:min-h-9 {{ $active ? 'bg-abo text-tinta' : 'text-muted hover:bg-press hover:text-tinta' }}">
                        <x-ui.icon :name="$item['icon']" size="18" />
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    @if ($admin || $user->is_super_admin)
        <div class="border-t border-line pt-4">
            @if ($admin)
                <a href="{{ route('app.home') }}" class="flex min-h-11 items-center gap-3 rounded px-3 text-sm font-semibold text-muted transition-colors hover:bg-press hover:text-tinta lg:min-h-9">
                    <x-ui.icon name="arrow-left" size="18" /><span>{{ __('app.nav.back_to_app') }}</span>
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="flex min-h-11 items-center gap-3 rounded px-3 text-sm font-semibold text-muted transition-colors hover:bg-press hover:text-tinta lg:min-h-9">
                    <x-ui.icon name="shield-check" size="18" /><span>{{ __('app.nav.admin') }}</span>
                </a>
            @endif
        </div>
    @endif

    <div class="mt-auto flex flex-col gap-1 border-t border-line pt-4">
        <button type="button" x-show="! $store.pwa.installed" x-cloak aria-haspopup="dialog"
            @click="$dispatch('close-dialog', { name: 'app-menu' }); $store.pwa.install()"
            class="flex min-h-11 items-center gap-3 rounded px-3 text-sm font-semibold text-muted transition-colors hover:bg-press hover:text-tinta lg:min-h-9">
            <x-ui.icon name="smartphone" size="18" /><span>{{ __('ui.install.button') }}</span>
        </button>
        <button type="button" @click="$dispatch('close-dialog', { name: 'app-menu' }); $dispatch('open-dialog', { name: 'shortcuts' })"
            class="flex min-h-11 items-center gap-3 rounded px-3 text-sm font-semibold text-muted transition-colors hover:bg-press hover:text-tinta lg:min-h-9"
            aria-keyshortcuts="Shift+?" title="{{ __('app.nav.shortcuts') }} (?)">
            <x-ui.icon name="keyboard" size="18" /><span>{{ __('app.nav.shortcuts') }}</span>
        </button>
        <div class="flex items-center gap-3 px-3 py-2">
            <x-ui.avatar :name="$user->name" size="sm" />
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold">{{ $user->name }}</p>
                <p class="truncate text-xs text-muted">{{ $user->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex min-h-11 w-full items-center gap-3 rounded px-3 text-sm font-semibold text-muted transition-colors hover:bg-press hover:text-tinta lg:min-h-9">
                <x-ui.icon name="log-out" size="18" /><span>{{ __('app.nav.logout') }}</span>
            </button>
        </form>
    </div>
</div>
