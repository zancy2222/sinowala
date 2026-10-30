@php use App\Support\Fmt; @endphp
<x-layouts.app :title="__('admin.companies.title')" admin>
    <x-page-header :title="__('admin.companies.title')" :subtitle="__('admin.companies.subtitle')" />

    @include('admin.partials.filters', ['searchLabel' => __('admin.companies.search'), 'statuses' => __('app.company.status')])

    <div class="rounded-lg border border-line bg-papel">
        @if ($companies->isEmpty())
            <p class="p-6 text-sm text-muted">{{ __('admin.companies.empty') }}</p>
        @else
            <x-ui.table :caption="__('admin.companies.title')">
                <thead>
                    <tr>
                        <th scope="col" class="w-2/5">{{ __('admin.companies.col_name') }}</th>
                        <th scope="col">{{ __('admin.companies.col_status') }}</th>
                        <th scope="col" class="text-right">{{ __('admin.companies.col_members') }}</th>
                        <th scope="col" class="text-right">{{ __('admin.companies.col_created') }}</th>
                        <th scope="col" class="text-right">{{ __('admin.companies.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $c)
                        <tr>
                            <td class="font-semibold">{{ $c->name }}</td>
                            <td><x-ui.badge :tone="$c->status === 'active' ? 'success' : ($c->status === 'suspended' ? 'danger' : 'neutral')">{{ __('app.company.status.'.$c->status) }}</x-ui.badge></td>
                            <td class="text-right" data-num>{{ $c->memberships_count }}</td>
                            <td class="text-right whitespace-nowrap" data-num>{{ $c->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    @if ($c->status === 'pending')
                                        <form method="POST" action="{{ route('admin.companies.approve', $c) }}">@csrf<x-ui.button type="submit" size="sm">{{ __('admin.companies.approve') }}<span class="sr-only">: {{ $c->name }}</span></x-ui.button></form>
                                    @elseif ($c->status === 'active')
                                        <form method="POST" action="{{ route('admin.companies.suspend', $c) }}">@csrf<x-ui.button type="submit" variant="secondary" size="sm">{{ __('admin.companies.suspend') }}<span class="sr-only">: {{ $c->name }}</span></x-ui.button></form>
                                    @else
                                        <form method="POST" action="{{ route('admin.companies.reactivate', $c) }}">@csrf<x-ui.button type="submit" variant="secondary" size="sm">{{ __('admin.companies.reactivate') }}<span class="sr-only">: {{ $c->name }}</span></x-ui.button></form>
                                    @endif
                                    <x-ui.button variant="ghost" size="sm" icon="trash-2" class="text-danger" @click="$dispatch('open-dialog', { name: 'del-{{ $c->id }}' })">{{ __('admin.companies.delete') }}<span class="sr-only">: {{ $c->name }}</span></x-ui.button>
                                </div>
                                @php $bag = 'delete_'.$c->id; @endphp
                                <x-ui.dialog :name="'del-'.$c->id" :title="__('admin.companies.delete_title', ['name' => $c->name])" size="sm"
                                    x-init="{{ $errors->getBag($bag)->any() ? '$nextTick(() => open())' : '' }}">
                                    <p class="mb-4">{{ __('admin.companies.delete_body', ['count' => $c->memberships_count]) }}</p>
                                    <form method="POST" action="{{ route('admin.companies.destroy', $c) }}" id="del-form-{{ $c->id }}" novalidate>
                                        @csrf @method('DELETE')
                                        <x-ui.field :label="__('app.company.confirm_name_label').': '.$c->name" :for="'confirm-'.$c->id" error="confirm_name" :bag="$bag">
                                            <x-ui.input name="confirm_name" :id="'confirm-'.$c->id" error-key="confirm_name" :bag="$bag" autocomplete="off" required data-autofocus />
                                        </x-ui.field>
                                    </form>
                                    <x-slot:footer>
                                        <x-ui.button variant="secondary" @click="close()">{{ __('ui.cancel') }}</x-ui.button>
                                        <x-ui.button type="submit" :form="'del-form-'.$c->id" variant="danger-solid">{{ __('admin.companies.delete') }}</x-ui.button>
                                    </x-slot:footer>
                                </x-ui.dialog>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
        @endif
    </div>
    <div class="mt-4">{{ $companies->links() }}</div>
</x-layouts.app>
