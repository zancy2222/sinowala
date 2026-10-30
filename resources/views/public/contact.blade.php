<x-layouts.public :title="__('web.contact.title')" :description="__('web.contact.meta')">
    <x-public-hero :title="__('web.contact.heading')" :lead="__('web.contact.lead')" />

    <div class="mx-auto grid max-w-[1240px] grid-cols-[minmax(0,1fr)] gap-12 safe-x py-16 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-24">
        <div class="lg:col-span-7">
            @if (session('sent'))
                <x-ui.alert tone="success" :title="__('web.contact.sent_title')">{{ __('web.contact.sent_body') }}</x-ui.alert>
            @else
                <form method="POST" action="{{ route('contact.store') }}" class="flex flex-col gap-5" novalidate>
                    @csrf
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-ui.field :label="__('web.contact.name')" for="name">
                            <x-ui.input name="name" :value="old('name', auth()->user()?->name)" autocomplete="name" maxlength="120" required />
                        </x-ui.field>
                        <x-ui.field :label="__('web.contact.email')" for="email">
                            <x-ui.input type="email" name="email" :value="old('email', auth()->user()?->email)" autocomplete="email" required />
                        </x-ui.field>
                    </div>
                    <x-ui.field :label="__('web.contact.message')" for="message" :help="__('web.contact.message_help')">
                        <x-ui.textarea name="message" rows="7" maxlength="5000" required :help="true">{{ old('message') }}</x-ui.textarea>
                    </x-ui.field>
                    {{-- Honeypot: hidden from people and screen readers; bots fill it in. --}}
                    <div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
                        <label for="website">{{ __('web.contact.honeypot') }}</label>
                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div><x-ui.button type="submit" size="lg">{{ __('web.contact.submit') }}</x-ui.button></div>
                </form>
            @endif
        </div>
        <aside class="lg:col-span-4 lg:col-start-9" aria-labelledby="contact-other">
            <h2 id="contact-other" class="text-lg font-extrabold">{{ __('web.contact.other_ways') }}</h2>
            <p class="mt-3 text-base"><span class="text-muted">{{ __('web.contact.email_us') }}:</span> {{ config('sinowala.business.email') }}</p>
            <p class="mt-6 rounded-lg bg-ground p-5 text-base leading-7">{{ __('web.contact.privacy_hint') }} <a href="{{ route('privacy-request') }}" class="link font-semibold">{{ __('web.footer.links.privacy-request') }}</a></p>
        </aside>
    </div>
</x-layouts.public>
