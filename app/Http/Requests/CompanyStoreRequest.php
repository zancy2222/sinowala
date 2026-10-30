<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && ! $this->user()->membership()->exists();
    }

    public function rules(): array
    {
        return ['company_name' => ['required', 'string', 'min:2', 'max:120']];
    }

    public function attributes(): array
    {
        return ['company_name' => __('auth.register.company_name')];
    }
}
