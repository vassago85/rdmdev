@props([
    'image' => [],
    'sizes' => null,
    'alt' => '',
])

@php
    $src = $image['src'] ?? '';
    $srcset = $image['srcset'] ?? null;
    $width = (int) ($image['width'] ?? 0);
    $height = (int) ($image['height'] ?? 0);
@endphp

@if ($src !== '')
    <picture class="contents">
        @if ($srcset)
            <source type="image/webp" srcset="{{ $srcset }}" @if ($sizes) sizes="{{ $sizes }}" @endif>
        @endif
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            @if ($width > 0) width="{{ $width }}" @endif
            @if ($height > 0) height="{{ $height }}" @endif
            @if ($sizes) sizes="{{ $sizes }}" @endif
            {{ $attributes }}
        >
    </picture>
@endif
