<x-layouts.app :title="__('app.calendar.team_title')" :description="__('app.calendar.team_meta', ['company' => $company->name])">
    <x-page-header :title="__('app.calendar.team_title')" :subtitle="__('app.calendar.team_meta', ['company' => $company->name])">
        <x-slot:actions>
            <x-ui.button icon="calendar-plus" shortcut="N" title="{{ __('app.nav.file') }} ({{ __('ui.shortcut') }}: N)" class="w-full sm:w-auto"
                @click="Livewire.dispatch('open-absence-form')">{{ __('app.nav.file') }}</x-ui.button>
        </x-slot:actions>
    </x-page-header>

    @include('partials.company-status', ['company' => $company])

    @if ($company->isActive())
        <livewire:calendar scope="team" />
    @endif
</x-layouts.app>
