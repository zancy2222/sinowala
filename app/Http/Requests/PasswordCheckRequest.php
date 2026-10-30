<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Re-enter the current password (log out other sessions). */
class PasswordCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return ['password' => ['required', 'string', 'current_password:web']];
    }

    public function attributes(): array
    {
        return ['password' => __('auth.fields.password')];
    }
}
