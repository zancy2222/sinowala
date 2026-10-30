<?php

namespace App\Livewire;

use App\Livewire\Forms\AbsenceFormData;
use App\Models\Absence;
use App\Services\AbsenceService;
use App\Support\Fmt;
use App\Support\Today;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/** The one "Mag-file ng lakad" dialog, opened from anywhere in the app (N, buttons, a day). */
class AbsenceForm extends Component
{
    public AbsenceFormData $form;

    /** Set when editing; re-authorized on every action, never trusted on its own. */
    public ?int $absenceId = null;

    public ?string $editingLabel = null;

    #[On('open-absence-form')]
    public function create(?string $date = null): void
    {
        $this->authorize('create', Absence::class);
        $this->resetValidation();
        $this->form->reset();
        $this->absenceId = null;
        $this->editingLabel = null;

        $day = $date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ? $date : Today::date()->toDateString();
        $this->form->from = $day;
        $this->form->to = $day;

        $this->dispatch('open-dialog', name: 'absence');
    }

    #[On('edit-absence')]
    public function edit(int $id): void
    {
        $absence = $this->ownAbsence($id);
        $this->authorize('update', $absence);

        $this->resetValidation();
        $this->absenceId = $absence->id;
        $this->editingLabel = Fmt::date($absence->date);
        $this->form->from = $absence->date->toDateString();
        $this->form->to = $absence->date->toDateString();
        $this->form->part = $absence->part;
        $this->form->note = $absence->note ? (string) $absence->note->note : '';
        $this->form->skipWeekends = false;

        $this->dispatch('open-dialog', name: 'absence');
    }

    /** Look up by id AND owner, so a forged id never reaches someone else's entry. */
    private function ownAbsence(int $id): Absence
    {
        return Absence::query()->where('user_id', auth()->id())->with('note')->findOrFail($id);
    }

    private function throttle(): bool
    {
        $key = 'absence-form:'.auth()->id();
        if (RateLimiter::tooManyAttempts($key, 30)) {
            $this->addError('form.from', __('app.form.too_many'));

            return false;
        }
        RateLimiter::hit($key, 60);

        return true;
    }

    public function save(AbsenceService $service): void
    {
        if (! $this->throttle()) {
            return;
        }

        if ($this->absenceId) {
            $absence = $this->ownAbsence($this->absenceId);
            $this->authorize('update', $absence);
            $this->form->validateOnly('part');
            $this->form->validateOnly('note');
            $service->update($absence, $this->form->part, $this->form->note);
            $message = __('app.form.updated', ['date' => Fmt::date($absence->date)]);
        } else {
            $this->authorize('create', Absence::class);
            $this->form->validate();

            $from = CarbonImmutable::parse($this->form->from, config('app.timezone'));
            $to = CarbonImmutable::parse($this->form->to, config('app.timezone'));
            if ($from->diffInDays($to) + 1 > config('sinowala.max_range_days')) {
                $this->addError('form.to', __('app.form.range_too_long', ['days' => config('sinowala.max_range_days')]));

                return;
            }

            $count = $service->file(auth()->user(), $from, $to, $this->form->part, $this->form->note, $this->form->skipWeekends);
            if ($count === 0) {
                $this->addError('form.to', __('app.form.nothing_saved'));

                return;
            }
            $message = trans_choice('app.form.saved', $count, ['count' => $count, 'range' => Fmt::range($from, $to)]);
        }

        $this->finish($message);
    }

    public function delete(): void
    {
        if (! $this->absenceId || ! $this->throttle()) {
            return;
        }

        $absence = $this->ownAbsence($this->absenceId);
        $this->authorize('delete', $absence);
        $label = Fmt::date($absence->date);
        $absence->delete(); // note cascades

        $this->finish(__('app.form.deleted', ['date' => $label]));
    }

    private function finish(string $message): void
    {
        $this->dispatch('close-dialog', name: 'absence');
        $this->dispatch('absences-changed');
        $this->dispatch('toast', message: $message);
        $this->absenceId = null;
        $this->form->reset();
    }

    public function render(): View
    {
        return view('livewire.absence-form', [
            'singleDay' => $this->form->from !== '' && $this->form->from === $this->form->to,
        ]);
    }
}
