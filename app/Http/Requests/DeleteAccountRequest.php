<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class DeleteAccountRequest extends FormRequest
{
    protected $errorBag = 'deleteAccount';

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'current_password:web'],
            'code' => [$this->user()->hasTwoFactorEnabled() ? 'required' : 'nullable', 'string', 'max:32'],
        ];
    }

    public function attributes(): array
    {
        return ['password' => __('auth.fields.password'), 'code' => __('auth.fields.two_factor_code')];
    }

    /** Accept a current authenticator code or one unused recovery code. */
    public function after(): array
    {
        return [function (Validator $validator) {
            $user = $this->user();
            if (! $user->hasTwoFactorEnabled() || $validator->errors()->isNotEmpty()) {
                return;
            }

            $code = str_replace(' ', '', (string) $this->input('code'));
            $valid = app(TwoFactorAuthenticationProvider::class)->verify(decrypt($user->two_factor_secret), $code)
                || in_array($code, $user->recoveryCodes(), true);

            if (! $valid) {
                $validator->errors()->add('code', __('auth.two_factor_invalid'));
            }
        }];
    }
}
