<x-layouts.public :title="__('web.privacy_request.title')" :description="__('web.privacy_request.meta')">
    <x-public-hero :title="__('web.privacy_request.title')" :lead="__('web.privacy_request.lead')" />

    <div class="mx-auto grid max-w-[1240px] grid-cols-[minmax(0,1fr)] gap-12 safe-x py-16 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-24">
        <div class="lg:col-span-7">
            @if (session('sent'))
                <x-ui.alert tone="success" :title="__('web.privacy_request.sent_title')">{{ __('web.privacy_request.sent_body') }}</x-ui.alert>
            @else
                <form method="POST" action="{{ route('privacy-request.store') }}" class="flex flex-col gap-5" novalidate>
                    @csrf
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-ui.field :label="__('web.privacy_request.name')" for="name">
                            <x-ui.input name="name" :value="old('name', auth()->user()?->name)" autocomplete="name" maxlength="120" required />
                        </x-ui.field>
                        <x-ui.field :label="__('web.privacy_request.email')" for="email" :help="__('web.privacy_request.email_help')">
                            <x-ui.input type="email" name="email" :value="old('email', auth()->user()?->email)" autocomplete="email" required :help="true" />
                        </x-ui.field>
                    </div>
                    <fieldset class="flex flex-col gap-2">
                        <legend class="mb-2 text-sm font-semibold">{{ __('web.privacy_request.type') }}</legend>
                        @foreach (__('web.privacy_request.types') as $value => $label)
                            <x-ui.radio name="type" :value="$value" :label="$label" :checked="old('type', 'access') === $value" />
                        @endforeach
                        @error('type')<p class="text-sm text-danger" role="alert">{{ $message }}</p>@enderror
                    </fieldset>
                    <x-ui.field :label="__('web.privacy_request.details')" for="details" :help="__('web.privacy_request.details_help')" optional>
                        <x-ui.textarea name="details" rows="5" maxlength="5000" :help="true">{{ old('details') }}</x-ui.textarea>
                    </x-ui.field>
                    <div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
                        <label for="website">{{ __('web.contact.honeypot') }}</label>
                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div><x-ui.button type="submit" size="lg">{{ __('web.privacy_request.submit') }}</x-ui.button></div>
                </form>
            @endif
        </div>
        <aside class="lg:col-span-4 lg:col-start-9">
            <p class="rounded-lg bg-ground p-5 text-base leading-7">{{ __('web.privacy_request.self_service') }}
                <a href="{{ route('settings') }}#data" class="link font-semibold">{{ __('web.privacy_request.self_service_link') }}</a></p>
        </aside>
    </div>
</x-layouts.public>
