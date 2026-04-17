@props(['icon' => 'home'])

@php
    $icons = [
        'home'   => '<path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>',
        'hammer' => '<path d="M2 20l8-8"/><path d="M14 4l6 6-4 4-6-6 4-4z"/><path d="M10 8l-4 4"/>',
        'brush'  => '<path d="M4 20c0-2 2-4 4-4s4 2 4 4-4 2-8 0z"/><path d="M22 4l-8 8-3-3 8-8z"/>',
        'door'   => '<rect x="6" y="3" width="12" height="18" rx="1"/><circle cx="14.5" cy="12" r="0.8"/>',
        'shower' => '<path d="M6 4h6v6"/><path d="M12 4v2"/><path d="M4 10h12l-1 4H5z"/><path d="M7 18v2"/><path d="M10 16v4"/><path d="M13 18v2"/>',
    ];
    $path = $icons[$icon] ?? $icons['home'];
@endphp

<svg {{ $attributes->merge(['class' => 'h-7 w-7', 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    {!! $path !!}
</svg>
