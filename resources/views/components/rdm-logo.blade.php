@props(['variant' => 'dark', 'eager' => true])

@php
    // variant="light" renders the logo as pure white (for dark backgrounds)
    // via CSS filter: brightness(0) invert(1).
    $styleAttr = $variant === 'light'
        ? 'filter: brightness(0) invert(1);'
        : '';

    // Display size is ~115×48 (h-12). Serve 128w / 256w (1x/2x) WebP with
    // PNG fallbacks so we don't ship the 480px master to every page.
    $png128 = asset('images/rdmdev-logo-128.png');
    $png256 = asset('images/rdmdev-logo-256.png');
    $png480 = asset('images/rdmdev-logo-480.png');
    $webp128 = asset('images/rdmdev-logo-128.webp');
    $webp256 = asset('images/rdmdev-logo-256.webp');
    $loading = $eager ? 'eager' : 'lazy';
    $fetchpriority = $eager ? 'high' : null;
@endphp

<picture>
    <source
        type="image/webp"
        srcset="{{ $webp128 }} 128w, {{ $webp256 }} 256w"
        sizes="115px"
    >
    <img
        src="{{ $png128 }}"
        srcset="{{ $png128 }} 128w, {{ $png256 }} 256w, {{ $png480 }} 480w"
        sizes="115px"
        alt="RDM Developments — Building &amp; Renovation"
        width="115"
        height="48"
        loading="{{ $loading }}"
        @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
        decoding="async"
        style="{{ $styleAttr }}"
        {{ $attributes->merge(['class' => 'h-12 w-auto']) }}
    />
</picture>
