<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['date', 'part'])]
class Absence extends Model
{
    public const PARTS = ['full', 'am', 'pm'];

    protected function casts(): array
    {
        return ['date' => 'date:Y-m-d'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Owner-only. Never eager-load this outside the owner's own views. */
    public function note(): HasOne
    {
        return $this->hasOne(AbsenceNote::class);
    }

    public function partLabel(): string
    {
        return __('app.part.'.$this->part);
    }
}
