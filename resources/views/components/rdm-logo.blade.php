@props(['variant' => 'dark'])

@php
    // variant="light" renders the logo as pure white (for dark backgrounds)
    // via CSS filter: brightness(0) invert(1).
    $styleAttr = $variant === 'light'
        ? 'filter: brightness(0) invert(1);'
        : '';
@endphp

<img
    src="{{ asset('images/rdmdev-logo-480.png') }}"
    srcset="{{ asset('images/rdmdev-logo-480.png') }} 1x, {{ asset('images/rdmdev-logo-960.png') }} 2x"
    alt="RDM Developments — Building &amp; Renovation"
    width="480"
    height="201"
    style="{{ $styleAttr }}"
    {{ $attributes->merge(['class' => 'h-12 w-auto']) }}
/>
