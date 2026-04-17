@extends('layouts.app')

@section('content')

<section class="bg-ink-800 text-white">
    <div class="container py-16 sm:py-20">
        <p class="eyebrow !text-brand-200">Get in touch</p>
        <h1 class="!text-white mt-3 max-w-3xl">Request a Quote for Building or Renovation Work in Pretoria&nbsp;East</h1>
        <p class="mt-5 text-lg text-ink-100/90 max-w-2xl leading-relaxed">
            Whether it's a bathroom renovation, a new build, or a custom project in between —
            {{ config('rdm.owner') }} will personally talk you through what's possible, what it
            costs, and how long it takes. Honest answers, no pressure.
        </p>
    </div>
</section>

<section class="section">
    <div class="container grid gap-10 lg:grid-cols-[1.3fr_1fr]">
        @include('partials.enquiry-form', ['source' => 'contact_page'])

        <aside class="space-y-6">
            {{-- Ruben contact card --}}
            <div class="card p-6 sm:p-7">
                <div class="flex items-center gap-3 mb-5">
                    <div class="h-12 w-12 rounded-full bg-brand-600 text-white grid place-items-center font-bold text-lg">RM</div>
                    <div>
                        <p class="font-semibold text-ink-800 leading-tight text-lg">{{ config('rdm.owner') }}</p>
                        <p class="text-sm text-ink-500">Owner · RDM Developments</p>
                    </div>
                </div>
                <p class="text-ink-600 italic leading-relaxed">
                    "I run every job myself — so whatever you need, speak to me directly."
                </p>

                <ul class="mt-6 space-y-4 text-ink-700">
                    <li class="flex items-start gap-3">
                        <span class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                            <x-lucide name="phone" class="h-5 w-5" />
                        </span>
                        <div>
                            <p class="text-xs uppercase text-ink-400 tracking-wide font-semibold">Call Ruben</p>
                            <a class="text-lg font-display font-bold text-brand-700 hover:text-brand-600 transition" href="tel:{{ config('rdm.phone_tel') }}">{{ config('rdm.phone') }}</a>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <span class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                            <x-lucide name="message-circle" class="h-5 w-5" />
                        </span>
                        <div class="flex-1">
                            <p class="text-xs uppercase text-ink-400 tracking-wide font-semibold">WhatsApp</p>
                            <a class="btn btn-md bg-[#25D366] text-white hover:bg-[#1fb957] mt-1.5"
                               target="_blank" rel="noopener"
                               href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode(config('rdm.whatsapp_greeting')) }}">
                                <x-lucide name="message-circle" class="h-4 w-4" />
                                Chat on WhatsApp
                            </a>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <span class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                            <x-lucide name="mail" class="h-5 w-5" />
                        </span>
                        <div>
                            <p class="text-xs uppercase text-ink-400 tracking-wide font-semibold">Email</p>
                            <a class="font-semibold link-underline" href="mailto:{{ config('rdm.email') }}">{{ config('rdm.email') }}</a>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <span class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                            <x-lucide name="clock-3" class="h-5 w-5" />
                        </span>
                        <div>
                            <p class="text-xs uppercase text-ink-400 tracking-wide font-semibold">Hours</p>
                            <p class="font-semibold text-ink-700">Mon – Fri · 07:00 – 17:00</p>
                            <p class="text-sm text-ink-500">Saturday by arrangement</p>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <span class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                            <x-lucide name="map-pin" class="h-5 w-5" />
                        </span>
                        <div>
                            <p class="text-xs uppercase text-ink-400 tracking-wide font-semibold">Service area</p>
                            <p class="font-semibold text-ink-700">{{ config('rdm.location') }}</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="card p-6 bg-brand-50 border-brand-100">
                <div class="flex items-start gap-3">
                    <x-lucide name="message-circle" class="h-5 w-5 text-brand-600 mt-1 flex-shrink-0" />
                    <p class="text-sm text-ink-700 leading-relaxed">
                        After hours? Send a WhatsApp — we'll reply first thing the next working day.
                    </p>
                </div>
            </div>
        </aside>
    </div>
</section>

{{-- AREAS WE SERVE --}}
<section class="section-tight bg-ink-50/60 border-y border-ink-100">
    <div class="container">
        <div class="max-w-2xl">
            <p class="eyebrow">Where we work</p>
            <h2 class="mt-2 !text-2xl sm:!text-3xl">Areas We Serve in Pretoria East</h2>
            <p class="mt-3 text-ink-500 leading-relaxed">
                We're focused on Pretoria East so we can be on-site fast and stay close to every project.
            </p>
        </div>
        <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach (config('rdm.suburbs') as $suburb)
                <div class="card px-4 py-3 flex items-center gap-2.5 text-sm font-semibold text-ink-700 hover:border-brand-200 hover:text-brand-700 transition">
                    <x-lucide name="map-pin" class="h-4 w-4 text-brand-500" />
                    {{ $suburb }}
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('partials.cta')

@endsection
