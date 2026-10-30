<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name'])]
class Company extends Model
{
    public const PENDING = 'pending';

    public const ACTIVE = 'active';

    public const SUSPENDED = 'suspended';

    // No 0/O/1/I/L so codes can be read aloud and typed without mistakes.
    private const CODE_ALPHABET = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

    protected function casts(): array
    {
        return ['approved_at' => 'datetime'];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memberships')->withPivot('role', 'joined_at');
    }

    public function isActive(): bool
    {
        return $this->status === self::ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    public function adminCount(): int
    {
        return $this->memberships()->where('role', Membership::ROLE_ADMIN)->count();
    }

    public static function createWithCodes(string $name): self
    {
        $company = new self(['name' => $name]);
        $company->status = self::PENDING;
        $company->assignFreshCodes();

        return $company;
    }

    /** New code and invite link; the old ones stop working immediately. */
    public function assignFreshCodes(): void
    {
        do {
            $code = collect(range(1, 6))
                ->map(fn () => self::CODE_ALPHABET[random_int(0, strlen(self::CODE_ALPHABET) - 1)])
                ->implode('');
        } while (self::where('code', $code)->exists());

        $this->code = $code;
        $this->invite_token = Str::random(40);
        $this->save();
    }

    public function inviteUrl(): string
    {
        return route('join.link', $this->invite_token);
    }
}
