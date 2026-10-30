@php use App\Support\Fmt; @endphp
<x-layouts.app :title="__('admin.users.title')" admin>
    <x-page-header :title="__('admin.users.title')" :subtitle="__('admin.users.subtitle')" />

    @include('admin.partials.filters', ['searchLabel' => __('admin.users.search'), 'statuses' => __('admin.users.status')])

    <div class="rounded-lg border border-line bg-papel">
        @if ($users->isEmpty())
            <p class="p-6 text-sm text-muted">{{ __('admin.users.empty') }}</p>
        @else
            <x-ui.table :caption="__('admin.users.title')" min-width="min-w-[52rem]">
                <thead>
                    <tr>
                        <th scope="col" class="w-1/3">{{ __('admin.users.col_name') }}</th>
                        <th scope="col">{{ __('admin.users.col_status') }}</th>
                        <th scope="col">{{ __('admin.users.col_company') }}</th>
                        <th scope="col" class="text-right">{{ __('admin.users.col_entries') }}</th>
                        <th scope="col" class="text-right">{{ __('admin.users.col_joined') }}</th>
                        <th scope="col" class="text-right">{{ __('admin.users.col_last_login') }}</th>
                        <th scope="col"><span class="sr-only">{{ __('admin.companies.col_actions') }}</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            <td>
                                <p class="font-semibold">{{ $u->name }} @if ($u->is_super_admin)<x-ui.badge tone="dark" class="ml-1">{{ __('admin.users.super') }}</x-ui.badge>@endif</p>
                                <p class="text-muted">{{ $u->email }}@unless ($u->email_verified_at) · {{ __('admin.users.unverified') }}@endunless</p>
                            </td>
                            <td><x-ui.badge :tone="$u->isSuspended() ? 'danger' : 'success'">{{ __('admin.users.status.'.$u->status) }}</x-ui.badge></td>
                            <td>{{ $u->membership?->company?->name ?? __('admin.users.personal') }}</td>
                            <td class="text-right" data-num>{{ $u->absences_count }}</td>
                            <td class="text-right whitespace-nowrap" data-num>{{ $u->created_at->format('Y-m-d') }}</td>
                            <td class="text-right whitespace-nowrap" data-num>{{ $u->last_login_at?->format('Y-m-d') ?? '—' }}</td>
                            <td class="text-right">
                                @can('suspend', $u)
                                    @if ($u->isSuspended())
                                        <form method="POST" action="{{ route('admin.users.reactivate', $u) }}">@csrf<x-ui.button type="submit" variant="secondary" size="sm">{{ __('admin.users.reactivate') }}<span class="sr-only">: {{ $u->name }}</span></x-ui.button></form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.suspend', $u) }}">@csrf<x-ui.button type="submit" variant="destructive" size="sm">{{ __('admin.users.suspend') }}<span class="sr-only">: {{ $u->name }}</span></x-ui.button></form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
        @endif
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</x-layouts.app>
