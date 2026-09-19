@props([
    'placement' => 'unknown',
    'text' => null,
])
@php
    $message = $text ?? config('rdm.whatsapp_greeting');
@endphp
<a
    href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode($message) }}"
    target="_blank"
    rel="noopener"
    data-whatsapp-placement="{{ $placement }}"
    {{ $attributes }}
>{{ $slot }}</a>
