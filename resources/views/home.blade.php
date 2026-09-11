@extends('layouts.app')

@section('content')

@php
    $heroUrl = $about->homeHeroImageUrl();
    $featuredCount = $featuredProjects->count();
@endphp

{{-- HERO --}}
<section class="relative overflow-hidden text-white">
    <div class="absolute inset-0" aria-hidden="true">
        @if ($heroUrl)
            <img
                src="{{ $heroUrl }}"
                alt=""
                class="absolute inset-0 h-full w-full object-cover"
                width="1920"
                height="1080"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            >
        @else
            {{-- Branded fallback until hero-home.jpg is uploaded — see public/images/README-hero.md --}}
            <div class="absolute inset-0 bg-gradient-to-br from-ink-900 via-ink-800 to-brand-800"></div>
            <div class="absolute inset-0 opacity-30"
                 style="background-image: radial-gradient(circle at 80% 20%, rgba(201,169,97,0.25), transparent 45%), radial-gradient(circle at 10% 80%, rgba(47,93,98,0.45), transparent 40%);"></div>
        @endif
        {{-- Dark gradient overlay — keeps type legible over any photo --}}
        <div class="absolute inset-0 bg-gradient-to-r from-ink-900/95 via-ink-900/80 to-ink-900/55"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink-900/50 via-transparent to-ink-900/30"></div>
    </div>

    <div class="container relative grid gap-8 lg:gap-10 py-12 sm:py-14 lg:py-16 lg:grid-cols-[1.35fr_1fr] lg:items-center">
        <div>
            @if ($about->home_eyebrow)
                <p class="eyebrow !text-brand-200">{{ $about->home_eyebrow }}</p>
            @endif
            <h1 class="!text-white mt-3">{{ $about->home_heading }}</h1>
            @if ($about->home_intro)
                <p class="mt-4 text-base sm:text-lg text-ink-100 max-w-xl leading-relaxed">{{ $about->home_intro }}</p>
            @endif

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-lg bg-white text-brand-700 hover:bg-brand-50">
                    <x-lucide name="phone" class="h-5 w-5" stroke="2" />
                    Call Ruben
                </a>
                <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode(config('rdm.whatsapp_greeting')) }}"
                   target="_blank" rel="noopener"
                   class="btn-whatsapp">
                    <x-lucide name="message-circle" class="h-5 w-5" stroke="2" />
                    WhatsApp
                </a>
                <a href="{{ route('contact') }}#enquiry" class="btn-outline">
                    <x-lucide name="file-text" class="h-5 w-5" stroke="2" />
                    Request a Quote
                </a>
            </div>
        </div>

        {{-- OWNER / TRUST CARD — kept for credibility, now over the photo --}}
        <div class="lg:pl-4">
            <div class="bg-ink-900/55 backdrop-blur-md border border-white/15 rounded-2xl p-5 sm:p-6 shadow-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ $leadMember?->photoUrl(asset('images/ruben-metcalfe.jpg')) ?? asset('images/ruben-metcalfe.jpg') }}"
                         alt="{{ $leadMember?->name ?? config('rdm.owner') }}, owner of {{ config('rdm.name') }}"
                         width="48" height="48" loading="eager" decoding="async"
                         class="h-12 w-12 rounded-full object-cover object-top ring-2 ring-brand-400/60">
                    <div>
                        <p class="font-semibold text-white leading-tight">{{ $leadMember?->name ?? config('rdm.owner') }}</p>
                        <p class="text-sm text-ink-200">{{ $about->home_owner_role ?: 'Owner · RDM Developments' }}</p>
                    </div>
                </div>
                @if ($about->home_owner_quote)
                    <p class="text-ink-100 leading-relaxed italic text-[15px]">
                        “{{ $about->home_owner_quote }}”
                    </p>
                @endif

                @if (count($about->homeOwnerBullets()))
                    <ul class="mt-4 space-y-2 text-sm text-ink-100">
                        @foreach ($about->homeOwnerBullets() as $bullet)
                            <li class="flex items-center gap-2">
                                <x-lucide name="check-circle-2" class="h-4 w-4 text-brand-300" />
                                {{ $bullet }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-5 flex flex-col gap-2">
                    <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-md bg-brand-500 text-white hover:bg-brand-400">
                        <x-lucide name="phone" class="h-4 w-4" />
                        {{ config('rdm.phone') }}
                    </a>
                    <a href="mailto:{{ config('rdm.email') }}" class="btn btn-md bg-white/10 text-white hover:bg-white/20">
                        <x-lucide name="mail" class="h-4 w-4" />
                        {{ config('rdm.email') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.trust-bar')

{{-- FEATURED PROJECTS --}}
@if ($featuredCount)
<section class="section">
    <div class="container">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div class="max-w-2xl">
                @if ($about->projects_eyebrow)
                    <p class="eyebrow">{{ $about->projects_eyebrow }}</p>
                @endif
                <h2 class="mt-2">{{ $about->projects_heading ?: 'Featured projects' }}</h2>
                @if ($about->projects_intro)
                    <p class="mt-3 text-ink-500 leading-relaxed">{{ $about->projects_intro }}</p>
                @endif
            </div>
            <a href="{{ route('projects.index') }}" class="btn-ghost !text-base inline-flex items-center gap-1.5 shrink-0">
                View all projects
                <x-lucide name="arrow-right" class="h-4 w-4" />
            </a>
        </div>

        <div @class([
            'mt-10 grid gap-6',
            'max-w-xl mx-auto' => $featuredCount === 1,
            'sm:grid-cols-2 max-w-4xl mx-auto' => $featuredCount === 2,
            'sm:grid-cols-2 lg:grid-cols-3' => $featuredCount >= 3,
        ])>
            @foreach ($featuredProjects->take(3) as $project)
                @include('partials.project-card', ['project' => $project])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- SERVICES --}}
<section class="section bg-ink-50/60 border-y border-ink-100">
    <div class="container">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div class="max-w-2xl">
                @if ($about->services_eyebrow)
                    <p class="eyebrow">{{ $about->services_eyebrow }}</p>
                @endif
                <h2 class="mt-2">{{ $about->services_heading ?: 'Services across Pretoria East' }}</h2>
                @if ($about->services_intro)
                    <p class="mt-3 text-lg text-ink-500 leading-relaxed">{{ $about->services_intro }}</p>
                @endif
            </div>
            <a href="{{ route('services.index') }}" class="btn-ghost !text-base inline-flex items-center gap-1.5 shrink-0">
                All services
                <x-lucide name="arrow-right" class="h-4 w-4" />
            </a>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $service)
                @include('partials.service-card', ['service' => $service])
            @endforeach
        </div>

        @if ($about->services_disclaimer)
            <p class="mt-10 max-w-2xl text-sm text-ink-500 leading-relaxed">{{ $about->services_disclaimer }}</p>
        @endif
    </div>
