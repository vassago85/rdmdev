@props(['icon' => 'house'])

@php
    // Map legacy service icon slugs → Lucide names.
    // If a new service is seeded with a direct lucide name it'll pass through.
    $map = [
        'home'     => 'house',
        'house'    => 'house',
        'shower'   => 'bath',
        'bath'     => 'bath',
        'hammer'   => 'hammer',
        'brush'    => 'paintbrush',
        'paintbrush' => 'paintbrush',
        'door'     => 'layers',
        'custom'   => 'layers',
        'layers'   => 'layers',
        'construction' => 'construction',
        'file-text' => 'file-text',
    ];
    $lucide = $map[$icon] ?? 'house';
@endphp

<x-lucide :name="$lucide" {{ $attributes->merge(['class' => 'h-7 w-7', 'stroke' => '1.8']) }} />
