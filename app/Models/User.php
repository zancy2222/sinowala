<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'terms_version', 'privacy_version', 'accepted_at'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes', 'share_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
            'share_enabled' => 'boolean',
            'shortcuts_enabled' => 'boolean',
            'accepted_at' => 'datetime',
            'last_login_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function company(): ?Company
    {
        return $this->membership?->company;
    }

    /** Company the user may see a team calendar for (active companies only). */
    public function activeCompany(): ?Company
    {
        $company = $this->company();

        return $company?->isActive() ? $company : null;
    }

    public function isCompanyAdmin(): bool
    {
        return $this->membership?->role === Membership::ROLE_ADMIN;
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_secret) && ! is_null($this->two_factor_confirmed_at);
    }

    public function needsPolicyAcceptance(): bool
    {
        return $this->terms_version !== config('sinowala.terms_version')
            || $this->privacy_version !== config('sinowala.privacy_version');
    }

    public function initials(): string
    {
        return Str::of($this->name)->explode(' ')->filter()->take(2)
            ->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))->implode('');
    }

    public function firstName(): string
    {
        return Str::before(trim($this->name), ' ') ?: $this->name;
    }

    public function regenerateShareToken(): void
    {
        $this->forceFill(['share_token' => Str::random(48)])->save();
    }

    /** Super Admin is tied to the configured, verified email. */
    public function syncSuperAdminFlag(): void
    {
        $email = Str::lower((string) config('sinowala.super_admin_email'));
        $should = $email !== '' && Str::lower($this->email) === $email && $this->hasVerifiedEmail();

        if ($should && ! $this->is_super_admin) {
            $this->forceFill(['is_super_admin' => true])->save();
        }
    }
}