</section>

{{-- WHY CHOOSE RDM --}}
<section class="section">
    <div class="container">
        <div class="max-w-2xl mx-auto text-center lg:max-w-none lg:text-left lg:mx-0">
            @if ($about->why_eyebrow)
                <p class="eyebrow">{{ $about->why_eyebrow }}</p>
            @endif
            <h2 class="mt-2">{{ $about->why_heading ?: 'Why homeowners choose RDM' }}</h2>
            @if ($about->why_intro)
                <p class="mt-3 text-lg text-ink-500 leading-relaxed max-w-2xl lg:mx-0 mx-auto">{{ $about->why_intro }}</p>
            @endif
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($about->whyItems() as $item)
                <div class="card-interactive p-6">
                    <div class="inline-flex items-center justify-center h-11 w-11 rounded-lg bg-brand-50 text-brand-600 mb-4">
                        <x-lucide :name="$item['icon']" class="h-6 w-6" stroke="1.8" />
                    </div>
                    <h3 class="!text-lg">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed">{{ $item['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('partials.testimonials')

{{-- AREAS WE SERVE --}}
<section class="section bg-ink-50/60 border-y border-ink-100">
    <div class="container">
        <div class="max-w-2xl mx-auto text-center">
            @if ($about->areas_eyebrow)
                <p class="eyebrow">{{ $about->areas_eyebrow }}</p>
            @endif
            <h2 class="mt-2">{{ $about->areas_heading ?: 'Areas we serve in Pretoria East' }}</h2>
            @if ($about->areas_intro)
                <p class="mt-3 text-lg text-ink-500 leading-relaxed">{{ $about->areas_intro }}</p>
            @endif
        </div>

        <div class="mt-10 flex flex-wrap justify-center gap-2.5 sm:gap-3">
            @foreach (config('rdm.suburbs') as $suburb)
                <span class="suburb-pill">
                    <x-lucide name="map-pin" class="h-3.5 w-3.5 text-brand-500" />
                    {{ $suburb }}
                </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ABOUT PREVIEW --}}
<section class="section">
    <div class="container grid gap-10 lg:grid-cols-2 lg:items-center">
        <div>
            @if ($about->home_about_eyebrow)
                <p class="eyebrow">{{ $about->home_about_eyebrow }}</p>
            @endif
            <h2 class="mt-2">{{ $about->home_about_heading }}</h2>
            @if ($about->home_about_intro)
                <p class="mt-5 text-lg text-ink-500 leading-relaxed">{{ $about->home_about_intro }}</p>
            @endif
            @if (count($about->homeAboutBullets()))
                <ul class="mt-6 space-y-3 text-ink-700">
                    @foreach ($about->homeAboutBullets() as $bullet)
                        <li class="flex gap-3 items-start">
                            <x-lucide name="check-circle-2" class="h-5 w-5 mt-0.5 text-brand-600 flex-shrink-0" />
                            <span>{{ $bullet }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
            <a href="{{ route('about') }}" class="btn btn-md mt-8 bg-ink-800 text-white hover:bg-ink-900 inline-flex items-center gap-2">
                About &amp; Team
                <x-lucide name="arrow-right" class="h-4 w-4" />
            </a>
        </div>

        <div class="relative">
            <div class="relative aspect-[4/3] rounded-2xl overflow-hidden shadow-card">
                <img
                    src="{{ $leadMember?->photoUrl(asset('images/ruben-metcalfe.jpg')) ?? asset('images/ruben-metcalfe.jpg') }}"
                    alt="{{ $leadMember?->name ?? config('rdm.owner') }} — {{ $leadMember?->title ?? 'owner of ' . config('rdm.name') }}"
                    width="768"
                    height="1024"
                    loading="lazy"
                    decoding="async"
                    class="absolute inset-0 h-full w-full object-cover object-top"
                />
                {{-- Dark gradient so the branding stays legible over the photo --}}
                <div class="absolute inset-0 bg-gradient-to-t from-ink-900/85 via-ink-900/25 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8 text-white">
                    <p class="text-4xl sm:text-5xl font-display font-bold tracking-tight">Pretoria East</p>
                    <p class="mt-2 text-sm text-brand-100">Garsfontein · Faerie Glen · Moreleta Park · Woodhill · Silver Lakes · Olympus</p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.cta')

@endsection
