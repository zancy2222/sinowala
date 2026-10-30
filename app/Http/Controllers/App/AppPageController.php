<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppPageController extends Controller
{
    /** "Ngayon": Team / Ako toggle for company members, personal view otherwise. */
    public function home(Request $request): View
    {
        $user = $request->user();
        $this->authorize('viewAny', Absence::class);

        $company = $user->company();
        $tab = $company && $request->query('tab') !== 'ako' ? 'team' : 'ako';

        if ($tab === 'team' && $company->isActive()) {
            $this->authorize('viewTeam', $company);
        }

        return view('app.home', ['company' => $company, 'tab' => $tab]);
    }

    public function myCalendar(Request $request): View
    {
        $this->authorize('viewAny', Absence::class);

        return view('app.my-calendar');
    }

    public function teamCalendar(Request $request): View
    {
        $company = $request->user()->company();
        abort_unless($company, 404);

        if ($company->isActive()) {
            $this->authorize('viewTeam', $company);
        }

        return view('app.team-calendar', ['company' => $company]);
    }
}
