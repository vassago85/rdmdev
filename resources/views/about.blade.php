@extends('layouts.app')

@section('content')

<section class="bg-ink-800 text-white">
    <div class="container py-16 sm:py-20 grid gap-10 lg:grid-cols-[1.3fr_1fr] lg:items-center">
        <div>
            <p class="eyebrow !text-brand-200">About RDM</p>
            <h1 class="!text-white mt-3">Personally supervised construction in Pretoria East</h1>
            <p class="mt-5 text-lg text-ink-100/90 max-w-2xl">
                {{ config('rdm.name') }} is a Pretoria East–based construction and renovation
                business owned and operated by {{ config('rdm.owner') }}. As a small,
                owner-managed company, every project is personally supervised to ensure
                quality workmanship and reliable delivery.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-lg bg-white text-brand-700 hover:bg-brand-50">Call {{ config('rdm.phone') }}</a>
                <a href="{{ route('contact') }}#enquiry" class="btn-outline">Request a quote</a>
            </div>
        </div>
        <div>
            <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 p-8 text-white flex flex-col justify-between">
                <x-rdm-logo variant="light" class="h-14 w-auto" />
                <div>
                    <p class="text-3xl sm:text-4xl font-display font-bold">{{ config('rdm.owner') }}</p>
                    <p class="mt-2 text-brand-100">Owner &amp; Project Supervisor</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container grid gap-12 lg:grid-cols-3">
        <div class="lg:col-span-2 prose-rdm">
            <h2>Why homeowners trust RDM Developments</h2>
            <p>
                Most issues on construction sites come down to one thing: nobody with skin in the
                game is actually watching the work. At {{ config('rdm.name') }}, that problem is
                solved by design. The owner, {{ config('rdm.owner') }}, is on-site and personally
                responsible for every project we take on.
            </p>
            <p>
                We stay deliberately small. We don't have a sales team, we don't chase jobs all
                over Gauteng, and we don't juggle more projects than we can personally supervise.
                That's why clients in Garsfontein, Faerie Glen, Moreleta Park, Woodhill, Silver
                Lakes and the rest of Pretoria East come back to us and refer their neighbours.
            </p>

            <h3>What you can expect</h3>
            <ul>
                <li><strong>Honest quotes.</strong> Clear scope, clear pricing, no hidden extras.</li>
                <li><strong>Owner on-site.</strong> Ruben is your direct line for the whole project.</li>
                <li><strong>Tidy sites.</strong> We respect that you live there — we protect, cover and clean.</li>
                <li><strong>Quality tradesmen.</strong> Same plumbers, sparkies and tilers, project after project.</li>
                <li><strong>Realistic timelines.</strong> Promises we can keep, not ones that look good on paper.</li>
            </ul>

            <h3>Where we work</h3>
            <p>
                We focus on Pretoria East suburbs including Garsfontein, Faerie Glen, Moreleta
                Park, Woodhill, Silver Lakes, Olympus, Wapadrand, Elarduspark, Lynnwood and Menlo
                Park. Working in a tight geographic area means we can be responsive when issues
                come up on-site — because they always do.
            </p>
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

@include('partials.cta')

@endsection
