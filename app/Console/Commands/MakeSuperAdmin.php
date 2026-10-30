<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sinowala:make-super-admin {email : Email of an existing account}')]
#[Description('Gawing Super Admin ang isang existing na account (fallback kapag hindi tugma ang SUPER_ADMIN_EMAIL).')]
class MakeSuperAdmin extends Command
{
    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Walang account na may ganyang email. Mag-sign up muna sa website.');

            return self::FAILURE;
        }

        $user->forceFill([
            'is_super_admin' => true,
            'status' => 'active',
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $this->info("Super Admin na si {$user->email}.");
        if (! $user->hasTwoFactorEnabled()) {
            $this->warn('Kailangan munang i-on ang two-factor authentication sa Settings bago bumukas ang /admin.');
        }

        return self::SUCCESS;
    }
}
