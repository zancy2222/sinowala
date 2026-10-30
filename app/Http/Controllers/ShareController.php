<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use App\Services\CalendarData;
use App\Support\Today;
use Illuminate\View\View;

/** Public, read-only: Available / Wala for the next 3 months. No company, notes or email. */
class ShareController extends Controller
{
    public function show(string $token): View
    {
        $user = User::query()
            ->where('share_token', $token)
            ->where('share_enabled', true)
            ->where('status', 'active')
            ->first();

        abort_unless($user, 404);

        $from = Today::date();
        $to = $from->addMonthsNoOverflow(config('sinowala.share_months'));

        // Only date + part — never the note relation.
        $absences = Absence::query()
            ->where('user_id', $user->id)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->get(['date', 'part'])
            ->mapWithKeys(fn (Absence $a) => [$a->date->toDateString() => ['part' => $a->part]]);

        $months = [];
        for ($m = $from->startOfMonth(); $m->lte($to); $m = $m->addMonthNoOverflow()) {
            $months[] = ['cursor' => $m, 'weeks' => CalendarData::monthWeeks($m)];
        }

        return view('share', [
            'firstName' => $user->firstName(),
            'months' => $months,
            'absences' => $absences,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
