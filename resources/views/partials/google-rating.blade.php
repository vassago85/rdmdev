@php
    /**
     * Google rating badge — renders ONLY when real numbers are present in
     * config('rdm.google'). Never shows an invented rating. Populate
     * RDM_GOOGLE_RATING + RDM_GOOGLE_REVIEW_COUNT once the GBP is live.
     */
    $gRating = config('rdm.google.rating');
    $gCount  = config('rdm.google.review_count');
    $gUrl    = config('rdm.google.reviews_url') ?: config('rdm.social.google_business');
    $hasGoogle = filled($gRating) && filled($gCount);

    $ratingValue = $hasGoogle ? (float) $gRating : 0;
    $fullStars   = (int) floor($ratingValue);
    $hasHalf     = ($ratingValue - $fullStars) >= 0.25 && ($ratingValue - $fullStars) < 0.75;
    if (($ratingValue - $fullStars) >= 0.75) { $fullStars++; }

    $tag        = $gUrl ? 'a' : 'span';
    $classes    = ($classes ?? 'inline-flex items-center gap-2.5 rounded-full border border-ink-100 bg-white px-4 py-2 shadow-sm');
@endphp

@if ($hasGoogle)
<{{ $tag }}
    @if ($gUrl) href="{{ $gUrl }}" target="_blank" rel="noopener" @endif
    class="{{ $classes }} {{ $gUrl ? 'transition hover:border-brand-300 hover:shadow' : '' }}"
    aria-label="Rated {{ $gRating }} out of 5 from {{ $gCount }} Google reviews">
    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" aria-hidden="true">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0 0 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 0 1 0-4.2V7.06H2.18a11 11 0 0 0 0 9.88l3.66-2.84z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84C6.71 7.31 9.14 5.38 12 5.38z"/>
    </svg>
    <span class="flex items-center gap-1.5">
        <span class="font-display font-bold text-ink-800 leading-none">{{ $gRating }}</span>
        <span class="flex text-accent" aria-hidden="true">
            @for ($i = 0; $i < 5; $i++)
                @if ($i < $fullStars)
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M10 1.5l2.39 4.84 5.34.78-3.86 3.76.91 5.32L10 13.9l-4.78 2.5.91-5.32L2.27 7.12l5.34-.78L10 1.5z"/></svg>
                @elseif ($i === $fullStars && $hasHalf)
                    <svg class="h-4 w-4" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="rgb(226 232 240)"/></linearGradient></defs><path fill="url(#half)" d="M10 1.5l2.39 4.84 5.34.78-3.86 3.76.91 5.32L10 13.9l-4.78 2.5.91-5.32L2.27 7.12l5.34-.78L10 1.5z"/></svg>
                @else
                    <svg class="h-4 w-4 fill-ink-200" viewBox="0 0 20 20"><path d="M10 1.5l2.39 4.84 5.34.78-3.86 3.76.91 5.32L10 13.9l-4.78 2.5.91-5.32L2.27 7.12l5.34-.78L10 1.5z"/></svg>
                @endif
            @endfor
        </span>
        <span class="text-sm text-ink-500 leading-none whitespace-nowrap">from {{ $gCount }} Google reviews</span>
    </span>
</{{ $tag }}>
@endif
