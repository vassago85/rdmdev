@extends('layouts.app')

@section('content')

<section class="bg-ink-800 text-white">
    <div class="container py-16 sm:py-20">
        <p class="eyebrow !text-brand-200">Services</p>
        <h1 class="!text-white mt-3 max-w-3xl">Building &amp; renovation services across Pretoria East</h1>
        <p class="mt-5 text-lg text-ink-100/90 max-w-2xl">
            Pick the service you need to learn more, or get in touch directly —
            {{ config('rdm.owner') }} will talk you through what's possible.
        </p>
    </div>
</section>

<section class="section">
    <div class="container grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($services as $service)
            <a href="{{ route('services.show', $service->slug) }}"
               class="card p-6 hover:shadow-lg hover:-translate-y-0.5 transition block group">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-brand-50 text-brand-600 mb-5">
                    <x-service-icon :icon="$service->icon ?: 'home'" />
                </div>
                <h2 class="!text-xl group-hover:text-brand-600 transition">{{ $service->title }}</h2>
                @if ($service->tagline)
                    <p class="mt-1 text-sm font-semibold text-brand-600">{{ $service->tagline }}</p>
                @endif
                @if ($service->excerpt)
                    <p class="mt-3 text-ink-500 text-[15px] leading-relaxed">{{ \Illuminate\Support\Str::limit($service->excerpt, 170) }}</p>
                @endif
                <p class="mt-5 text-sm font-semibold text-brand-600">Learn more &rarr;</p>
            </a>
        @endforeach
    </div>
</section>

@include('partials.cta')

@endsection
