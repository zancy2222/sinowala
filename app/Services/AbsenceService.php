<?php

namespace App\Services;

use App\Models\Absence;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class AbsenceService
{
    /**
     * Mark a day or range as Wala. Existing entries in the range are updated.
     * Half days (am/pm) apply to a single day only.
     *
     * @return int number of days saved
     */
    public function file(User $user, CarbonImmutable $from, CarbonImmutable $to, string $part, ?string $note, bool $skipWeekends = false): int
    {
        if (! $from->isSameDay($to)) {
            $part = 'full';
        }
        $note = filled($note) ? trim($note) : null;

        return DB::transaction(function () use ($user, $from, $to, $part, $note, $skipWeekends) {
            $count = 0;
            for ($day = $from; $day->lte($to); $day = $day->addDay()) {
                if ($skipWeekends && $day->isWeekend()) {
                    continue;
                }
                $absence = Absence::query()
                    ->where('user_id', $user->id)
                    ->whereDate('date', $day->toDateString())
                    ->first() ?? new Absence(['date' => $day->toDateString()]);
                $absence->user_id = $user->id;
                $absence->part = $part;
                $absence->save();
                $this->saveNote($absence, $note);
                $count++;
            }

            return $count;
        });
    }

    public function update(Absence $absence, string $part, ?string $note): void
    {
        DB::transaction(function () use ($absence, $part, $note) {
            $absence->update(['part' => $part]);
            $this->saveNote($absence, filled($note) ? trim($note) : null);
        });
    }

    private function saveNote(Absence $absence, ?string $note): void
    {
        if ($note === null) {
            $absence->note()->delete();

            return;
        }

        $model = $absence->note()->firstOrNew();
        $model->user_id = $absence->user_id;
        $model->note = $note;
        $model->save();
    }
}
