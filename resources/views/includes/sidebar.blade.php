@php
    $user = auth()->user();
    $total_baru = App\Models\SuratMasuk::where('status', 'baru')->count();
@endphp

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="{{ asset('assets/img/logo-sulteng.png') }}" alt="Logo"
                onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:26px;font-weight:900;color:#eab308;\'>C</span>'">
        </div>
        <div class="brand-text">
            <h2>CEK<span>IDOT</span></h2>
            <small>{{ $user?->isSuperAdmin() ? 'Super Admin' : ($user?->isAdminDivisi() ? 'Admin '.$user->divisi : 'Anggota') }}</small>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">{{ strtoupper(substr($user?->nama_admin ?? 'A', 0, 1)) }}</div>
        <div class="user-info">
            <div class="user-name">{{ $user?->nama_admin ?? 'Admin' }}</div>
            <div class="user-role">{{ str_replace('_', ' ', $user?->role ?? '') }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">

            {{-- DASHBOARD --}}
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i><span>Dashboard</span>
                </a>
            </li>

            {{-- SUPER ADMIN ONLY --}}
            @if($user?->isSuperAdmin())

            <li class="nav-section">KONTEN PUBLIK</li>

            <li>
                <a href="{{ route('admin.slider.index') }}" class="{{ request()->routeIs('admin.slider.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i><span>Slider Beranda</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.surat.index') }}" class="{{ request()->routeIs('admin.surat.*') ? 'active' : '' }}">
                    <i class="fas fa-inbox"></i>
                    <span>Surat Masuk</span>
                    @if($total_baru > 0)
                    <span class="badge">{{ $total_baru }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-section">DOKUMEN KINERJA</li>

            {{-- Dropdown: Dokumen --}}
            <li class="has-dropdown {{ request()->routeIs('admin.akip.*') || request()->routeIs('admin.iki.*') ? 'open' : '' }}">
                <a href="#" class="dropdown-toggle {{ request()->routeIs('admin.akip.*') || request()->routeIs('admin.iki.*') ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <i class="fas fa-folder-open"></i><span>Dokumen</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.akip.index') }}" class="{{ request()->routeIs('admin.akip.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check"></i> AKIP
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.iki.index') }}" class="{{ request()->routeIs('admin.iki.*') ? 'active' : '' }}">
                            <i class="fas fa-user-check"></i> IKI
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Dropdown: Data Kinerja --}}
            <li class="has-dropdown {{ request()->routeIs('admin.iku.*') || request()->routeIs('admin.capaian.*') || request()->routeIs('admin.monev.*') ? 'open' : '' }}">
                <a href="#" class="dropdown-toggle {{ request()->routeIs('admin.iku.*') || request()->routeIs('admin.capaian.*') || request()->routeIs('admin.monev.*') ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <i class="fas fa-chart-bar"></i><span>Data Kinerja</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.iku.index') }}" class="{{ request()->routeIs('admin.iku.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> IKU
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.capaian.index') }}" class="{{ request()->routeIs('admin.capaian.*') ? 'active' : '' }}">
                            <i class="fas fa-flag-checkered"></i> Capaian Program
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.monev.index') }}" class="{{ request()->routeIs('admin.monev.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie"></i> Monev Renaksi
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-section">MANAJEMEN</li>

            {{-- Dropdown: Manajemen --}}
            <li class="has-dropdown {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.folder.*') || request()->routeIs('admin.upload.*') ? 'open' : '' }}">
                <a href="#" class="dropdown-toggle {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.folder.*') || request()->routeIs('admin.upload.*') ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <i class="fas fa-cogs"></i><span>Manajemen</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Kelola User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.folder.index') }}" class="{{ request()->routeIs('admin.folder.*') ? 'active' : '' }}">
                            <i class="fas fa-folder"></i> Folder Dokumen
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.upload.index') }}" class="{{ request()->routeIs('admin.upload.*') ? 'active' : '' }}">
                            <i class="fas fa-file-upload"></i> Upload Anggota
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('admin.arsip.index') }}" class="{{ request()->routeIs('admin.arsip.*') ? 'active' : '' }}">
                    <i class="fas fa-archive"></i><span>Arsip Surat</span>
                </a>
            </li>

            {{-- ADMIN DIVISI --}}
            @elseif($user?->isAdminDivisi())

            <li class="nav-section">DOKUMEN</li>

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

            <li class="nav-section">KINERJA</li>

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

            <li class="nav-section">KELOLA</li>

            <li>
                <a href="{{ route('admin.arsip.index') }}" class="{{ request()->routeIs('admin.arsip.*') ? 'active' : '' }}">
                    <i class="fas fa-archive"></i><span>Arsip Surat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.folder.index') }}" class="{{ request()->routeIs('admin.folder.*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i><span>Folder Dokumen</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.upload.index') }}" class="{{ request()->routeIs('admin.upload.*') ? 'active' : '' }}">
                    <i class="fas fa-file-upload"></i><span>Upload Anggota</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i><span>Kelola User</span>
                </a>
            </li>

            @endif

            <li class="nav-divider"></li>
            <li>
                <a href="{{ route('logout') }}" style="color:#ef4444;">
                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="datetime">
            <span><i class="fas fa-calendar-alt"></i> {{ date('d M Y') }}</span>
            <span><i class="fas fa-clock"></i> <span id="sidebarClock">--:--:--</span></span>
        </div>
    </div>
