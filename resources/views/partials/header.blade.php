<header x-data="{ open: false, scrolled: false }"
        x-init="scrolled = window.scrollY > 8; window.addEventListener('scroll', () => scrolled = window.scrollY > 8)"
        class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-ink-100 transition-shadow"
        :class="scrolled ? 'shadow-card' : ''">
    <div class="container flex items-center justify-between h-20">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="RDM Developments — home">
            <x-rdm-logo class="h-12 w-auto" />
        </a>

        <nav class="hidden lg:flex items-center gap-8" aria-label="Primary">
            @php
                $nav = [
                    ['label' => 'Home',     'route' => 'home',            'match' => 'home'],
                    ['label' => 'Services', 'route' => 'services.index', 'match' => 'services.*'],
                    ['label' => 'Projects', 'route' => 'projects.index', 'match' => 'projects.*'],
                    ['label' => 'About',    'route' => 'about',          'match' => 'about'],
                    ['label' => 'Contact',  'route' => 'contact',        'match' => 'contact'],
                ];
            @endphp
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="text-sm font-semibold uppercase tracking-wide transition
                          {{ request()->routeIs($item['match']) ? 'text-brand-600' : 'text-ink-700 hover:text-brand-600' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden lg:flex items-center gap-3">
            <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-md bg-brand text-white hover:bg-brand-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.89.72 2.78a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.11-.45c.89.35 1.82.59 2.78.72A2 2 0 0 1 22 16.92z"/></svg>
                {{ config('rdm.phone') }}
            </a>
        </div>

        <button @click="open = !open" class="lg:hidden p-2 -mr-2 text-ink-700" aria-label="Toggle menu" aria-expanded="false" :aria-expanded="open.toString()">
            <svg x-show="!open" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            <svg x-show="open" x-cloak class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <div x-show="open" x-cloak x-transition.origin.top class="lg:hidden border-t border-ink-100 bg-white">
        <div class="container py-4 flex flex-col gap-1">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="py-2.5 text-sm font-semibold uppercase tracking-wide
                          {{ request()->routeIs($item['match']) ? 'text-brand-600' : 'text-ink-700' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
            <div class="flex flex-col sm:flex-row gap-2 pt-3">
                <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-md bg-brand text-white hover:bg-brand-600 flex-1">
                    Call {{ config('rdm.phone') }}
                </a>
                <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode(config('rdm.whatsapp_greeting')) }}"
                   target="_blank" rel="noopener"
                   class="btn btn-md bg-[#25D366] text-white hover:bg-[#1fb957] flex-1">
                    WhatsApp
                </a>
            </div>
        </div>
    </div>
</header>
