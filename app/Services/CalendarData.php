<?php

namespace App\Services;

use App\Models\Absence;
use App\Models\Company;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/**
 * Reads calendar data with the privacy rules built in:
 * - personal(): the owner's own entries, with their private notes.
 * - team(): fellow members' name + date + part only. Never notes.
 * Callers must authorize first (AbsencePolicy / CompanyPolicy::viewTeam).
 */
class CalendarData
{
    /** @return Collection<string, array{id:int, part:string, note:?string}> keyed by Y-m-d */
    public function personal(User $user, CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return Absence::query()
            ->where('user_id', $user->id)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->with('note')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn (Absence $a) => [$a->date->toDateString() => [
                'id' => $a->id,
                'part' => $a->part,
                'note' => $a->note?->note,
            ]]);
    }

    /** @return Collection<int, object> each with id, name, role */
    public function members(Company $company): Collection
    {
        return $company->members()
            ->where('users.status', 'active')
            ->orderBy('users.name')
            ->get(['users.id', 'users.name'])
            ->map(fn (User $u) => (object) ['id' => $u->id, 'name' => $u->name, 'role' => $u->pivot->role]);
    }

    /** @return Collection<string, Collection> keyed by Y-m-d, each item user_id/name/part */
    public function team(Company $company, CarbonImmutable $from, CarbonImmutable $to, ?Collection $members = null): Collection
    {
        $members ??= $this->members($company);
        $names = $members->pluck('name', 'id');

        // Select only the three columns fellow members may see.
        return Absence::query()
            ->whereIn('user_id', $names->keys())
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('date')
            ->get(['user_id', 'date', 'part'])
            ->map(fn (Absence $a) => [
                'user_id' => $a->user_id,
                'name' => $names[$a->user_id],
                'part' => $a->part,
                'date' => $a->date->toDateString(),
            ])
            ->sortBy('name')
            ->groupBy('date')
            ->sortKeys();
    }

    /** Monday-first weeks covering the month of $cursor. */
    public static function monthWeeks(CarbonImmutable $cursor): array
    {
        $start = $cursor->startOfMonth()->startOfWeek(CarbonImmutable::MONDAY);
        $end = $cursor->endOfMonth()->endOfWeek(CarbonImmutable::SUNDAY);
        $weeks = [];
        for ($day = $start; $day->lte($end); $day = $day->addDay()) {
            $weeks[intdiv((int) $start->diffInDays($day), 7)][] = $day;
        }

        return $weeks;
    }

    /** @return array<int, CarbonImmutable> */
    public static function weekDays(CarbonImmutable $cursor): array
    {
        $start = $cursor->startOfWeek(CarbonImmutable::MONDAY);

        return array_map(fn ($i) => $start->addDays($i), range(0, 6));
    }
}
