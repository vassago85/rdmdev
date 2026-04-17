@extends('layouts.app')

@section('content')

<section class="bg-ink-800 text-white">
    <div class="container py-16 sm:py-20">
        <p class="eyebrow !text-brand-200">Get in touch</p>
        <h1 class="!text-white mt-3 max-w-3xl">Let's talk about your project in Pretoria East</h1>
        <p class="mt-5 text-lg text-ink-100/90 max-w-2xl">
            Whether it's a bathroom renovation, a new build, or something in between,
            {{ config('rdm.owner') }} will personally talk you through what's possible.
        </p>
    </div>
</section>

<section class="section">
    <div class="container grid gap-10 lg:grid-cols-[1.3fr_1fr]">
        @include('partials.enquiry-form', ['source' => 'contact_page'])

        <aside class="space-y-6">
            <div class="card p-6">
                <h3 class="!text-lg">Quick contact</h3>
                <ul class="mt-4 space-y-4 text-ink-700">
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">Call</p>
                        <a class="text-2xl font-display font-bold text-brand-700" href="tel:{{ config('rdm.phone_tel') }}">{{ config('rdm.phone') }}</a>
                    </li>
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">WhatsApp</p>
                        <a class="btn btn-md bg-[#25D366] text-white hover:bg-[#1fb957]"
                           target="_blank" rel="noopener"
                           href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode(config('rdm.whatsapp_greeting')) }}">
                            Chat on WhatsApp
                        </a>
                    </li>
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">Email</p>
                        <a class="font-semibold link-underline" href="mailto:{{ config('rdm.email') }}">{{ config('rdm.email') }}</a>
                    </li>
                    <li>
                        <p class="text-xs uppercase text-ink-400 tracking-wide">Serving</p>
                        <p class="font-semibold">{{ config('rdm.location') }}</p>
                    </li>
                </ul>
            </div>

            <div class="card p-6 bg-ink-800 text-white border-ink-800">
                <h3 class="!text-lg !text-white">Weekday hours</h3>
                <p class="mt-2 text-ink-200 text-sm">Mon – Fri · 07:00 – 17:00<br>Saturday · by arrangement</p>
                <p class="mt-4 text-ink-300 text-sm">After hours? Send a WhatsApp and we'll reply first thing.</p>
            </div>
        </aside>
    </div>
</section>

@endsection
