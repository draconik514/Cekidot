<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - CEKIDOT')</title>
    <link rel="icon" href="{{ asset('assets/img/logo-sulteng.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    @yield('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>

    @include('includes.sidebar')

    <main class="main-content">
        <div class="overlay">
            @yield('content')
        </div>
    </main>

    <script>
        var toggle = document.getElementById('sidebarToggle');
        var sidebar = document.querySelector('.sidebar');
        var overlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            toggle.querySelector('i').className = 'fas fa-times';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            toggle.querySelector('i').className = 'fas fa-bars';
        }

        toggle.addEventListener('click', function() {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        overlay.addEventListener('click', closeSidebar);

        function updateSidebarClock() {
            var now = new Date();
            var h = String(now.getHours()).padStart(2,'0');
            var m = String(now.getMinutes()).padStart(2,'0');
            var s = String(now.getSeconds()).padStart(2,'0');
            var el = document.getElementById('sidebarClock');
            if (el) el.textContent = h + ':' + m + ':' + s;
        }
        updateSidebarClock();
        setInterval(updateSidebarClock, 1000);
    </script>
    @yield('scripts')
</body>
</html>
