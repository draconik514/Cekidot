<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CEKIDOT - Dinas Pariwisata')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @yield('styles')
    @stack('styles')
</head>
<body>
    @include('includes.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('includes.footer')
    
    <script src="{{ asset('assets/js/turbo.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>