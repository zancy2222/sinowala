<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PolicyAcceptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return ['terms' => ['accepted']];
    }

    public function attributes(): array
    {
        return ['terms' => __('auth.register.terms_short')];
    }
}
