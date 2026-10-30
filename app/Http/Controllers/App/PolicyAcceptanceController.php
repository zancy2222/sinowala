<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\PolicyAcceptRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PolicyAcceptanceController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->user()->needsPolicyAcceptance()) {
            return redirect()->route('app.home');
        }

        return view('app.policies');
    }

    public function accept(PolicyAcceptRequest $request): RedirectResponse
    {
        $request->user()->forceFill([
            'terms_version' => config('sinowala.terms_version'),
            'privacy_version' => config('sinowala.privacy_version'),
            'accepted_at' => now(),
        ])->save();

        return redirect()->route('app.home');
    }
}
