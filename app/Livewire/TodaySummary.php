<?php

namespace App\Livewire;

use App\Models\Absence;
use App\Services\CalendarData;
use App\Support\Today;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

/** "Ngayon": today's team summary, or the user's own next Wala days. */
class TodaySummary extends Component
{
    #[Locked]
    public string $mode = 'ako';

    public function mount(string $mode = 'ako'): void
    {
        $this->mode = $mode === 'team' ? 'team' : 'ako';
    }

    #[On('absences-changed')]
    public function refreshData(): void {}

    public function render(CalendarData $data): View
    {
        $user = auth()->user();
        $today = Today::date();

        if ($this->mode === 'team' && ($company = $user->activeCompany())) {
            $this->authorize('viewTeam', $company);
            $members = $data->members($company);
            $todayRows = $data->team($company, $today, $today, $members)->get($today->toDateString(), collect());
            $upcoming = $data->team($company, $today->addDay(), $today->addDays(30), $members)->take(4);

            $fullDay = $todayRows->where('part', 'full')->count();

            return view('livewire.today-summary', [
                'mode' => 'team',
                'today' => $today,
                'outToday' => $todayRows,
                'presentCount' => $members->count() - $fullDay,
                'halfDayCount' => $todayRows->where('part', '!=', 'full')->count(),
                'memberCount' => $members->count(),
                'upcoming' => $upcoming,
            ]);
        }

        $this->authorize('viewAny', Absence::class);

        $next = Absence::query()
            ->where('user_id', $user->id)
            ->where('date', '>=', $today->toDateString())
            ->with('note')
            ->orderBy('date')
            ->limit(5)
            ->get();

        $thisMonth = Absence::query()
            ->where('user_id', $user->id)
            ->whereBetween('date', [$today->startOfMonth()->toDateString(), $today->endOfMonth()->toDateString()])
            ->get(['part']);

        return view('livewire.today-summary', [
            'mode' => 'ako',
            'today' => $today,
            'next' => $next,
            'todayEntry' => $next->first(fn (Absence $a) => $a->date->isSameDay($today)),
            'monthDays' => $thisMonth->sum(fn ($a) => $a->part === 'full' ? 1 : 0.5),
        ]);
    }
}
