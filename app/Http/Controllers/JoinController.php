<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JoinController extends Controller
{
    public function __construct(private CompanyService $companies) {}

    public function show(Request $request, string $token): View|RedirectResponse
    {
        $company = $this->companies->findJoinable(token: $token);

        if (! $request->user()) {
            if (! $company) {
                return view('join', ['company' => null]);
            }
            // Remember the invite through sign-up; the link itself is the proof.
            $request->session()->put('join_token', $token);

            return redirect()->route('register', ['path' => 'join']);
        }

        return view('join', ['company' => $company, 'token' => $token, 'alreadyMember' => $request->user()->membership()->exists()]);
    }

    public function accept(Request $request, string $token): RedirectResponse
    {
        $company = $this->companies->findJoinable(token: $token);
        abort_unless($company, 404);

        $this->companies->join($request->user(), $company);

        return redirect()->route('app.home', ['tab' => 'team'])->with('toast', __('app.company.joined', ['company' => $company->name]));
    }
}
