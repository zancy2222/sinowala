<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $company = $this->user()?->company();

        return $company && $this->user()->can('update', $company);
    }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'min:2', 'max:120']];
    }

    public function attributes(): array
    {
        return ['name' => __('auth.register.company_name')];
    }
}
