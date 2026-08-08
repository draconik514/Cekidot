@php
    $current_page = basename($_SERVER['PHP_SELF']);
    $total_baru = App\Models\SuratMasuk::where('status', 'baru')->count();
    $admin_nama = auth()->user()?->nama_admin ?? 'Admin';
@endphp

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="{{ asset('admin/img/logo-sulteng.png') }}" alt="Logo" onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:28px;font-weight:900;color:#eab308;\'>S</span>'">
        </div>
        <div class="brand-text">
            <h2>CEK<span>IDOT</span></h2>
            <small>Admin Panel</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ $current_page == 'index.php' ? 'active' : '' }}"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="{{ route('admin.slider.index') }}" class="{{ $current_page == 'slider.php' ? 'active' : '' }}"><i class="fas fa-images"></i><span>Slider</span></a></li>
            <li>
                <a href="{{ route('admin.surat.index') }}" class="{{ $current_page == 'surat-masuk.php' ? 'active' : '' }}">
                    <i class="fas fa-inbox"></i>
                    <span>Surat Masuk</span>
                    @if($total_baru > 0)
                    <span class="badge" style="background:#ef4444; color:#fff; font-size:10px; font-weight:600; padding:0 8px; min-width:18px; height:18px; border-radius:999px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; line-height:1;">{{ $total_baru }}</span>
                    @else
                    <span class="badge zero" style="background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.35); font-size:9px; font-weight:500; padding:0 8px; min-width:18px; height:18px; border-radius:999px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; line-height:1;">0</span>
                    @endif
                </a>
            </li>
            <li><a href="{{ route('admin.akip.index') }}" class="{{ $current_page == 'akip.php' ? 'active' : '' }}"><i class="fas fa-clipboard-check"></i><span>Dokumen AKIP</span></a></li>
            <li><a href="{{ route('admin.iki.index') }}" class="{{ $current_page == 'iki.php' ? 'active' : '' }}"><i class="fas fa-user-check"></i><span>Dokumen IKI</span></a></li>
            <li><a href="{{ route('admin.iku.index') }}" class="{{ $current_page == 'iku.php' ? 'active' : '' }}"><i class="fas fa-chart-line"></i><span>IKU</span></a></li>
            <li><a href="{{ route('admin.capaian.index') }}" class="{{ $current_page == 'capaian.php' ? 'active' : '' }}"><i class="fas fa-flag-checkered"></i><span>Capaian Program</span></a></li>
            <li><a href="{{ route('admin.monev.index') }}" class="{{ $current_page == 'monev.php' ? 'active' : '' }}"><i class="fas fa-chart-pie"></i><span>Monev Renaksi</span></a></li>
            <li class="nav-divider"></li>
            <li class="nav-logout"><a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="datetime">
            <div class="date"><i class="fas fa-calendar-alt"></i> <span id="sidebarDate">{{ date('d F Y') }}</span></div>
            <div class="time"><i class="fas fa-clock"></i> <span id="sidebarClock">00:00:00</span></div>
        </div>
    </div>
</aside>