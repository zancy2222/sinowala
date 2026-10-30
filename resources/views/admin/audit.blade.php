<x-layouts.app :title="__('admin.audit.title')" admin>
    <x-page-header :title="__('admin.audit.title')" :subtitle="__('admin.audit.subtitle')" />

    @include('admin.partials.filters', ['searchLabel' => __('admin.audit.search')])

    <div class="rounded-lg border border-line bg-papel">
        @if ($logs->isEmpty())
            <p class="p-6 text-sm text-muted">{{ __('admin.audit.empty') }}</p>
        @else
            <x-ui.table :caption="__('admin.audit.title')">
                <thead>
                    <tr>
                        <th scope="col" class="w-44">{{ __('admin.audit.col_when') }}</th>
                        <th scope="col">{{ __('admin.audit.col_who') }}</th>
                        <th scope="col">{{ __('admin.audit.col_action') }}</th>
                        <th scope="col">{{ __('admin.audit.col_target') }}</th>
                        <th scope="col" class="text-right">{{ __('admin.audit.col_ip') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap" data-num>{{ $log->created_at->setTimezone(config('app.timezone'))->format('Y-m-d H:i') }}</td>
                            <td>{{ $log->actor?->name ?? __('admin.audit.deleted_user') }}</td>
                            <td>{{ __('admin.audit.actions.'.$log->action) }}</td>
                            <td><span class="text-muted">{{ $log->target_type }} #{{ $log->target_id }}</span> {{ $log->target_label }}</td>
                            <td class="text-right" data-num>{{ $log->ip }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
        @endif
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
</x-layouts.app>
