<?php

namespace App\Mail;

use App\Models\PrivacyRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PrivacyRequestReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public PrivacyRequest $privacyRequest) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('mail.privacy.subject', ['type' => __('web.privacy_request.types.'.$this->privacyRequest->type)]));
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.privacy-request');
    }
}
