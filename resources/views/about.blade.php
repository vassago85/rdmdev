@extends('layouts.app')

@section('content')

<section class="bg-ink-800 text-white">
    <div class="container py-16 sm:py-20 grid gap-10 lg:grid-cols-[1.3fr_1fr] lg:items-center">
        <div>
            @if ($about->about_eyebrow)
                <p class="eyebrow !text-brand-200">{{ $about->about_eyebrow }}</p>
            @endif
            <h1 class="!text-white mt-3">{{ $about->about_heading }}</h1>
            @if ($about->about_intro)
                <p class="mt-5 text-lg text-ink-100/90 max-w-2xl">{{ $about->about_intro }}</p>
            @endif
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-lg bg-white text-brand-700 hover:bg-brand-50">Call {{ config('rdm.phone') }}</a>
                <a href="{{ route('contact') }}#enquiry" class="btn-outline">Request a quote</a>
            </div>
        </div>
        <div>
            @if ($about->heroImageUrl())
                <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-card">
                    <img
                        src="{{ $about->heroImageUrl() }}"
                        alt="{{ $about->about_heading }}"
                        width="960"
                        height="720"
                        class="h-full w-full object-cover"
                    />
                </div>
            @else
                <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 p-8 text-white flex flex-col justify-between">
                    <x-rdm-logo variant="light" class="h-14 w-auto self-start" />
                    <div>
                        <p class="text-3xl sm:text-4xl font-display font-bold">{{ $leadMember?->name ?? config('rdm.owner') }}</p>
                        <p class="mt-2 text-brand-100">{{ $leadMember?->title ?? 'Owner & Project Supervisor' }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="section">
    <div class="container grid gap-12 lg:grid-cols-3">
        <div class="lg:col-span-2 prose-rdm">
            @if ($about->about_story_heading)
                <h2>{{ $about->about_story_heading }}</h2>
            @endif
            @if ($about->about_body)
                {!! $about->about_body !!}
            @endif

            @if ($about->expect_heading || count($about->expectItems()))
                @if ($about->expect_heading)
                    <h3>{{ $about->expect_heading }}</h3>
                @endif
                @if (count($about->expectItems()))
                    <ul>
                        @foreach ($about->expectItems() as $item)
                            <li>
                                @if (! empty($item['title']))
                                    <strong>{{ $item['title'] }}</strong>
                                @endif
                                @if (! empty($item['text']))
                                    {{ $item['text'] }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endif

            @if ($about->where_heading || $about->where_we_work)
                @if ($about->where_heading)
                    <h3>{{ $about->where_heading }}</h3>
                @endif
                @if ($about->where_we_work)
                    <p>{{ $about->where_we_work }}</p>
                @endif
            @endif
        </div>

        <aside class="space-y-6">
            <div class="card p-6">
                <h3 class="!text-lg">Get in touch</h3>
                <ul class="mt-4 space-y-3 text-ink-700">
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">Phone</p>
                        <a class="font-semibold link-underline" href="tel:{{ config('rdm.phone_tel') }}">{{ config('rdm.phone') }}</a>
                    </li>
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">WhatsApp</p>
                        <a class="font-semibold link-underline" target="_blank" rel="noopener"
                           href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode(config('rdm.whatsapp_greeting')) }}">
                            {{ config('rdm.phone') }}
                        </a>
                    </li>
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">Email</p>
                        <a class="font-semibold link-underline" href="mailto:{{ config('rdm.email') }}">{{ config('rdm.email') }}</a>
                    </li>
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">Area</p>
                        <p class="font-semibold">{{ config('rdm.location') }}</p>
                    </li>
                </ul>
            </div>

            <div class="card p-6">
                <h3 class="!text-lg">Company details</h3>
                <ul class="mt-4 space-y-3 text-ink-700 text-sm">
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">Legal name</p>
                        <p class="font-semibold">{{ config('rdm.legal_name') }}</p>
                    </li>
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">Registration number</p>
                        <p class="font-semibold">{{ config('rdm.registration_number') }}</p>
                    </li>
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">NHBRC</p>
                        <p class="font-semibold">
                            Registered with the NHBRC as a home builder
                            @if (config('rdm.nhbrc_number'))
                                <span class="block text-ink-500 font-normal mt-1">{{ config('rdm.nhbrc_number') }}</span>
                            @endif
                        </p>
                    </li>
                </ul>
            </div>

            <div class="card p-6 bg-brand-50 border-brand-100">
                <h3 class="!text-lg">Suburbs we serve</h3>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach (config('rdm.suburbs') as $suburb)
                        <span class="chip">{{ $suburb }}</span>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</section>

@if ($members->isNotEmpty() || $about->teamGroupPhotoUrl())
<section class="section-tight bg-ink-50" id="team">
    <div class="container">
        @if ($about->team_heading)
            <p class="eyebrow">About &amp; Team</p>
            <h2 class="mt-2">{{ $about->team_heading }}</h2>
        @endif
        @if ($about->team_intro)
            <p class="mt-4 text-lg text-ink-500 max-w-2xl">{{ $about->team_intro }}</p>
        @endif

        @if ($about->teamGroupPhotoUrl())
            <figure class="mt-10 rounded-2xl overflow-hidden shadow-card bg-white">
                <img
                    src="{{ $about->teamGroupPhotoUrl() }}"
                    alt="{{ $about->team_group_photo_caption ?: 'The RDM Developments team on-site in Pretoria East' }}"
                    width="1600"
                    height="900"
                    loading="lazy"
                    decoding="async"
                    class="w-full h-auto object-cover"
                />
                @if ($about->team_group_photo_caption)
                    <figcaption class="px-5 py-3 text-sm text-ink-500 text-center">
                        {{ $about->team_group_photo_caption }}
                    </figcaption>
                @endif
            </figure>
        @endif

        @if ($members->isNotEmpty())
        <div class="mt-10 grid grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($members as $member)
                @php
                    $fallback = strcasecmp($member->name, (string) config('rdm.owner')) === 0
                        ? asset('images/ruben-metcalfe.jpg')
                        : null;
                    $photo = $member->photoUrl($fallback);
                @endphp
                <article class="card p-5 sm:p-6 text-center">
                    <div class="mx-auto h-28 w-28 sm:h-32 sm:w-32 rounded-full overflow-hidden bg-brand-50 text-brand-700">
                        @if ($photo)
                            <img
                                src="{{ $photo }}"
                                alt="{{ $member->name }}"
                                width="256"
                                height="256"
                                loading="lazy"
                                decoding="async"
                                class="h-full w-full object-cover object-top"
                            />
                        @else
                            <div class="h-full w-full flex items-center justify-center font-display text-2xl font-bold">
                                {{ $member->initials() }}
                            </div>
                        @endif
                    </div>
                    <p class="mt-4 text-xs font-semibold uppercase tracking-[0.16em] text-brand-600">{{ $member->title }}</p>
                    <h3 class="mt-1 !text-lg sm:!text-xl">{{ $member->name }}</h3>
                </article>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif

@include('partials.testimonials')

@include('partials.cta')

@endsection
