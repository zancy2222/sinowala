<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /** Super Admin suspends/reactivates accounts — never their own. */
    public function suspend(User $actor, User $target): bool
    {
        return $actor->is_super_admin && $actor->id !== $target->id && ! $target->is_super_admin;
    }

    public function reactivate(User $actor, User $target): bool
    {
        return $actor->is_super_admin && $target->isSuspended();
    }

    public function delete(User $actor, User $target): bool
    {
        return $actor->id === $target->id;
    }

    public function update(User $actor, User $target): bool
    {
        return $actor->id === $target->id;
    }
}