</aside>

<style>
.sidebar {
    width: 260px;
    min-height: 100vh;
    background: linear-gradient(180deg, #0f3b5e 0%, #0a2a44 100%);
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0; top: 0; bottom: 0;
    z-index: 100;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.1) transparent;
    transition: width 0.3s ease;
}
.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 20px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    flex-shrink: 0;
}
.sidebar-brand .brand-logo {
    width: 40px; height: 40px;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.sidebar-brand .brand-logo img { width: 28px; height: 28px; object-fit: contain; }
.sidebar-brand .brand-text h2 { font-size: 18px; font-weight: 800; color: #fff; letter-spacing: 1px; line-height: 1.2; }
.sidebar-brand .brand-text h2 span { color: #eab308; }
.sidebar-brand .brand-text small { font-size: 10px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; }

.sidebar-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    background: rgba(255,255,255,0.05);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}
.sidebar-user .user-avatar {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #eab308, #f59e0b);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; color: #0f3b5e;
    flex-shrink: 0;
}
.sidebar-user .user-name { font-size: 13px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
.sidebar-user .user-role { font-size: 10px; color: rgba(255,255,255,0.4); text-transform: capitalize; }

.sidebar-nav { flex: 1; padding: 12px 0; }
.nav-list { list-style: none; padding: 0; margin: 0; }

.nav-section {
    font-size: 9px;
    font-weight: 700;
    color: rgba(255,255,255,0.25);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    padding: 14px 20px 4px;
}

.nav-list > li > a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    color: rgba(255,255,255,0.65);
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 500;
    transition: all 0.2s;
    border-left: 3px solid transparent;
    position: relative;
}
.nav-list > li > a:hover {
    background: rgba(255,255,255,0.07);
    color: #fff;
    border-left-color: rgba(234,179,8,0.5);
}
.nav-list > li > a.active {
    background: rgba(234,179,8,0.12);
    color: #eab308;
    border-left-color: #eab308;
    font-weight: 600;
}
.nav-list > li > a i:first-child { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }

.badge {
    background: #ef4444;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 7px;
    border-radius: 20px;
    margin-left: auto;
    flex-shrink: 0;
}

/* Dropdown */
.has-dropdown > a .arrow {
    margin-left: auto;
    font-size: 10px;
    transition: transform 0.3s;
    flex-shrink: 0;
}
.has-dropdown.open > a .arrow { transform: rotate(180deg); }
.has-dropdown.open > a {
    background: rgba(255,255,255,0.07);
    color: #fff;
}

.dropdown-menu {
    list-style: none;
    padding: 4px 0 4px 0;
    margin: 0;
    background: rgba(0,0,0,0.2);
    display: none;
    border-left: 2px solid rgba(234,179,8,0.2);
    margin-left: 20px;
}
.has-dropdown.open .dropdown-menu { display: block; }
.dropdown-menu li a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    color: rgba(255,255,255,0.55);
    text-decoration: none;
    font-size: 13px;
    transition: all 0.2s;
}
.dropdown-menu li a:hover { color: #fff; background: rgba(255,255,255,0.05); }
.dropdown-menu li a.active { color: #eab308; font-weight: 600; }
.dropdown-menu li a i { width: 16px; text-align: center; font-size: 12px; }

.nav-divider { height: 1px; background: rgba(255,255,255,0.08); margin: 8px 16px; }

.sidebar-footer {
    padding: 12px 20px;
    border-top: 1px solid rgba(255,255,255,0.08);
    flex-shrink: 0;
}
.sidebar-footer .datetime {
    display: flex;
    flex-direction: column;
    gap: 3px;
    font-size: 11px;
    color: rgba(255,255,255,0.3);
}
.sidebar-footer .datetime span { display: flex; align-items: center; gap: 6px; }
.sidebar-footer .datetime i { width: 12px; }
</style>

<script>
function toggleDropdown(el) {
    var li = el.parentElement;
    var isOpen = li.classList.contains('open');
    document.querySelectorAll('.has-dropdown.open').forEach(function(item) {
        if (item !== li) item.classList.remove('open');
    });
    li.classList.toggle('open', !isOpen);
    event.preventDefault();
}

(function tick() {
    var el = document.getElementById('sidebarClock');
    if (el) {
        var now = new Date();
        el.textContent = now.toLocaleTimeString('id-ID');
    }
    setTimeout(tick, 1000);
})();
</script>
