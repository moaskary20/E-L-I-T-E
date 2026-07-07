<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}" />
    <title>@yield('title', 'Elite Physio Clinics | Expert Physiotherapy Northampton')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <style>[x-cloak] { display: none !important; }</style>
    @stack('head')
</head>
<body>
    @yield('content')
    <script src="{{ asset('js/booking.js') }}"></script>
    <script src="{{ asset('js/hero-bg.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
