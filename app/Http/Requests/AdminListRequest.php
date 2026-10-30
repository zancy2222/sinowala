<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Search, filter and page parameters for admin lists. */
class AdminListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_super_admin;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'in:pending,active,suspended,open,done,unread'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
