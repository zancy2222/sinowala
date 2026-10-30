{{-- Banner on company features while the company is pending or suspended. --}}
@if ($company && $company->isPending())
    <x-ui.alert tone="pending" :title="__('app.calendar.pending_title')" class="mb-6">
        {{ __('app.calendar.pending_body', ['company' => $company->name]) }}
    </x-ui.alert>
@elseif ($company && $company->status === 'suspended')
    <x-ui.alert tone="danger" :title="__('app.calendar.suspended_title', ['company' => $company->name])" class="mb-6">
        {{ __('app.calendar.suspended_body') }}
    </x-ui.alert>
@endif
