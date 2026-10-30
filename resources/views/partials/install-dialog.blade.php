{{-- Install steps for the device in hand. Android and desktop Chrome usually skip this and show their own prompt. --}}
<x-ui.dialog name="install-app" :title="__('ui.install.title')" :description="__('ui.install.description')" data-install-done="{{ __('ui.install.done') }}">
    @foreach (['ios', 'ios_inapp', 'android', 'android_inapp'] as $key)
        <ol x-show="$store.pwa.platform === @js(str_replace('_', '-', $key))" class="flex flex-col gap-3">
            @foreach (__('ui.install.'.$key) as $step)
                <li class="flex gap-4 rounded-lg border border-line bg-papel p-4">
                    <span class="inline-flex size-11 shrink-0 items-center justify-center rounded-full bg-abo text-tinta"><x-ui.icon :name="$step['icon']" size="20" /></span>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-muted">{{ __('ui.install.step', ['n' => $loop->iteration]) }}</p>
                        <p class="mt-0.5 text-base leading-6 font-extrabold">{{ $step['text'] }}</p>
                        @if ($step['hint'])<p class="mt-1 text-sm leading-5 text-muted">{{ $step['hint'] }}</p>@endif
                    </div>
                </li>
            @endforeach
        </ol>
    @endforeach

    <div x-show="$store.pwa.platform === 'desktop'">
        <p class="text-base leading-7">{{ __('ui.install.desktop_intro') }}</p>
        <div class="mt-4 flex items-center gap-2 rounded-lg border border-line bg-papel p-2 pl-4" x-data="copyButton(@js(url('/')), @js(__('ui.install.copied')))">
            <span class="min-w-0 flex-1 truncate font-extrabold">{{ preg_replace('#^https?://#', '', url('/')) }}</span>
            <x-ui.button size="sm" variant="secondary" icon="copy" @click="copy()">{{ __('ui.install.copy') }}</x-ui.button>
        </div>
        <template x-if="$store.pwa.canPrompt">
            <x-ui.button variant="ghost" icon="monitor" class="mt-4" @click="$store.pwa.install(true)">{{ __('ui.install.desktop_alt') }}</x-ui.button>
        </template>
    </div>
</x-ui.dialog>
