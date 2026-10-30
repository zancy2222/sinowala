<x-layouts.app :title="__('app.calendar.my_title')" :description="__('app.calendar.my_meta')">
    <x-page-header :title="__('app.calendar.my_title')" :subtitle="__('app.calendar.my_meta')">
        <x-slot:actions>
            <x-ui.button icon="calendar-plus" shortcut="N" title="{{ __('app.nav.file') }} ({{ __('ui.shortcut') }}: N)" class="w-full sm:w-auto"
                @click="Livewire.dispatch('open-absence-form')">{{ __('app.nav.file') }}</x-ui.button>
        </x-slot:actions>
    </x-page-header>

    <livewire:calendar scope="me" />
</x-layouts.app>
