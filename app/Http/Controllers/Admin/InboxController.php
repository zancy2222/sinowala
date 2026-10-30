<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminListRequest;
use App\Models\AuditLog;
use App\Models\ContactMessage;
use App\Models\PrivacyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/** Contact-form messages and data-privacy requests. */
class InboxController extends Controller
{
    public function messages(AdminListRequest $request): View
    {
        $messages = ContactMessage::query()
            ->when($request->validated('status') === 'unread', fn ($q) => $q->whereNull('read_at'))
            ->when($request->validated('q'), fn ($q, $term) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$term.'%')->orWhere('email', 'like', '%'.$term.'%')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.messages', ['messages' => $messages]);
    }

    public function markRead(AdminListRequest $request, ContactMessage $message): RedirectResponse
    {
        $message->forceFill(['read_at' => now()])->save();
        AuditLog::record('message.read', $message, $message->email);

        return back()->with('toast', __('admin.messages.marked'));
    }

    public function privacyRequests(AdminListRequest $request): View
    {
        $requests = PrivacyRequest::query()
            ->when($request->validated('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->validated('q'), fn ($q, $term) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$term.'%')->orWhere('email', 'like', '%'.$term.'%')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.privacy-requests', ['requests' => $requests]);
    }

    public function markDone(AdminListRequest $request, PrivacyRequest $privacyRequest): RedirectResponse
    {
        $privacyRequest->forceFill(['status' => 'done', 'handled_at' => now()])->save();
        AuditLog::record('privacy_request.done', $privacyRequest, $privacyRequest->type.' · '.$privacyRequest->email);

        return back()->with('toast', __('admin.privacy.marked'));
    }
}
