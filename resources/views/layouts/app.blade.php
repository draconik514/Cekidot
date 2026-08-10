<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CEKIDOT - Dinas Pariwisata')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @yield('styles')
    @stack('styles')
</head>
<body class="{{ !request()->routeIs('home') ? 'has-fixed-header' : '' }}">
    @include('includes.header')

    <main>
        @yield('content')
    </main>

    @include('includes.footer')

    <script src="{{ asset('assets/js/script.js') }}"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
