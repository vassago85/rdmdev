<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2F5D62">

    @include('partials.seo')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('partials.schema')
</head>
<body class="min-h-screen flex flex-col bg-white text-ink-700">

    @include('partials.header')

    <main class="flex-1 whatsapp-safe">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.floating-whatsapp')
</body>
</html>
