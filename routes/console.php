<?php

use Illuminate\Support\Facades\Schedule;

/*
| One cron entry runs all of this:
|   * * * * * cd /path/to/sinowala && php artisan schedule:run >> /dev/null 2>&1
*/

// Retention (unverified accounts 30 days, contact messages 1 year, audit logs 2 years).
Schedule::command('sinowala:prune')->dailyAt('02:30')->withoutOverlapping();

// Database backup every day, keep 14 days.
Schedule::command('backup:clean')->dailyAt('03:00')->withoutOverlapping();
Schedule::command('backup:run --only-db')->dailyAt('03:15')->withoutOverlapping();
Schedule::command('backup:monitor')->dailyAt('09:00');

// Shared hosting: no Supervisor, so drain the database queue every minute.
if (config('sinowala.queue_via_scheduler')) {
    Schedule::command('queue:work --stop-when-empty --max-time=50 --tries=3')
        ->everyMinute()
        ->withoutOverlapping();
}

Schedule::command('auth:clear-resets')->daily();
