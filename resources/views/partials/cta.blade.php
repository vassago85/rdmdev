@php
    $heading = $heading ?? 'Ready to start your project?';
    $subtext = $subtext ?? 'Speak directly to ' . config('rdm.owner') . ' — honest advice, a clear quote, and a finish you can be proud of.';
@endphp
<section class="bg-brand-600 text-white">
    <div class="container py-14 lg:py-20 grid gap-8 lg:grid-cols-[1.4fr_1fr] lg:items-center">
        <div>
            <p class="eyebrow !text-brand-100">Let's talk</p>
            <h2 class="!text-white mt-2">{{ $heading }}</h2>
            <p class="mt-4 text-brand-50/90 max-w-xl text-lg leading-relaxed">{{ $subtext }}</p>
        </div>
        <div class="flex flex-wrap gap-3 lg:justify-end">
            <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-lg bg-white text-brand-700 hover:bg-brand-50">
                <x-lucide name="phone" class="h-5 w-5" />
                Call {{ config('rdm.phone') }}
            </a>
            <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode(config('rdm.whatsapp_greeting')) }}"
               target="_blank" rel="noopener"
               class="btn-whatsapp">
                <x-lucide name="message-circle" class="h-5 w-5" />
                WhatsApp
            </a>
            <a href="{{ route('contact') }}#enquiry" class="btn-outline">
                <x-lucide name="file-text" class="h-5 w-5" />
                Request a quote
            </a>
        </div>
    </div>
</section>
