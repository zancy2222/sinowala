<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrivacyRequestForm;
use App\Mail\PrivacyRequestReceived;
use App\Models\PrivacyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PrivacyRequestController extends Controller
{
    public function show(): View
    {
        return view('public.privacy-request');
    }

    public function store(PrivacyRequestForm $request): RedirectResponse
    {
        $data = $request->validated();

        if (filled($data['website'] ?? null)) {
            return redirect()->route('privacy-request')->with('sent', true);
        }

        $privacyRequest = PrivacyRequest::create([
            'user_id' => $request->user()?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'type' => $data['type'],
            'details' => $data['details'] ?? null,
            'ip' => $request->ip(),
        ]);

        if ($to = config('sinowala.super_admin_email')) {
            Mail::to($to)->queue(new PrivacyRequestReceived($privacyRequest));
        }

        return redirect()->route('privacy-request')->with('sent', true);
    }
}
