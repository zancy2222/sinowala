<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompanyService
{
    public function create(User $user, string $name): Company
    {
        if ($user->membership()->exists()) {
            throw ValidationException::withMessages(['company_name' => __('app.company.already_member')]);
        }

        return DB::transaction(function () use ($user, $name) {
            $company = Company::createWithCodes(trim($name));
            Membership::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'role' => Membership::ROLE_ADMIN,
                'joined_at' => now(),
            ]);
            $user->unsetRelation('membership');

            return $company;
        });
    }

    /** Only ACTIVE companies accept new members. Callers show one generic error either way. */
    public function findJoinable(?string $code = null, ?string $token = null): ?Company
    {
        $query = Company::query()->where('status', Company::ACTIVE);
        if ($token !== null) {
            return $query->where('invite_token', $token)->first();
        }

        return $query->where('code', strtoupper(trim((string) $code)))->first();
    }

    public function join(User $user, Company $company): void
    {
        if ($user->membership()->exists()) {
            throw ValidationException::withMessages(['code' => __('app.company.already_member')]);
        }

        Membership::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'role' => Membership::ROLE_MEMBER,
            'joined_at' => now(),
        ]);
        $user->unsetRelation('membership');
    }

    /**
     * Leave the company. The personal calendar always stays with the user.
     * The last admin must promote someone first; the last member removes the company.
     */
    public function leave(User $user): void
    {
        $membership = $user->membership;
        if (! $membership) {
            return;
        }
        $company = $membership->company;
        $memberCount = $company->memberships()->count();

        if ($memberCount > 1 && $membership->role === Membership::ROLE_ADMIN && $company->adminCount() === 1) {
            throw ValidationException::withMessages(['company' => __('app.company.last_admin')]);
        }

        DB::transaction(function () use ($membership, $company, $memberCount) {
            $membership->delete();
            if ($memberCount === 1) {
                $company->delete();
            }
        });
        $user->unsetRelation('membership');
    }

    /** Memberships cascade; members become personal users. Calendars are untouched. */
    public function delete(Company $company): void
    {
        $company->delete();
    }
}
