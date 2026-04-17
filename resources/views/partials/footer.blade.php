<footer class="bg-ink-800 text-ink-100 mt-24">
    <div class="container py-14 grid gap-10 md:grid-cols-4">
        <div class="md:col-span-2">
            <x-rdm-logo variant="light" class="h-12 w-auto mb-4" />
            <p class="text-ink-200 max-w-md">
                {{ config('rdm.name') }} is a small, owner-managed construction and renovation business based in Pretoria East.
                Every project is personally supervised by {{ config('rdm.owner') }}.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-md bg-white/10 text-white hover:bg-white/20">Call {{ config('rdm.phone') }}</a>
                <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode(config('rdm.whatsapp_greeting')) }}"
                   target="_blank" rel="noopener"
                   class="btn btn-md bg-[#25D366] text-white hover:bg-[#1fb957]">WhatsApp</a>
            </div>
        </div>

        <div>
            <h4 class="text-sm font-semibold tracking-wide uppercase text-white mb-4">Services</h4>
            <ul class="space-y-2 text-ink-200 text-sm">
                @foreach (\App\Models\Service::published()->ordered()->take(6)->get() as $s)
                    <li><a class="hover:text-white" href="{{ route('services.show', $s->slug) }}">{{ $s->title }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h4 class="text-sm font-semibold tracking-wide uppercase text-white mb-4">Company</h4>
            <ul class="space-y-2 text-ink-200 text-sm">
                <li><a class="hover:text-white" href="{{ route('projects.index') }}">Projects</a></li>
                <li><a class="hover:text-white" href="{{ route('about') }}">About</a></li>
                <li><a class="hover:text-white" href="{{ route('contact') }}">Contact</a></li>
                <li><a class="hover:text-white" href="mailto:{{ config('rdm.email') }}">{{ config('rdm.email') }}</a></li>
                <li class="text-ink-300">{{ config('rdm.location') }}</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="container py-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-ink-300">
            <p>&copy; {{ date('Y') }} RDM Developments (Pty) Ltd. All rights reserved.</p>
            <p>Pretoria East · Gauteng · South Africa</p>
        </div>
    </div>
</footer>
