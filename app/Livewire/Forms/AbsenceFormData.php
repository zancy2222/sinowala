<?php

namespace App\Livewire\Forms;

use App\Support\Today;
use Livewire\Form;

/** Validation for "Mag-file ng lakad" (the Livewire equivalent of a Form Request). */
class AbsenceFormData extends Form
{
    public string $from = '';

    public string $to = '';

    public string $part = 'full';

    public string $note = '';

    public bool $skipWeekends = false;

    public function rules(): array
    {
        $min = Today::date()->subYear()->toDateString();
        $max = Today::date()->addYears(2)->toDateString();

        return [
            'from' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.$min, 'before_or_equal:'.$max],
            'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from', 'before_or_equal:'.$max],
            'part' => ['required', 'in:full,am,pm'],
            'note' => ['nullable', 'string', 'max:200'],
            'skipWeekends' => ['boolean'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'from' => __('app.form.from'),
            'to' => __('app.form.to'),
            'part' => __('app.form.part'),
            'note' => __('app.form.note'),
        ];
    }

    public function messages(): array
    {
        return [
            'to.after_or_equal' => __('app.form.to_after'),
        ];
    }
}
