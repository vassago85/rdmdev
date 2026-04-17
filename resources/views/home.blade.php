@extends('layouts.app')

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-ink-800 text-white">
    <div aria-hidden="true" class="absolute inset-0 opacity-20">
        <svg class="absolute -right-10 -top-10 h-[500px] w-[500px] text-brand-400" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 100 100">
            <path d="M0 70 L50 10 L100 70" />
            <path d="M20 70 L50 30 L80 70" />
        </svg>
    </div>

    <div class="container relative grid gap-12 py-16 sm:py-20 lg:py-28 lg:grid-cols-[1.4fr_1fr] lg:items-center">
        <div>
            <p class="eyebrow !text-brand-200">Pretoria East · Gauteng</p>
            <h1 class="!text-white mt-4">Reliable Building &amp; Renovation Contractors in Pretoria&nbsp;East</h1>
            <p class="mt-6 text-lg sm:text-xl text-ink-100/90 max-w-2xl leading-relaxed">
                {{ config('rdm.name') }} is an owner-managed construction business run by
                {{ config('rdm.owner') }}. Small team, clear communication, and proper finishes —
                from bathroom renovations to full home remodels and custom builds.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
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

            <ul class="mt-10 grid gap-4 sm:grid-cols-3 text-sm text-ink-100/90">
                <li class="flex items-start gap-3">
                    <span class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-brand-500/20 text-brand-200">
                        <x-lucide name="shield-check" class="h-5 w-5" />
                    </span>
                    <span class="pt-1.5 font-medium">Owner-managed projects</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-brand-500/20 text-brand-200">
                        <x-lucide name="file-text" class="h-5 w-5" />
                    </span>
                    <span class="pt-1.5 font-medium">Honest, itemised quotes</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-brand-500/20 text-brand-200">
                        <x-lucide name="check-circle-2" class="h-5 w-5" />
                    </span>
                    <span class="pt-1.5 font-medium">Clean finishes, on time</span>
                </li>
            </ul>
        </div>

        {{-- OWNER / TRUST CARD --}}
        <div class="lg:pl-6">
            <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl">
                <div class="flex items-center gap-3 mb-5">
                    <div class="h-12 w-12 rounded-full bg-brand-500 text-white grid place-items-center font-bold text-lg">RM</div>
                    <div>
                        <p class="font-semibold text-white leading-tight text-lg">{{ config('rdm.owner') }}</p>
                        <p class="text-sm text-ink-200">Owner · RDM Developments</p>
                    </div>
                </div>
                <p class="text-ink-100 leading-relaxed italic">
                    "Every project I quote, I run. No middlemen, no surprises — just good work, on time, on budget."
                </p>

                <ul class="mt-6 space-y-2.5 text-sm text-ink-100">
                    <li class="flex items-center gap-2.5">
                        <x-lucide name="check-circle-2" class="h-4 w-4 text-brand-300" />
                        Owner on every project
                    </li>
                    <li class="flex items-center gap-2.5">
                        <x-lucide name="check-circle-2" class="h-4 w-4 text-brand-300" />
                        No middlemen
                    </li>
                    <li class="flex items-center gap-2.5">
                        <x-lucide name="check-circle-2" class="h-4 w-4 text-brand-300" />
                        Pretoria East focused
                    </li>
                </ul>

                <div class="mt-6 flex flex-col gap-2">
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

{{-- SERVICES --}}
<section class="section">
    <div class="container">
        <div class="max-w-2xl">
            <p class="eyebrow">What we do</p>
            <h2 class="mt-2">Services across Pretoria East</h2>
            <p class="mt-4 text-lg text-ink-500 leading-relaxed">
                From full home renovations to standalone bathrooms and one-off custom builds —
                every service is personally supervised by {{ config('rdm.owner') }}, from the first
                quote to the final clean-up.
            </p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $service)
                <a href="{{ route('services.show', $service->slug) }}"
                   class="card p-6 block group transition hover:shadow-lg hover:-translate-y-0.5 hover:border-brand-200">
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-brand-50 text-brand-600 mb-5 group-hover:bg-brand-600 group-hover:text-white transition">
                        <x-service-icon :icon="$service->icon ?: 'house'" />
                    </div>
                    <h3 class="!text-xl group-hover:text-brand-600 transition">{{ $service->title }}</h3>
                    @if ($service->excerpt)
                        <p class="mt-3 text-ink-500 text-[15px] leading-relaxed">{{ \Illuminate\Support\Str::limit($service->excerpt, 155) }}</p>
                    @endif
                    <p class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 group-hover:gap-2.5 transition-all">
                        Learn more
                        <x-lucide name="arrow-right" class="h-4 w-4" />
                    </p>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- WHY CHOOSE RDM --}}
