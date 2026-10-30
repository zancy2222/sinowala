{{-- "I-install sa phone". It needs script, so it starts hidden, and it stays hidden inside the installed app. --}}
@props(['variant' => 'secondary', 'size' => 'md', 'block' => false])
<x-ui.button :variant="$variant" :size="$size" :block="$block" icon="smartphone" aria-haspopup="dialog"
    x-show="! $store.pwa.installed" x-cloak @click="$store.pwa.install()" {{ $attributes }}>
    {{ $slot->isEmpty() ? __('ui.install.button') : $slot }}
</x-ui.button>
