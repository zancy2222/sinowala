<?php

namespace App\Policies;

use App\Models\Absence;
use App\Models\User;

/** A user reads and writes only their own absences. */
class AbsencePolicy
{
    public function viewAny(User $user): bool
    {
        return ! $user->isSuspended();
    }

    public function create(User $user): bool
    {
        return ! $user->isSuspended();
    }

    public function view(User $user, Absence $absence): bool
    {
        return $absence->user_id === $user->id;
    }

    public function update(User $user, Absence $absence): bool
    {
        return $absence->user_id === $user->id && ! $user->isSuspended();
    }

    public function delete(User $user, Absence $absence): bool
    {
        return $absence->user_id === $user->id && ! $user->isSuspended();
    }
}
