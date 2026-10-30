<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\CompanyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * Sign-up with three paths:
 *  personal — "Para sa sarili ko": active immediately.
 *  company  — "Para sa kumpanya ko": creates a pending company, user becomes its admin.
 *  join     — "Sumali sa kumpanya": joins an active company by invite link or 6-character code.
 */
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(private CompanyService $companies) {}

    public function create(array $input): User
    {
        $inviteToken = session('join_token');

        Validator::make($input, [
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => $this->passwordRules(),
            'path' => ['required', Rule::in(['personal', 'company', 'join'])],
            'company_name' => ['exclude_unless:path,company', 'required', 'string', 'min:2', 'max:120'],
            'code' => ['exclude_unless:path,join', Rule::requiredIf(! $inviteToken), 'nullable', 'string', 'max:12'],
            'terms' => ['accepted'],
        ], [], [
            'company_name' => __('auth.register.company_name'),
            'code' => __('auth.register.code'),
            'terms' => __('auth.register.terms_short'),
        ])->validate();

        $company = null;
        if ($input['path'] === 'join') {
            $company = filled($input['code'] ?? null)
                ? $this->companies->findJoinable(code: $input['code'])
                : $this->companies->findJoinable(token: $inviteToken);

            if (! $company) {
                throw ValidationException::withMessages(['code' => __('app.company.invalid_code')]);
            }
        }

        return DB::transaction(function () use ($input, $company) {
            $user = User::create([
                'name' => trim($input['name']),
                'email' => $input['email'],
                'password' => $input['password'],
                'terms_version' => config('sinowala.terms_version'),
                'privacy_version' => config('sinowala.privacy_version'),
                'accepted_at' => now(),
            ]);

            if ($input['path'] === 'company') {
                $this->companies->create($user, $input['company_name']);
            } elseif ($company) {
                $this->companies->join($user, $company);
                session()->forget('join_token');
            }

            return $user;
        });
    }
}
