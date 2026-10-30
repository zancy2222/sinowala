<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyJoinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && ! $this->user()->membership()->exists();
    }

    public function rules(): array
    {
        return ['code' => ['required', 'string', 'size:6', 'alpha_num']];
    }

    public function attributes(): array
    {
        return ['code' => __('auth.register.code')];
    }
}
