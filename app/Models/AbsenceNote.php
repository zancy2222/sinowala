<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['note'])]
#[Hidden(['note'])]
class AbsenceNote extends Model
{
    protected function casts(): array
    {
        return ['note' => 'encrypted'];
    }

    public function absence(): BelongsTo
    {
        return $this->belongsTo(Absence::class);
    }
}
