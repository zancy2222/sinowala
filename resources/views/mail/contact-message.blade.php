<x-mail::message>
# {{ __('mail.contact.heading') }}

**{{ __('mail.contact.from') }}:** {{ $contactMessage->name }}<br>
**{{ __('mail.contact.email') }}:** {{ $contactMessage->email }}<br>
**{{ __('mail.contact.received') }}:** {{ \App\Support\Fmt::dateTime($contactMessage->created_at) }}

**{{ __('mail.contact.message') }}:**

<x-mail::panel>
{{ $contactMessage->message }}
</x-mail::panel>

{{ __('mail.contact.reply') }}

<x-mail::button :url="route('admin.messages')">
{{ __('mail.contact.button') }}
</x-mail::button>
</x-mail::message>
