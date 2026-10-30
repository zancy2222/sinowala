<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Honeypot was filled in: look successful, store nothing.
        if (filled($data['website'] ?? null)) {
            return redirect()->route('contact')->with('sent', true);
        }

        $message = ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
            'ip' => $request->ip(),
        ]);

        if ($to = config('sinowala.super_admin_email')) {
            Mail::to($to)->queue(new ContactMessageReceived($message));
        }

        return redirect()->route('contact')->with('sent', true);
    }
}
