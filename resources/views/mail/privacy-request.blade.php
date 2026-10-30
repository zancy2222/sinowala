<x-mail::message>
# {{ __('mail.privacy.heading') }}

{{ __('mail.privacy.deadline') }}

**{{ __('mail.privacy.type') }}:** {{ __('web.privacy_request.types.'.$privacyRequest->type) }}<br>
**{{ __('mail.privacy.name') }}:** {{ $privacyRequest->name }}<br>
**{{ __('mail.privacy.email') }}:** {{ $privacyRequest->email }}

@if ($privacyRequest->details)
**{{ __('mail.privacy.details') }}:**

<x-mail::panel>
{{ $privacyRequest->details }}
</x-mail::panel>
@endif

<x-mail::button :url="route('admin.privacy-requests')">
{{ __('mail.privacy.button') }}
</x-mail::button>
</x-mail::message>
