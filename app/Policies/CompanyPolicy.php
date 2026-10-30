<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /** Members of an ACTIVE company can read fellow members' name, date and part. */
    public function viewTeam(User $user, Company $company): bool
    {
        return $company->isActive()
            && ! $user->isSuspended()
            && $user->membership?->company_id === $company->id;
    }

    /** Team page, invites, roles: company admins only (works while pending, too). */
    public function manage(User $user, Company $company): bool
    {
        return ! $user->isSuspended()
            && $user->membership?->company_id === $company->id
            && $user->isCompanyAdmin();
    }

    public function invite(User $user, Company $company): bool
    {
        return $this->manage($user, $company) && $company->isActive();
    }

    public function update(User $user, Company $company): bool
    {
        return $this->manage($user, $company);
    }

    public function delete(User $user, Company $company): bool
    {
        return $this->manage($user, $company);
    }

    // Platform-level actions (Super Admin area).
    public function approve(User $user, Company $company): bool
    {
        return $user->is_super_admin && $company->status !== Company::ACTIVE;
    }

    public function suspend(User $user, Company $company): bool
    {
        return $user->is_super_admin && $company->status === Company::ACTIVE;
    }

    public function destroyAsAdmin(User $user, Company $company): bool
    {
        return $user->is_super_admin;
    }
}
