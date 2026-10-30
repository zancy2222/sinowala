<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Membership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/** Team page (boss): members, roles, invite link and code. */
class TeamController extends Controller
{
    private function company(Request $request): Company
    {
        $company = $request->user()->company();
        abort_unless($company, 404);
        $this->authorize('manage', $company);

        return $company;
    }

    public function index(Request $request): View
    {
        $company = $this->company($request);

        $memberships = $company->memberships()
            ->with('user:id,name,email,status')
            ->get()
            ->sortBy(fn (Membership $m) => [$m->role === Membership::ROLE_ADMIN ? 0 : 1, $m->user->name])
            ->values();

        return view('app.team', ['company' => $company, 'memberships' => $memberships]);
    }

    public function regenerate(Request $request): RedirectResponse
    {
        $company = $this->company($request);
        $this->authorize('invite', $company);
        $company->assignFreshCodes();

        return back()->with('toast', __('app.team.regenerated'));
    }

    public function promote(Request $request, Membership $membership): RedirectResponse
    {
        $this->authorize('promote', $membership);
        $membership->update(['role' => Membership::ROLE_ADMIN]);

        return back()->with('toast', __('app.team.promoted', ['name' => $membership->user->name]));
    }

    public function demote(Request $request, Membership $membership): RedirectResponse
    {
        $this->authorize('demote', $membership);

        DB::transaction(function () use ($membership) {
            // Re-check inside the transaction so two admins cannot demote each other at once.
            $admins = Membership::where('company_id', $membership->company_id)
                ->where('role', Membership::ROLE_ADMIN)->lockForUpdate()->count();
            abort_if($admins < 2, 403);
            $membership->update(['role' => Membership::ROLE_MEMBER]);
        });

        $redirect = $membership->user_id === $request->user()->id ? redirect()->route('app.home') : back();

        return $redirect->with('toast', __('app.team.demoted', ['name' => $membership->user->name]));
    }

    public function remove(Request $request, Membership $membership): RedirectResponse
    {
        $this->authorize('remove', $membership);
        $name = $membership->user->name;
        $membership->delete();

        return back()->with('toast', __('app.team.removed', ['name' => $name]));
    }
}
