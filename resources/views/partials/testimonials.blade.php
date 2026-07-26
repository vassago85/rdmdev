@php
    /**
     * PLACEHOLDER scaffold — populate config('rdm.testimonials') with real
     * customer quotes before this section is considered live social proof.
     * Empty array = section hidden.
     */
    $items = collect($testimonials ?? config('rdm.testimonials', []))
        ->filter(fn ($t) => filled($t['quote'] ?? null) && filled($t['name'] ?? null))
        ->values();
@endphp

@if ($items->isNotEmpty())
<section class="section bg-ink-50/60 border-y border-ink-100">
    <div class="container">
        <div class="max-w-2xl mx-auto text-center">
            <p class="eyebrow">What clients say</p>
            <h2 class="mt-2">Trusted across Pretoria East</h2>
            <p class="mt-4 text-lg text-ink-500 leading-relaxed">
                Straight feedback from homeowners we've worked with.
            </p>
        </div>

        <div class="mt-12 grid gap-6 {{ $items->count() === 1 ? 'max-w-xl mx-auto' : ($items->count() === 2 ? 'sm:grid-cols-2 max-w-4xl mx-auto' : 'sm:grid-cols-2 lg:grid-cols-3') }}">
            @foreach ($items as $item)
                <figure class="card p-6 sm:p-7 flex flex-col">
                    @php $rating = (int) ($item['rating'] ?? 5); @endphp
                    <div class="flex gap-0.5 text-accent" aria-label="{{ $rating }} out of 5 stars">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="h-5 w-5 {{ $i < $rating ? 'fill-current' : 'fill-ink-200' }}" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M10 1.5l2.39 4.84 5.34.78-3.86 3.76.91 5.32L10 13.9l-4.78 2.5.91-5.32L2.27 7.12l5.34-.78L10 1.5z"/>
                            </svg>
                        @endfor
                    </div>
                    <blockquote class="mt-4 flex-1 text-ink-700 leading-relaxed text-[15px]">
                        “{{ $item['quote'] }}”
                    </blockquote>
                    <figcaption class="mt-5 pt-4 border-t border-ink-100">
                        <p class="font-semibold text-ink-800">{{ $item['name'] }}</p>
                        @if (! empty($item['suburb']))
                            <p class="text-sm text-ink-500">{{ $item['suburb'] }}, Pretoria East</p>
                        @endif
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif
