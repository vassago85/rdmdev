@props(['variant' => 'dark'])

@php
    $wordColor = $variant === 'light' ? '#FFFFFF' : '#2F5D62';
    $subColor  = $variant === 'light' ? '#FFFFFF' : '#3A3F45';
    $roofColor = $variant === 'light' ? '#FFFFFF' : '#2F5D62';
@endphp

<svg {{ $attributes->merge(['class' => 'h-12 w-auto', 'role' => 'img', 'aria-label' => 'RDM Developments']) }}
     viewBox="0 0 260 80" xmlns="http://www.w3.org/2000/svg">
    {{-- Roof line --}}
    <path d="M10 34 L100 10 L190 34" fill="none" stroke="{{ $roofColor }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
    {{-- Little chimney accent --}}
    <line x1="148" y1="22" x2="148" y2="34" stroke="{{ $roofColor }}" stroke-width="4" stroke-linecap="round"/>
    {{-- Wordmark --}}
    <text x="10" y="62"
          font-family="Barlow Condensed, Inter, sans-serif"
          font-size="34" font-weight="700"
          fill="{{ $wordColor }}"
          letter-spacing="2">RDMDEV</text>
    <text x="134" y="62"
          font-family="Inter, sans-serif"
          font-size="14" font-weight="500"
          fill="{{ $subColor }}"
          letter-spacing="3">DEVELOPMENTS</text>
</svg>
