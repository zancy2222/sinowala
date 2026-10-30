<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminListRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserAdminController extends Controller
{
    public function index(AdminListRequest $request): View
    {
        $users = User::query()
            ->with('membership.company:id,name')
            ->withCount('absences')
            ->when($request->validated('q'), fn ($q, $term) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$term.'%')
                ->orWhere('email', 'like', '%'.$term.'%')))
            ->when($request->validated('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.users', ['users' => $users]);
    }

    public function suspend(User $user): RedirectResponse
    {
        $this->authorize('suspend', $user);
        $user->forceFill(['status' => 'suspended'])->save();

        // Sign them out everywhere.
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
        }
        AuditLog::record('user.suspended', $user, $user->email);

        return back()->with('toast', __('admin.users.toast.suspended', ['name' => $user->name]));
    }

    public function reactivate(User $user): RedirectResponse
    {
        $this->authorize('reactivate', $user);
        $user->forceFill(['status' => 'active'])->save();
        AuditLog::record('user.reactivated', $user, $user->email);

        return back()->with('toast', __('admin.users.toast.reactivated', ['name' => $user->name]));
    }
}
