<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'consent_id', 'version', 'choices', 'ip'])]
class ConsentLog extends Model
{
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return ['choices' => 'array', 'created_at' => 'datetime'];
    }
}
