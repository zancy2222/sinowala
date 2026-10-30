<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminListRequest;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\ContactMessage;
use App\Models\Membership;
use App\Models\PrivacyRequest;
use App\Models\User;
use App\Support\Today;
use Carbon\Carbon;
use Illuminate\View\View;

/** Aggregate numbers only. The Super Admin never sees calendar entries or notes. */
class AdminController extends Controller
{
    public function dashboard(AdminListRequest $request): View
    {
        $weekStart = Today::date()->startOfWeek(Carbon::MONDAY)->subWeeks(7);

        $signups = User::query()
            ->where('created_at', '>=', $weekStart)
            ->pluck('created_at')
            ->groupBy(fn ($at) => $at->copy()->setTimezone(config('app.timezone'))->startOfWeek(Carbon::MONDAY)->toDateString())
            ->map->count();

        $weeks = collect(range(0, 7))->map(fn ($i) => [
            'start' => $weekStart->addWeeks($i),
            'count' => $signups[$weekStart->addWeeks($i)->toDateString()] ?? 0,
        ]);

        return view('admin.dashboard', [
            'totals' => [
                'personal' => User::whereDoesntHave('membership')->count(),
                'companies' => Company::count(),
                'members' => Membership::count(),
                'pending' => Company::where('status', Company::PENDING)->count(),
            ],
            'weeks' => $weeks,
            'pendingCompanies' => Company::where('status', Company::PENDING)->withCount('memberships')->oldest()->limit(5)->get(),
            'unreadMessages' => ContactMessage::whereNull('read_at')->count(),
            'openRequests' => PrivacyRequest::where('status', 'open')->count(),
        ]);
    }

    public function audit(AdminListRequest $request): View
    {
        $logs = AuditLog::query()
            ->with('actor:id,name')
            ->when($request->validated('q'), fn ($q, $term) => $q->where(fn ($w) => $w
                ->where('action', 'like', '%'.$term.'%')
                ->orWhere('target_label', 'like', '%'.$term.'%')))
            ->latest('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.audit', ['logs' => $logs]);
    }
}
