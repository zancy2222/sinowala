{{-- Shared template for Privacy Policy, Terms of Use, Cookie Policy and Accessibility Statement. --}}
@php
    use App\Support\Fmt;
    $d = __('legal.'.$doc);
    $vars = [
        'business' => config('sinowala.business.name'),
        'address' => config('sinowala.business.address'),
        'email' => config('sinowala.business.email'),
        'dpo_name' => config('sinowala.business.dpo_name'),
        'dpo_email' => config('sinowala.business.dpo_email'),
        'privacy_url' => route('privacy'),
        'request_url' => route('privacy-request'),
        'contact_url' => route('contact'),
    ];
    $fill = fn ($text) => strtr($text, collect($vars)->mapWithKeys(fn ($v, $k) => [':'.$k => $v])->all());
@endphp
<x-layouts.public :title="$d['title']" :description="$d['meta']">
    <x-public-hero :title="$d['title']">
        <p class="mt-4 text-base text-muted">{{ __('web.legal.updated') }}: <time datetime="{{ config('sinowala.legal_updated') }}">{{ Fmt::date(config('sinowala.legal_updated')) }}</time> · {{ __('web.legal.version') }} {{ $version }}</p>
        @if (! empty($d['intro']))<p class="measure mt-5 text-lg leading-8">{{ $fill($d['intro']) }}</p>@endif
    </x-public-hero>

    <div class="mx-auto grid max-w-[1240px] grid-cols-[minmax(0,1fr)] gap-10 safe-x py-14 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-20">
        <nav aria-labelledby="legal-toc" class="lg:sticky lg:top-24 lg:col-span-3 lg:max-h-[calc(100dvh-8rem)] lg:self-start lg:overflow-y-auto">
            <h2 id="legal-toc" class="text-sm font-semibold text-muted">{{ __('web.legal.toc') }}</h2>
            <ol class="mt-2 gap-x-10 md:columns-2 lg:columns-1">
                @foreach ($d['sections'] as $i => $s)
                    <li><a href="#s{{ $i + 1 }}" class="flex min-h-10 break-inside-avoid items-center py-1 text-sm font-semibold underline-offset-4 hover:underline">{{ $s['title'] }}</a></li>
                @endforeach
            </ol>
        </nav>
        <article class="prose-sw lg:col-span-8 lg:col-start-5">
            @foreach ($d['sections'] as $i => $s)
                <section id="s{{ $i + 1 }}" aria-labelledby="s{{ $i + 1 }}-title" class="scroll-mt-24">
                    <h2 id="s{{ $i + 1 }}-title" class="{{ $i === 0 ? 'mt-0!' : '' }}">{{ $s['title'] }}</h2>
                    @foreach ($s['body'] as $block)
                        @if (is_array($block) && isset($block['list']))
                            <ul>@foreach ($block['list'] as $li)<li>{{ $fill($li) }}</li>@endforeach</ul>
                        @elseif (is_array($block) && isset($block['table']))
                            <div class="overflow-x-auto" tabindex="0" role="region" aria-label="{{ $s['title'] }}">
                                <table class="min-w-[36rem]">
                                    <thead><tr>@foreach ($block['table']['head'] as $h)<th scope="col">{{ $h }}</th>@endforeach</tr></thead>
                                    <tbody>
                                        @foreach ($block['table']['rows'] as $row)
                                            <tr>@foreach ($row as $j => $cell)@if ($j === 0)<th scope="row" class="border-b border-line py-2.5 pr-4 text-left align-top font-semibold">{{ $fill($cell) }}</th>@else<td>{{ $fill($cell) }}</td>@endif @endforeach</tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif (is_array($block) && isset($block['link']))
                            <p><a href="{{ $fill($block['href']) }}">{{ $fill($block['link']) }}</a></p>
                        @else
                            <p>{{ $fill($block) }}</p>
                        @endif
                    @endforeach
                </section>
            @endforeach
        </article>
    </div>
</x-layouts.public>
