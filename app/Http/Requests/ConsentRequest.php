<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'consent_id' => ['required', 'uuid'],
            'analytics' => ['required', 'boolean'],
        ];
    }
}
