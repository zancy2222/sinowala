<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['actor_id', 'action', 'target_type', 'target_id', 'target_label', 'ip'])]
class AuditLog extends Model
{
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /** Record an admin action. Never pass notes, passwords or tokens in the label. */
    public static function record(string $action, ?Model $target = null, ?string $label = null): self
    {
        return self::create([
            'actor_id' => auth()->id(),
            'action' => $action,
            'target_type' => $target ? class_basename($target) : null,
            'target_id' => $target?->getKey(),
            'target_label' => $label,
            'ip' => request()->ip(),
        ]);
    }
}
