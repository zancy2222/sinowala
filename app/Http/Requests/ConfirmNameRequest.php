<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/** "Type the company name to confirm" for destructive company actions. */
class ConfirmNameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return ['confirm_name' => ['required', 'string', 'max:120']];
    }

    public function attributes(): array
    {
        return ['confirm_name' => __('app.company.confirm_name_label')];
    }

    /** Admin list: each company's dialog reads its own error bag, so it reopens with the message. */
    protected function prepareForValidation(): void
    {
        if ($this->route('company') instanceof Company) {
            $this->errorBag = 'delete_'.$this->route('company')->id;
        }
    }

    /** Same name, ignoring letter case and extra spaces. */
    public function confirms(string $name): bool
    {
        $normalise = fn (string $s) => Str::lower(Str::squish($s));

        return $normalise((string) $this->validated('confirm_name')) === $normalise($name);
    }
}
