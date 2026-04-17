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
            <p class="mt-5 text-lg text-ink-100/90 max-w-2xl">{{ $service->tagline }}</p>
        @endif

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-lg bg-white text-brand-700 hover:bg-brand-50">Call {{ config('rdm.phone') }}</a>
            <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode('Hi Ruben, I would like a quote for: '.$service->title) }}"
               target="_blank" rel="noopener" class="btn-whatsapp">WhatsApp for a quote</a>
            <a href="#enquiry" class="btn-outline">Request a quote</a>
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
                <h3 class="!text-lg mb-3">Why homeowners choose RDM</h3>
                <ul class="space-y-2 text-ink-700">
                    <li class="flex gap-2"><span class="mt-1 h-2 w-2 bg-brand-500 rounded-full flex-shrink-0"></span>Owner on-site every project</li>
                    <li class="flex gap-2"><span class="mt-1 h-2 w-2 bg-brand-500 rounded-full flex-shrink-0"></span>Clear, itemised quotes</li>
                    <li class="flex gap-2"><span class="mt-1 h-2 w-2 bg-brand-500 rounded-full flex-shrink-0"></span>Tidy sites, careful with your home</li>
                    <li class="flex gap-2"><span class="mt-1 h-2 w-2 bg-brand-500 rounded-full flex-shrink-0"></span>Focused on Pretoria East suburbs</li>
                </ul>
            </div>

            <div class="card p-6 bg-brand-50 border-brand-100">
                <h3 class="!text-lg mb-2">Call {{ config('rdm.owner') }} directly</h3>
                <p class="text-ink-600 mb-4">Quickest way to get a quote for your {{ \Illuminate\Support\Str::lower($service->title) }}.</p>
                <div class="flex flex-wrap gap-2">
                    <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-md bg-brand text-white hover:bg-brand-600">Call {{ config('rdm.phone') }}</a>
                    <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode('Hi Ruben, I would like a quote for: '.$service->title) }}"
                       target="_blank" rel="noopener" class="btn btn-md bg-[#25D366] text-white hover:bg-[#1fb957]">WhatsApp</a>
                </div>
            </div>
        </aside>
    </div>
</section>

<section class="section-tight bg-ink-50/50" id="enquiry">
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
                <a href="{{ route('services.show', $other->slug) }}" class="card p-5 hover:shadow-lg hover:-translate-y-0.5 transition block group">
                    <div class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-brand-50 text-brand-600 mb-3">
                        <x-service-icon :icon="$other->icon ?: 'home'" />
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
