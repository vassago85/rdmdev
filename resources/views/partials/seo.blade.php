@php
    $title       = $pageTitle      ?? (config('rdm.name') . ' — ' . config('rdm.tagline'));
    $description = $metaDescription ?? 'Owner-managed builder and renovation specialist in Pretoria East. Personally supervised by Ruben Metcalfe.';
    $canonical   = url()->current();
    $ogImage     = $ogImage ?? asset('images/rdmdev-logo-960.png');
@endphp
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $canonical }}">
<meta name="robots" content="index, follow">
<meta name="author" content="{{ config('rdm.owner') }}">
<meta name="geo.region" content="ZA-GT">
<meta name="geo.placename" content="Pretoria East, Gauteng">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('rdm.name') }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:locale" content="en_ZA">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<link rel="icon" type="image/svg+xml" href="{{ asset('images/rdm-icon.svg') }}">
