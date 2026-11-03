@props(['variant' => 'dark', 'eager' => true, 'wordmark' => false])

@php
    // variant="light" renders the logo as pure white (for dark backgrounds)
    // via CSS filter: brightness(0) invert(1).
    $styleAttr = $variant === 'light'
        ? 'filter: brightness(0) invert(1);'
        : '';

    $passedClass = (string) $attributes->get('class', '');
    $hasHeight = preg_match('/(?:^|\s)!?h-/', $passedClass) === 1;
    $defaultClass = $hasHeight ? 'w-auto' : 'h-12 w-auto';

    $loading = $eager ? 'eager' : 'lazy';
    $fetchpriority = $eager ? 'high' : null;

    if ($wordmark) {
        $png = asset('images/rdmdev-wordmark.png');
        $webp = asset('images/rdmdev-wordmark.webp');
        $width = 249;
        $height = 79;
        $sizes = '180px';
    } else {
        $png128 = asset('images/rdmdev-logo-128.png');
        $png256 = asset('images/rdmdev-logo-256.png');
        $png480 = asset('images/rdmdev-logo-480.png');
        $webp128 = asset('images/rdmdev-logo-128.webp');
        $webp256 = asset('images/rdmdev-logo-256.webp');
        $width = 115;
        $height = 48;
        $sizes = '160px';
    }
@endphp

<picture>
    @if ($wordmark)
        <source type="image/webp" srcset="{{ $webp }}" sizes="{{ $sizes }}">
        <img
            src="{{ $png }}"
            alt="RDM Developments"
            width="{{ $width }}"
            height="{{ $height }}"
            sizes="{{ $sizes }}"
            loading="{{ $loading }}"
            @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
            decoding="async"
            style="{{ $styleAttr }}"
            {{ $attributes->merge(['class' => $defaultClass]) }}
        />
    @else
        <source
            type="image/webp"
            srcset="{{ $webp128 }} 128w, {{ $webp256 }} 256w"
            sizes="{{ $sizes }}"
        >
        <img
            src="{{ $png128 }}"
            srcset="{{ $png128 }} 128w, {{ $png256 }} 256w, {{ $png480 }} 480w"
            sizes="{{ $sizes }}"
            alt="RDM Developments — Building &amp; Renovation"
            width="{{ $width }}"
            height="{{ $height }}"
            loading="{{ $loading }}"
            @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
            decoding="async"
            style="{{ $styleAttr }}"
            {{ $attributes->merge(['class' => $defaultClass]) }}
        />
    @endif
</picture>
