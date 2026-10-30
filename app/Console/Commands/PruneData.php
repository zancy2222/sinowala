<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\ConsentLog;
use App\Models\ContactMessage;
use App\Models\PrivacyRequest;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sinowala:prune')]
#[Description('Retention: burahin ang lumang data ayon sa Privacy Policy.')]
class PruneData extends Command
{
    public function handle(): int
    {
        $days = config('sinowala.retention');

        $counts = [
            'unverified users' => User::whereNull('email_verified_at')
                ->where('is_super_admin', false)
                ->where('created_at', '<', now()->subDays($days['unverified_days']))
                ->get()->each->delete()->count(),
            'contact messages' => ContactMessage::where('created_at', '<', now()->subDays($days['contact_days']))->delete(),
            'audit logs' => AuditLog::where('created_at', '<', now()->subDays($days['audit_days']))->delete(),
            'consent logs' => ConsentLog::where('created_at', '<', now()->subDays($days['consent_days']))->delete(),
            'privacy requests' => PrivacyRequest::where('status', 'done')
                ->where('created_at', '<', now()->subDays($days['privacy_request_days']))->delete(),
        ];

        foreach ($counts as $what => $count) {
            $this->line("Deleted {$count} {$what}.");
        }

        return self::SUCCESS;
    }
}
