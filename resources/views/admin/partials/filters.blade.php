{{-- GET filter bar shared by admin lists. $statuses = [value => label] (optional) --}}
<form method="GET" class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end" role="search">
    <div class="flex flex-1 flex-col gap-2 sm:max-w-sm">
        <label for="q" class="text-sm font-semibold">{{ $searchLabel }}</label>
        <div class="relative">
            <x-ui.icon name="search" size="18" class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-muted" />
            <input id="q" name="q" type="search" value="{{ request('q') }}" maxlength="100" class="block min-h-11 w-full rounded border border-line-strong bg-papel py-2 pr-3 pl-10 text-base transition-colors hover:border-tinta">
        </div>
    </div>
    @isset($statuses)
        <div class="flex flex-col gap-2 sm:w-56">
            <label for="status" class="text-sm font-semibold">{{ __('admin.companies.status_filter') }}</label>
            <x-ui.select name="status">
                <option value="">{{ __('admin.companies.all_statuses') }}</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </x-ui.select>
        </div>
    @endisset
    <div class="flex gap-2">
        <x-ui.button type="submit" variant="secondary" icon="search">{{ __('ui.filter') }}</x-ui.button>
        @if (request()->hasAny(['q', 'status']))
            <x-ui.button :href="url()->current()" variant="ghost">{{ __('ui.clear') }}</x-ui.button>
        @endif
    </div>
</form>
