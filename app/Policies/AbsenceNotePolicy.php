<?php

namespace App\Policies;

use App\Models\AbsenceNote;
use App\Models\User;

/** Owner only, no exceptions — not the boss, not the company, not the Super Admin. */
class AbsenceNotePolicy
{
    public function view(User $user, AbsenceNote $note): bool
    {
        return $note->user_id === $user->id;
    }

    public function update(User $user, AbsenceNote $note): bool
    {
        return $note->user_id === $user->id;
    }

    public function delete(User $user, AbsenceNote $note): bool
    {
        return $note->user_id === $user->id;
    }
}
