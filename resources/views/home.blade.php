@extends('layouts.app')

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-ink-800 text-white">
    <div aria-hidden="true" class="absolute inset-0 opacity-25">
        <svg class="absolute -right-10 -top-10 h-[500px] w-[500px] text-brand-400" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 100 100">
            <path d="M0 70 L50 10 L100 70" />
            <path d="M20 70 L50 30 L80 70" />
        </svg>
    </div>

    <div class="container relative grid gap-10 py-16 sm:py-20 lg:py-28 lg:grid-cols-[1.4fr_1fr] lg:items-center">
        <div>
            <p class="eyebrow !text-brand-200">Pretoria East · Gauteng</p>
            <h1 class="!text-white mt-4">Building &amp; Renovation Specialists in Pretoria&nbsp;East</h1>
            <p class="mt-6 text-lg sm:text-xl text-ink-100/90 max-w-2xl">
                Owner-managed construction services by {{ config('rdm.owner') }}.
                Small team, personally supervised projects, and finishes you'll be proud to live with.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-lg bg-white text-brand-700 hover:bg-brand-50">
                    Call now {{ config('rdm.phone') }}
                </a>
                <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode(config('rdm.whatsapp_greeting')) }}"
                   target="_blank" rel="noopener"
                   class="btn-whatsapp">
                    WhatsApp
                </a>
                <a href="{{ route('contact') }}#enquiry" class="btn-outline">Request a quote</a>
            </div>

            <ul class="mt-10 grid gap-3 sm:grid-cols-3 text-sm text-ink-100/80">
                <li class="flex items-center gap-2"><span class="inline-block w-2 h-2 rounded-full bg-brand-300"></span>Owner-supervised</li>
                <li class="flex items-center gap-2"><span class="inline-block w-2 h-2 rounded-full bg-brand-300"></span>Honest, itemised quotes</li>
                <li class="flex items-center gap-2"><span class="inline-block w-2 h-2 rounded-full bg-brand-300"></span>Clean sites, clean finish</li>
            </ul>
        </div>

        <div class="lg:pl-6">
            <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-full bg-brand-500 text-white grid place-items-center font-bold">RM</div>
                    <div>
                        <p class="font-semibold text-white leading-tight">{{ config('rdm.owner') }}</p>
                        <p class="text-sm text-ink-200">Owner · RDM Developments</p>
                    </div>
                </div>
                <p class="text-ink-100 leading-relaxed">
                    "Every project I quote, I run. No middlemen, no surprises — just good work, on time, on budget."
                </p>
                <div class="mt-6 flex flex-col gap-2">
                    <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-md bg-brand-500 text-white hover:bg-brand-400">
                        {{ config('rdm.phone') }}
                    </a>
                    <a href="mailto:{{ config('rdm.email') }}" class="btn btn-md bg-white/10 text-white hover:bg-white/20">
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
            <p class="mt-4 text-lg text-ink-500">
                From full home renovations to standalone bathrooms, garage conversions and
                new-build extensions — all personally supervised by {{ config('rdm.owner') }}.
            </p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $service)
                <a href="{{ route('services.show', $service->slug) }}"
                   class="card p-6 hover:shadow-lg hover:-translate-y-0.5 transition block group">
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-brand-50 text-brand-600 mb-5">
                        <x-service-icon :icon="$service->icon ?: 'home'" />
                    </div>
                    <h3 class="!text-xl group-hover:text-brand-600 transition">{{ $service->title }}</h3>
                    @if ($service->excerpt)
                        <p class="mt-2 text-ink-500 text-[15px] leading-relaxed">{{ \Illuminate\Support\Str::limit($service->excerpt, 130) }}</p>
                    @endif
                    <p class="mt-5 text-sm font-semibold text-brand-600">Learn more &rarr;</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- FEATURED PROJECTS --}}
@if ($featuredProjects->count())
<section class="section bg-ink-50/50">
    <div class="container">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div class="max-w-xl">
                <p class="eyebrow">Recent work</p>
                <h2 class="mt-2">Featured projects</h2>
                <p class="mt-4 text-ink-500">A look at some of the renovations and builds we've completed across Pretoria East.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="btn-ghost !text-base">View all projects &rarr;</a>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($featuredProjects as $project)
                @include('partials.project-card', ['project' => $project])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ABOUT PREVIEW --}}
<section class="section">
    <div class="container grid gap-10 lg:grid-cols-2 lg:items-center">
        <div>
            <p class="eyebrow">About RDM</p>
            <h2 class="mt-2">Small, owner-managed, personally supervised</h2>
            <p class="mt-5 text-lg text-ink-500 leading-relaxed">
                {{ config('rdm.name') }} is a Pretoria East–based construction and renovation business
                owned and operated by {{ config('rdm.owner') }}. As a small, owner-managed company,
                every project is personally supervised to ensure quality workmanship and reliable delivery.
            </p>
            <ul class="mt-6 space-y-3 text-ink-700">
                <li class="flex gap-3"><span class="mt-1 h-2 w-2 bg-brand-500 rounded-full flex-shrink-0"></span>No sub-contracting of project management — Ruben runs every job.</li>
                <li class="flex gap-3"><span class="mt-1 h-2 w-2 bg-brand-500 rounded-full flex-shrink-0"></span>Focused on Pretoria East so we can be on-site fast.</li>
                <li class="flex gap-3"><span class="mt-1 h-2 w-2 bg-brand-500 rounded-full flex-shrink-0"></span>Clear quotes, honest timelines, tidy sites.</li>
            </ul>
            <a href="{{ route('about') }}" class="btn btn-md mt-8 bg-ink-800 text-white hover:bg-ink-900">Read more about us</a>
        </div>

        <div class="relative">
            <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 shadow-card p-8 text-white flex flex-col justify-between">
                {{-- Intentionally stretched vertically: same width as `h-14 w-auto` (~134px)
                     but double the height. Uses a direct <img> to bypass the component's
                     aspect-locked width/height HTML attrs. --}}
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
