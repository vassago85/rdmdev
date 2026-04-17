@extends('layouts.app')

@section('content')

<section class="bg-ink-800 text-white">
    <div class="container py-14 sm:py-20">
        <nav class="text-sm text-ink-200 mb-5" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white">Home</a>
            <span class="mx-1.5 text-ink-400">/</span>
            <a href="{{ route('services.index') }}" class="hover:text-white">Services</a>
            <span class="mx-1.5 text-ink-400">/</span>
            <span class="text-white">{{ $service->title }}</span>
        </nav>

        <p class="eyebrow !text-brand-200">Service · Pretoria East</p>
        <h1 class="!text-white mt-3 max-w-3xl">{{ $service->title }}</h1>
        @if ($service->tagline)
            <p class="mt-5 text-lg text-ink-100/90 max-w-2xl leading-relaxed">{{ $service->tagline }}</p>
        @endif

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-lg bg-white text-brand-700 hover:bg-brand-50">
                <x-lucide name="phone" class="h-5 w-5" />
                Call {{ config('rdm.phone') }}
            </a>
            <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode('Hi Ruben, I would like a quote for: '.$service->title) }}"
               target="_blank" rel="noopener" class="btn-whatsapp">
                <x-lucide name="message-circle" class="h-5 w-5" />
                WhatsApp for a quote
            </a>
            <a href="#enquiry" class="btn-outline">
                <x-lucide name="file-text" class="h-5 w-5" />
                Request a quote
            </a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container grid gap-10 lg:grid-cols-[1.5fr_1fr]">
        <article class="prose-rdm">
            {!! $service->description !!}
        </article>

        <aside class="space-y-6">
            <div class="card p-6">
                <h3 class="!text-lg mb-4">Why homeowners choose RDM</h3>
                <ul class="space-y-3 text-ink-700">
                    <li class="flex gap-3 items-start">
                        <x-lucide name="shield-check" class="h-5 w-5 mt-0.5 text-brand-600 flex-shrink-0" />
                        <span>Owner on-site every project</span>
                    </li>
                    <li class="flex gap-3 items-start">
                        <x-lucide name="file-text" class="h-5 w-5 mt-0.5 text-brand-600 flex-shrink-0" />
                        <span>Clear, itemised quotes</span>
                    </li>
                    <li class="flex gap-3 items-start">
                        <x-lucide name="sparkles" class="h-5 w-5 mt-0.5 text-brand-600 flex-shrink-0" />
                        <span>Tidy sites, careful with your home</span>
                    </li>
                    <li class="flex gap-3 items-start">
                        <x-lucide name="map-pin" class="h-5 w-5 mt-0.5 text-brand-600 flex-shrink-0" />
                        <span>Focused on Pretoria East suburbs</span>
                    </li>
                </ul>
            </div>

            <div class="card p-6 bg-brand-50 border-brand-100">
                <h3 class="!text-lg mb-2">Call {{ config('rdm.owner') }} directly</h3>
                <p class="text-ink-600 mb-4">Quickest way to get a quote for your {{ \Illuminate\Support\Str::lower($service->title) }}.</p>
                <div class="flex flex-wrap gap-2">
                    <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-md bg-brand text-white hover:bg-brand-600">
                        <x-lucide name="phone" class="h-4 w-4" />
                        {{ config('rdm.phone') }}
                    </a>
                    <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode('Hi Ruben, I would like a quote for: '.$service->title) }}"
                       target="_blank" rel="noopener" class="btn btn-md bg-[#25D366] text-white hover:bg-[#1fb957]">
                        <x-lucide name="message-circle" class="h-4 w-4" />
                        WhatsApp
                    </a>
                </div>
            </div>
        </aside>
    </div>
</section>

<section class="section-tight bg-ink-50/60 border-y border-ink-100" id="enquiry">
    <div class="container max-w-3xl">
        @include('partials.enquiry-form', ['defaultService' => $service->title, 'source' => 'service:'.$service->slug])
    </div>
</section>

@if ($others->count())
<section class="section-tight">
    <div class="container">
        <h2 class="!text-2xl mb-8">Other services</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($others as $other)
                <a href="{{ route('services.show', $other->slug) }}" class="card p-5 hover:shadow-lg hover:-translate-y-0.5 hover:border-brand-200 transition block group">
                    <div class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-brand-50 text-brand-600 mb-3 group-hover:bg-brand-600 group-hover:text-white transition">
                        <x-service-icon :icon="$other->icon ?: 'house'" class="h-6 w-6" />
                    </div>
                    <h3 class="!text-base group-hover:text-brand-600 transition">{{ $other->title }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.cta')

@endsection
