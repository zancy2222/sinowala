{{-- Toast region: Livewire `toast` events and session flashes, announced politely. --}}
@props(['initial' => []])
<div x-data="toasts(@js(array_values(array_filter($initial))))" class="pointer-events-none fixed inset-x-0 bottom-0 z-50 flex flex-col items-center gap-2 px-4 safe-bottom sm:items-end sm:pr-6 sm:pb-6" role="status" aria-live="polite">
    <template x-for="t in items" :key="t.id">
        <div class="on-dark pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-lg bg-tinta px-4 py-3 text-sm text-white shadow-overlay">
            <x-ui.icon name="circle-check" size="18" class="mt-0.5" />
            <p class="flex-1" x-text="t.message"></p>
            <button type="button" @click="dismiss(t.id)" class="-my-2 -mr-2 inline-flex size-10 items-center justify-center rounded transition-colors hover:bg-tinta-2">
                <x-ui.icon name="x" size="16" /><span class="sr-only">{{ __('ui.close') }}</span>
            </button>
        </div>
    </template>
</div>
