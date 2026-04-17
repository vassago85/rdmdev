<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2F5D62">

    @include('partials.seo')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Local business structured data --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'GeneralContractor',
        'name'     => config('rdm.name'),
        'legalName'=> 'RDM Developments (Pty) Ltd',
        'image'    => asset('images/rdmdev-logo-960.png'),
        'url'      => url('/'),
        'telephone'=> config('rdm.phone_tel'),
        'email'    => config('rdm.email'),
        'areaServed' => [
            '@type' => 'Place',
            'name'  => 'Pretoria East, Gauteng, South Africa',
        ],
        'address'  => [
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Pretoria East',
            'addressRegion'   => 'Gauteng',
            'addressCountry'  => 'ZA',
        ],
        'founder'   => config('rdm.owner'),
        'makesOffer'=> collect(\App\Models\Service::published()->ordered()->get(['title','slug']))->map(fn ($s) => [
            '@type' => 'Offer',
            'name'  => $s->title,
            'url'   => route('services.show', $s->slug),
        ])->values(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</head>
<body class="min-h-screen flex flex-col bg-white text-ink-700">

    @include('partials.header')

    <main class="flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.floating-whatsapp')
</body>
</html>
