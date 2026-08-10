<header>
    <nav class="navbar {{ !request()->routeIs('home') ? 'always-solid' : '' }}" id="mainNav">
        <div class="container">
            <a href="{{ route('home') }}" class="nav-brand">
                <div class="brand-logo">
                    <img src="{{ asset('assets/img/logo-sulteng.png') }}" alt="Logo Sulawesi Tengah" onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:20px;font-weight:900;color:#eab308;\'>S</span>'">
                </div>
                <div class="brand-text">
                    <span class="brand-name">CEK<span>IDOT</span></span>
                    <span class="brand-sub">CEK IKU DAN DOKUMEN TERPADU</span>
                </div>
            </a>

            <button class="nav-toggle" id="navToggle" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="{{ route('surat.create') }}" class="{{ request()->routeIs('surat.create') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Kirim Surat</a></li>
                <li><a href="{{ route('akip.public') }}" class="{{ request()->routeIs('akip.public') ? 'active' : '' }}"><i class="fas fa-clipboard-check"></i> AKIP</a></li>
                <li><a href="{{ route('iki.public') }}" class="{{ request()->routeIs('iki.public') ? 'active' : '' }}"><i class="fas fa-user-check"></i> IKI</a></li>
                <li><a href="{{ route('iku.public') }}" class="{{ request()->routeIs('iku.public') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> IKU</a></li>
                <li><a href="{{ route('capaian.public') }}" class="{{ request()->routeIs('capaian.public') ? 'active' : '' }}"><i class="fas fa-flag-checkered"></i> Capaian Program</a></li>
                <li><a href="{{ route('monev.public') }}" class="{{ request()->routeIs('monev.public') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Monev</a></li>
                <li>
                    <a href="{{ route('login') }}" class="btn-login-nav">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<style>
    /* ===== NAVBAR ===== */
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        background: transparent;
        padding: 12px 0;
        border-bottom: none;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    .navbar.scrolled {
        background: rgba(15, 59, 94, 0.95);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 8px 0;
        border-bottom: 2px solid #eab308;
        box-shadow: 0 4px 30px rgba(0,0,0,0.15);
    }

    .navbar.always-solid {
        background: rgba(15, 59, 94, 0.95) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        padding: 8px 0 !important;
        border-bottom: 2px solid #eab308 !important;
        box-shadow: 0 4px 30px rgba(0,0,0,0.15) !important;
    }

    body.has-fixed-header { padding-top: 50px; }

    .navbar .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-brand {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #ffffff;
        font-size: 18px;
        font-weight: 800;
        text-decoration: none;
        letter-spacing: -0.3px;
    }

    .nav-brand .brand-logo {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        overflow: hidden;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 2px;
        border: 1px solid rgba(255,255,255,0.15);
    }

    .nav-brand .brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .nav-brand .brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .nav-brand .brand-text .brand-name {
        font-size: 18px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.3px;
    }

    .nav-brand .brand-text .brand-name span { color: #eab308; }

    .nav-brand .brand-text .brand-sub {
        font-size: 7px;
        font-weight: 500;
        color: rgba(255,255,255,0.5);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-top: -1px;
    }

    .nav-menu {
        list-style: none;
        display: flex;
        gap: 2px;
        align-items: center;
        flex-wrap: wrap;
    }

    .nav-menu li a {
        display: block;
        padding: 6px 14px;
        color: rgba(255,255,255,0.8);
        font-size: 13px;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.3s;
        text-decoration: none;
    }

    .nav-menu li a:hover {
        background: rgba(255,255,255,0.12);
        color: #ffffff;
    }

    .nav-menu li a.active {
        background: #eab308;
        color: #0f3b5e;
    }

    .btn-login-nav {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 14px;
        background: rgba(255,255,255,0.10);
        color: #ffffff;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid rgba(255,255,255,0.08);
        transition: all 0.3s;
    }

    .btn-login-nav:hover {
        background: #eab308;
        color: #0f3b5e;
        border-color: #eab308;
    }

    .nav-toggle {
        display: none;
        background: none;
        border: none;
        color: #ffffff;
        font-size: 22px;
        cursor: pointer;
        padding: 4px 8px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .nav-menu li a { padding: 5px 10px; font-size: 12px; }
    }

    @media (max-width: 768px) {
        body.has-fixed-header { padding-top: 68px; }
        .navbar .container { padding: 0 16px; }
        .nav-toggle { display: block; }

        .nav-menu {
            display: none;
            flex-direction: column;
            width: 100%;
            padding: 10px 0 6px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .nav-menu.open { display: flex; }
        .nav-menu li a { padding: 8px 12px; width: 100%; text-align: center; font-size: 14px; }

        .navbar.scrolled .nav-menu,
        .navbar.always-solid .nav-menu {
            background: rgba(15,59,94,0.95);
        }

        .nav-brand .brand-text .brand-name { font-size: 16px; }
        .nav-brand .brand-logo { width: 28px; height: 28px; }
        .btn-login-nav { padding: 4px 12px; font-size: 11px; }
        .nav-menu .btn-login-nav { margin-top: 4px; justify-content: center; }
    }

    @media (max-width: 480px) {
        body.has-fixed-header { padding-top: 60px; }
        .nav-brand .brand-text .brand-name { font-size: 14px; }
        .nav-brand .brand-text .brand-sub { font-size: 6px; }
        .nav-brand .brand-logo { width: 24px; height: 24px; }
        .navbar.scrolled, .navbar.always-solid { padding: 6px 0; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var navbar = document.getElementById('mainNav');

    if (!navbar.classList.contains('always-solid')) {
        function handleScroll() {
            if (window.scrollY > 80) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        handleScroll();
        window.addEventListener('scroll', handleScroll);
    }

    var toggleBtn = document.getElementById('navToggle');
    var navMenu = document.getElementById('navMenu');

    if (toggleBtn && navMenu) {
        toggleBtn.addEventListener('click', function() {
            navMenu.classList.toggle('open');
            var icon = this.querySelector('i');
            icon.className = navMenu.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
        });

        navMenu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                navMenu.classList.remove('open');
                var icon = toggleBtn.querySelector('i');
                if (icon) icon.className = 'fas fa-bars';
            });
        });
    }
});
</script>
