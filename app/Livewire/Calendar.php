<?php

namespace App\Livewire;

use App\Models\Absence;
use App\Models\Company;
use App\Services\CalendarData;
use App\Support\Fmt;
use App\Support\Today;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Accessible calendar (ARIA grid). Two scopes:
 *  me   — the signed-in user's own entries, with private notes.
 *  team — fellow members of an ACTIVE company: name + date + part only.
 */
class Calendar extends Component
{
    public const VIEWS = ['month', 'week', 'agenda'];

    #[Locked]
    public string $scope = 'me';

    /** null until the browser picks a default for its width (agenda / week / month). */
    #[Url(as: 'v', except: null)]
    public ?string $view = null;

    #[Url(as: 'd', except: null)]
    public ?string $cursor = null;

    public string $search = '';

    public ?string $openDay = null;

    public function mount(string $scope = 'me'): void
    {
        $this->scope = $scope === 'team' ? 'team' : 'me';
        if ($this->view !== null && ! in_array($this->view, self::VIEWS, true)) {
            $this->view = null;
        }
        $this->cursor = $this->validDate($this->cursor)?->toDateString();
    }

    private function validDate(?string $value): ?CarbonImmutable
    {
        if (! is_string($value) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }
        try {
            $date = CarbonImmutable::createFromFormat('!Y-m-d', $value, config('app.timezone'));
        } catch (\Throwable) {
            return null;
        }

        // Keep navigation within a sensible window.
        return $date && abs(Today::date()->diffInYears($date)) <= 5 ? $date : null;
    }

    private function cursorDate(): CarbonImmutable
    {
        return $this->validDate($this->cursor) ?? Today::date();
    }

    public function setView(string $view): void
    {
        if (in_array($view, self::VIEWS, true)) {
            $this->view = $view;
        }
    }

    #[On('calendar-prev')]
    public function prev(): void
    {
        $this->shift(-1);
    }

    #[On('calendar-next')]
    public function next(): void
    {
        $this->shift(1);
    }

    private function shift(int $direction): void
    {
        $c = $this->cursorDate();
        $c = match ($this->view) {
            'week' => $c->addWeeks($direction),
            'agenda' => $c->addDays($direction * $this->agendaLength()),
            default => $c->startOfMonth()->addMonthsNoOverflow($direction),
        };
        $this->cursor = $c->toDateString();
    }

    #[On('calendar-today')]
    public function today(): void
    {
        $this->cursor = null;
    }

    #[On('calendar-view')]
    public function switchView(string $view): void
    {
        $this->setView($view);
    }

    /** Keyboard navigation may move outside the visible period. */
    public function goTo(string $date): void
    {
        if ($d = $this->validDate($date)) {
            $this->cursor = $d->toDateString();
        }
    }

    public function showDay(string $date): void
    {
        if ($d = $this->validDate($date)) {
            $this->openDay = $d->toDateString();
            $this->dispatch('open-dialog', name: 'day-'.$this->getId());
        }
    }

    public function closeDay(): void
    {
        $this->openDay = null;
    }

    #[On('absences-changed')]
    public function refreshData(): void
    {
        // Re-render with fresh data.
    }

    private function agendaLength(): int
    {
        return $this->scope === 'team' ? 14 : 90;
    }

    private function company(): ?Company
    {
        $company = auth()->user()->activeCompany();
        if ($company) {
            $this->authorize('viewTeam', $company);
        }

        return $company;
    }

    public function render(CalendarData $data): View
    {
        $user = auth()->user();
        $today = Today::date();
        $cursor = $this->cursorDate();
        $view = $this->view ?? 'month';

        [$from, $to, $weeks, $days] = match ($view) {
            'week' => [...$this->weekRange($cursor), null, CalendarData::weekDays($cursor)],
            'agenda' => [$cursor, $cursor->addDays($this->agendaLength() - 1), null, null],
            default => $this->monthRange($cursor),
        };

        $members = collect();
        $team = collect();
        $mine = collect();
        $company = null;

        if ($this->scope === 'team') {
            $company = $this->company();
            if ($company) {
                $members = $data->members($company);
                $team = $data->team($company, $from, $to, $members);
                if (trim($this->search) !== '') {
                    $needle = mb_strtolower(trim($this->search));
                    $members = $members->filter(fn ($m) => str_contains(mb_strtolower($m->name), $needle))->values();
                    $ids = $members->pluck('id')->all();
                    $team = $team->map(fn (Collection $rows) => $rows->whereIn('user_id', $ids)->values())->filter->isNotEmpty();
                }
            }
        } else {
            $this->authorize('viewAny', Absence::class);
            $mine = $data->personal($user, $from, $to);
        }

        $title = match ($view) {
            'week' => Fmt::range($from, $to),
            'agenda' => Fmt::range($from, $to),
            default => Fmt::monthYear($cursor),
        };

        $dayDetail = null;
        if ($this->openDay) {
            $d = CarbonImmutable::parse($this->openDay, config('app.timezone'));
            $dayDetail = [
                'date' => $d,
                'label' => Fmt::date($d),
                'team' => $this->scope === 'team' && $company
                    ? ($data->team($company, $d, $d, $members)->get($d->toDateString()) ?? collect())
                    : collect(),
                'mine' => $this->scope === 'me' ? $data->personal($user, $d, $d)->get($d->toDateString()) : null,
                'memberCount' => $members->count(),
            ];
        }

        return view('livewire.calendar', [
            'resolvedView' => $view,
            'ready' => $this->view !== null,
            'today' => $today,
            'cursorDate' => $cursor,
            'weeks' => $weeks,
            'days' => $days,
            'from' => $from,
            'to' => $to,
            'title' => $title,
            'members' => $members,
            'team' => $team,
            'mine' => $mine,
            'company' => $company,
            'dayDetail' => $dayDetail,
        ]);
    }

    private function weekRange(CarbonImmutable $cursor): array
    {
        $start = $cursor->startOfWeek(CarbonImmutable::MONDAY);

        return [$start, $start->addDays(6)];
    }

    private function monthRange(CarbonImmutable $cursor): array
    {
        $weeks = CalendarData::monthWeeks($cursor);
        $first = $weeks[0][0];
        $lastWeek = end($weeks);

        return [$first, end($lastWeek), $weeks, null];
    }
}
