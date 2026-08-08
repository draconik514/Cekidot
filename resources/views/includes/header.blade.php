<header>
    <div class="nav-overlay" id="navOverlay"></div>
    <nav class="navbar" id="mainNav">
        <div class="container">
            <a href="{{ route('home') }}" class="nav-brand">
                <div class="brand-logo">
                    <img src="{{ asset('assets/img/logo-sulteng.png') }}" alt="Logo Sulawesi Tengah" onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:20px;font-weight:900;color:#eab308;\'>S</span>'">
                </div>
                <div class="brand-text">
                    <span class="brand-name" style="font-size:20px; font-weight:800; letter-spacing:-0.3px; line-height:1.2;">
                        CEK<span style="color:#eab308;">IDOT</span>
                    </span>
                    <span class="brand-sub" style="font-size:8px; font-weight:500; color:rgba(255,255,255,0.55); letter-spacing:1.8px; text-transform:uppercase; display:block; margin-top:1px;">
                        CEK IKU DAN DOKUMEN TERPADU
                    </span>
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
                        <span class="login-text">Admin</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