<section class="section bg-ink-50/60 border-y border-ink-100">
    <div class="container">
        <div class="max-w-2xl">
            <p class="eyebrow">Why RDM</p>
            <h2 class="mt-2">A small, focused team — and it shows in the finish</h2>
            <p class="mt-4 text-lg text-ink-500 leading-relaxed">
                The difference between a good renovation and a frustrating one is almost always
                the person running it. Here's what working with us looks like.
            </p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $why = [
                    ['icon' => 'users',          'title' => 'Small, focused team',      'body' => 'We stay deliberately small so every project gets real attention — no call centre, no juggling twenty sites at once.'],
                    ['icon' => 'shield-check',   'title' => 'Personally supervised',    'body' => 'Ruben is on-site and responsible for the work — the person who quoted your job is the person who runs it.'],
                    ['icon' => 'message-circle', 'title' => 'Reliable communication',   'body' => 'Straight answers on WhatsApp or the phone. You always know where the project is and what happens next.'],
                    ['icon' => 'sparkles',       'title' => 'Clean, well-finished',     'body' => 'Tidy sites, careful tradesmen, and the kind of finish you only get when someone is paying attention to the details.'],
                ];
            @endphp
            @foreach ($why as $item)
                <div class="card p-6 hover:shadow-lg transition">
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

{{-- FEATURED PROJECTS --}}
@if ($featuredProjects->count())
<section class="section">
    <div class="container">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div class="max-w-xl">
                <p class="eyebrow">Recent work</p>
                <h2 class="mt-2">Featured projects</h2>
                <p class="mt-4 text-ink-500 leading-relaxed">A selection of the renovations and builds we've completed across Pretoria East.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="btn-ghost !text-base inline-flex items-center gap-1.5">
                View all projects
                <x-lucide name="arrow-right" class="h-4 w-4" />
            </a>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($featuredProjects as $project)
                @include('partials.project-card', ['project' => $project])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- AREAS WE SERVE --}}
<section class="section bg-ink-50/60 border-y border-ink-100">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-[1fr_1.3fr] lg:items-center">
            <div>
                <p class="eyebrow">Where we work</p>
                <h2 class="mt-2">Areas We Serve in Pretoria East</h2>
                <p class="mt-4 text-lg text-ink-500 leading-relaxed">
                    We focus on Pretoria East so we can be on-site fast and stay properly involved
                    with every project — not stretched thin across the whole of Gauteng.
                </p>
                <div class="mt-6 inline-flex items-center gap-2 text-brand-700 font-semibold">
                    <x-lucide name="map-pin" class="h-5 w-5" />
                    <span>Pretoria East, Gauteng</span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @php
                    $homeSuburbs = [
                        'Faerie Glen', 'Garsfontein', 'Moreleta Park',
                        'Silver Lakes', 'Woodhill', 'Elardus Park',
                        'Olympus', 'Lynnwood', 'Wapadrand',
                    ];
                @endphp
                @foreach ($homeSuburbs as $suburb)
                    <div class="card px-4 py-3 flex items-center gap-2.5 text-sm font-semibold text-ink-700 hover:border-brand-200 hover:text-brand-700 transition">
                        <x-lucide name="map-pin" class="h-4 w-4 text-brand-500" />
                        {{ $suburb }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ABOUT PREVIEW --}}
<section class="section">
    <div class="container grid gap-10 lg:grid-cols-2 lg:items-center">
        <div>
            <p class="eyebrow">About RDM</p>
            <h2 class="mt-2">Small, owner-managed, personally supervised</h2>
            <p class="mt-5 text-lg text-ink-500 leading-relaxed">
                {{ config('rdm.name') }} is a Pretoria East–based construction and renovation business
                owned and operated by {{ config('rdm.owner') }}. We stay small on purpose — it's the
                only way to make sure every project is properly supervised and properly finished.
            </p>
            <ul class="mt-6 space-y-3 text-ink-700">
                <li class="flex gap-3 items-start">
                    <x-lucide name="check-circle-2" class="h-5 w-5 mt-0.5 text-brand-600 flex-shrink-0" />
                    <span>No sub-contracting of project management — Ruben runs every job.</span>
                </li>
                <li class="flex gap-3 items-start">
                    <x-lucide name="check-circle-2" class="h-5 w-5 mt-0.5 text-brand-600 flex-shrink-0" />
                    <span>Focused on Pretoria East so we can be on-site fast.</span>
                </li>
                <li class="flex gap-3 items-start">
                    <x-lucide name="check-circle-2" class="h-5 w-5 mt-0.5 text-brand-600 flex-shrink-0" />
                    <span>Clear quotes, honest timelines, tidy sites.</span>
                </li>
            </ul>
            <a href="{{ route('about') }}" class="btn btn-md mt-8 bg-ink-800 text-white hover:bg-ink-900 inline-flex items-center gap-2">
                Read more about us
                <x-lucide name="arrow-right" class="h-4 w-4" />
            </a>
        </div>

        <div class="relative">
            <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 shadow-card p-8 text-white flex flex-col justify-between">
                {{-- Intentionally stretched vertically (double height, same width)
                     — uses a direct <img> to bypass the component's aspect-locked
                     width/height HTML attrs. --}}
                <img
                    src="{{ asset('images/rdmdev-logo-480.png') }}"
                    srcset="{{ asset('images/rdmdev-logo-480.png') }} 1x, {{ asset('images/rdmdev-logo-960.png') }} 2x"
                    alt="RDM Developments — Building &amp; Renovation"
                    class="block"
                    style="width: 134px; height: 112px; filter: brightness(0) invert(1);"
                />
                <div>
                    <p class="text-5xl sm:text-6xl font-display font-bold tracking-tight">Pretoria East</p>
                    <p class="mt-2 text-brand-100">Garsfontein · Faerie Glen · Moreleta Park · Woodhill · Silver Lakes · Olympus</p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.cta')

@endsection
