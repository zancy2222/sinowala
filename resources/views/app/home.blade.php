@php use App\Support\Fmt; use App\Support\Today; @endphp
<x-layouts.app :title="__('app.home.title')" :description="__('app.home.meta')">
    <x-page-header :title="__('app.home.title')" :subtitle="Fmt::date(Today::date())">
        <x-slot:actions>
            @if ($company)
                <x-ui.tabs :label="__('app.home.tabs')" :items="[
                    ['label' => __('app.home.tab_team'), 'href' => route('app.home', ['tab' => 'team']), 'active' => $tab === 'team'],
                    ['label' => __('app.home.tab_ako'), 'href' => route('app.home', ['tab' => 'ako']), 'active' => $tab === 'ako'],
                ]" />
            @endif
            <x-ui.button icon="calendar-plus" shortcut="N" title="{{ __('app.nav.file') }} ({{ __('ui.shortcut') }}: N)" class="w-full sm:w-auto"
                @click="Livewire.dispatch('open-absence-form')">{{ __('app.nav.file') }}</x-ui.button>
        </x-slot:actions>
    </x-page-header>

    @if ($tab === 'team')
        @include('partials.company-status', ['company' => $company])
    @endif

    <div class="flex flex-col gap-8">
        @if ($tab === 'team' && $company->isActive())
            <livewire:today-summary mode="team" />
            <section aria-labelledby="team-cal-heading" class="flex flex-col gap-2">
                <h2 id="team-cal-heading" class="sr-only">{{ __('app.calendar.team_title') }}</h2>
                <livewire:calendar scope="team" />
            </section>
        @else
            <livewire:today-summary mode="ako" />
            <section aria-labelledby="my-cal-heading" class="flex flex-col gap-2">
                <h2 id="my-cal-heading" class="sr-only">{{ __('app.calendar.my_title') }}</h2>
                <livewire:calendar scope="me" />
            </section>
        @endif
    </div>
</x-layouts.app>
