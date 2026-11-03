@php
    $about = $about ?? \App\Models\PageSetting::current();
    $items = collect($about->publishedTestimonials());
@endphp

@if ($items->isNotEmpty())
<section class="section bg-ink-50/60 border-y border-ink-100">
    <div class="container">
        <div class="max-w-2xl mx-auto text-center">
            @if ($about->testimonials_eyebrow)
                <p class="eyebrow">{{ $about->testimonials_eyebrow }}</p>
            @endif
            @if ($about->testimonials_heading)
                <h2 class="mt-2">{{ $about->testimonials_heading }}</h2>
            @endif
            @if ($about->testimonials_intro)
                <p class="mt-4 text-lg text-ink-500 leading-relaxed">{{ $about->testimonials_intro }}</p>
            @endif
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
                    @php
                        $quote = trim((string) $item['quote']);
                        $sentences = preg_split('/(?<=[.!?])\s+/u', $quote, -1, PREG_SPLIT_NO_EMPTY) ?: [$quote];
                        $canExpand = ($expandable ?? false) && count($sentences) > 2;
                        $preview = $canExpand ? $sentences[0] . ' ' . $sentences[1] : $quote;
                    @endphp
                    <blockquote class="mt-4 flex-1 text-ink-700 leading-relaxed text-[15px]" @if ($canExpand) x-data="{ open: false }" @endif>
                        @if ($canExpand)
                            “<span x-show="!open">{{ $preview }}</span><span x-show="open" x-cloak>{{ $quote }}</span>”
                            <button type="button" class="mt-3 block text-sm font-semibold text-brand-600 hover:text-brand-700" @click="open = !open">
                                <span x-show="!open">Read more</span>
                                <span x-show="open" x-cloak>Read less</span>
                            </button>
                        @else
                            “{{ $quote }}”
                        @endif
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

        @php
            $reviewsUrl = config('rdm.google.reviews_url') ?: config('rdm.social.google_business');
        @endphp
        @if (is_string($reviewsUrl) && str_starts_with($reviewsUrl, 'http'))
            <p class="mt-10 text-center">
                <a href="{{ $reviewsUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 font-semibold text-brand-700 hover:text-brand-800">
                    See our Google reviews
                    <x-lucide name="arrow-right" class="h-4 w-4" />
                </a>
            </p>
        @endif
    </div>
</section>
@endif
