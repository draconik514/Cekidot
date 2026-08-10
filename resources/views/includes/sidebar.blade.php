@php
    $total_baru = App\Models\SuratMasuk::where('status', 'baru')->count();
    $admin_nama = auth()->user()?->nama_admin ?? 'Admin';
@endphp

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="{{ asset('assets/img/logo-sulteng.png') }}" alt="Logo" onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:28px;font-weight:900;color:#eab308;\'>S</span>'">
        </div>
        <div class="brand-text">
            <h2>CEK<span>IDOT</span></h2>
            <small>Admin Panel</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.slider.index') }}" class="{{ request()->routeIs('admin.slider.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i><span>Slider</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.surat.index') }}" class="{{ request()->routeIs('admin.surat.*') ? 'active' : '' }}">
                    <i class="fas fa-inbox"></i>
                    <span>Surat Masuk</span>
                    @if($total_baru > 0)
                    <span class="badge">{{ $total_baru }}</span>
                    @else
                    <span class="badge zero">0</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.akip.index') }}" class="{{ request()->routeIs('admin.akip.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i><span>Dokumen AKIP</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.iki.index') }}" class="{{ request()->routeIs('admin.iki.*') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i><span>Dokumen IKI</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.iku.index') }}" class="{{ request()->routeIs('admin.iku.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i><span>IKU</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.capaian.index') }}" class="{{ request()->routeIs('admin.capaian.*') ? 'active' : '' }}">
                    <i class="fas fa-flag-checkered"></i><span>Capaian Program</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.monev.index') }}" class="{{ request()->routeIs('admin.monev.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i><span>Monev Renaksi</span>
                </a>
            </li>
            <li class="nav-divider"></li>
            <li class="nav-logout">
                <a href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="datetime">
            <div class="date"><i class="fas fa-calendar-alt"></i> <span>{{ date('d F Y') }}</span></div>
            <div class="time"><i class="fas fa-clock"></i> <span id="sidebarClock">00:00:00</span></div>
        </div>
    </div>
</aside>
