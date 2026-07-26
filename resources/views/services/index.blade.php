@extends('layouts.app')

@section('content')

<section class="bg-ink-800 text-white">
    <div class="container py-16 sm:py-20">
        <x-breadcrumbs :items="[
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Services'],
        ]" tone="dark" />
        <p class="eyebrow !text-brand-200">Services</p>
        <h1 class="!text-white mt-3 max-w-3xl">Building &amp; renovation services across Pretoria East</h1>
        <p class="mt-5 text-lg text-ink-100 max-w-2xl">
            Pick the service you need to learn more, or get in touch directly —
            {{ config('rdm.owner') }} will talk you through what's possible.
        </p>
    </div>
</section>

<section class="section">
    <div class="container grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($services as $service)
            @include('partials.service-card', ['service' => $service, 'headingTag' => 'h2'])
        @endforeach
    </div>

    <p class="container mt-10 max-w-2xl text-sm text-ink-500 leading-relaxed">
        We do not offer electrical work. Where a project requires it, the client
        appoints their own registered electrician.
    </p>
</section>

@include('partials.cta')

@endsection
