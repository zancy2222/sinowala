<div>
    <x-ui.dialog name="absence" :title="$absenceId ? __('app.form.title_edit') : __('app.form.title_create')" :description="$absenceId ? __('app.form.editing', ['date' => $editingLabel]) : __('app.form.description_create')">
        <form wire:submit="save" id="absence-form" class="flex flex-col gap-5" novalidate x-data="{ confirming: false }" @dialog-closed.window="confirming = false" @confirm-delete-absence.window="confirming = true; $nextTick(() => $el.querySelector('[data-confirm-delete]')?.focus())">
            @unless ($absenceId)
                <div class="grid grid-cols-1 gap-4 min-[420px]:grid-cols-2">
                    <x-ui.field :label="__('app.form.from')" for="absence-from" error="form.from">
                        <x-ui.input type="date" name="form.from" id="absence-from" wire:model.live="form.from" required data-autofocus />
                    </x-ui.field>
                    <x-ui.field :label="__('app.form.to')" for="absence-to" error="form.to">
                        <x-ui.input type="date" name="form.to" id="absence-to" wire:model.live="form.to" :min="$form->from" required />
                    </x-ui.field>
                </div>
            @endunless

            <fieldset class="flex flex-col gap-2" @disabled(! $singleDay && ! $absenceId)>
                <legend class="mb-2 text-sm font-semibold">{{ __('app.form.part') }}</legend>
                @unless ($singleDay || $absenceId)
                    <p class="-mt-1 mb-1 text-sm text-muted" id="part-note">{{ __('app.form.part_range_note') }}</p>
                @endunless
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                    @foreach (['full', 'am', 'pm'] as $p)
                        <x-ui.radio name="form.part" :value="$p" id="absence-part-{{ $p }}" :label="__('app.part.'.$p)" wire:model="form.part" />
                    @endforeach
                </div>
                <p id="absence-part-error" class="text-sm text-danger empty:hidden" aria-live="polite">@error('form.part'){{ $message }}@enderror</p>
            </fieldset>

            <x-ui.field :label="__('app.form.note')" for="absence-note" error="form.note" :help="__('app.form.note_help')" optional>
                <x-ui.textarea name="form.note" id="absence-note" rows="2" maxlength="200" wire:model="form.note" :help="true" placeholder="{{ __('app.form.note_placeholder') }}" />
            </x-ui.field>

            @unless ($singleDay || $absenceId)
                <x-ui.checkbox name="form.skipWeekends" id="absence-skip" wire:model="form.skipWeekends" :label="__('app.form.skip_weekends')" />
            @endunless

            @if ($absenceId)
                <div class="rounded border border-line px-4 py-3" x-show="confirming" x-cloak>
                    <p class="text-sm">{{ __('app.form.delete_confirm', ['date' => $editingLabel]) }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <x-ui.button variant="danger-solid" size="sm" wire:click="delete" loading="delete" data-confirm-delete>{{ __('app.form.delete') }}</x-ui.button>
                        <x-ui.button variant="ghost" size="sm" @click="confirming = false">{{ __('ui.cancel') }}</x-ui.button>
                    </div>
                </div>
            @endif
        </form>

        <x-slot:footer>
            @if ($absenceId)
                <x-ui.button variant="destructive" icon="trash-2" class="sm:mr-auto" @click="$dispatch('confirm-delete-absence')">{{ __('app.form.delete') }}</x-ui.button>
            @endif
            <x-ui.button variant="secondary" @click="close()">{{ __('app.form.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="absence-form" loading="save">{{ $absenceId ? __('app.form.save_edit') : __('app.form.save') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.dialog>
</div>
