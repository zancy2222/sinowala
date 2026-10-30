<?php

namespace App\Http\Requests;

use App\Models\PrivacyRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrivacyRequestForm extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'type' => ['required', Rule::in(PrivacyRequest::TYPES)],
            'details' => ['nullable', 'string', 'max:5000'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function attributes(): array
    {
        return __('web.privacy_request.attributes');
    }
}
