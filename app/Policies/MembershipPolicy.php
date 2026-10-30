<?php

namespace App\Policies;

use App\Models\Membership;
use App\Models\User;

class MembershipPolicy
{
    private function managesSameCompany(User $user, Membership $membership): bool
    {
        return ! $user->isSuspended()
            && $user->isCompanyAdmin()
            && $user->membership?->company_id === $membership->company_id;
    }

    public function remove(User $user, Membership $membership): bool
    {
        return $this->managesSameCompany($user, $membership) && $membership->user_id !== $user->id;
    }

    public function promote(User $user, Membership $membership): bool
    {
        return $this->managesSameCompany($user, $membership) && $membership->role === Membership::ROLE_MEMBER;
    }

    /** A company always keeps at least one admin. */
    public function demote(User $user, Membership $membership): bool
    {
        return $this->managesSameCompany($user, $membership)
            && $membership->role === Membership::ROLE_ADMIN
            && $membership->company->adminCount() > 1;
    }

    public function leave(User $user, Membership $membership): bool
    {
        return $membership->user_id === $user->id;
    }
}
