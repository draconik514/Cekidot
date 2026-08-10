@extends('layouts.admin')

@section('title', 'Dashboard - CEKIDOT')

@section('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: #fff;
        padding: 18px 20px;
        border-radius: 14px;
        border: 1px solid #e8ecf1;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .stat-icon.blue { background: #dbeafe; color: #1d4ed8; }
    .stat-icon.red { background: #fef2f2; color: #dc2626; }
    .stat-icon.orange { background: #fef3c7; color: #b45309; }
    .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
    .stat-icon.cyan { background: #cffafe; color: #0891b2; }
    .stat-icon.green { background: #d1fae5; color: #065f46; }

    .stat-info .number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .stat-info .number .new-badge {
        font-size: 12px;
        font-weight: 600;
        color: #dc2626;
        background: #fef2f2;
        padding: 0 10px;
        border-radius: 12px;
        margin-left: 4px;
    }
    .stat-info .label {
        font-size: 13px;
        color: #94a3b8;
        margin-top: 2px;
    }
    .stat-info .trend {
        font-size: 11px;
        margin-top: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 1px 10px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #64748b;
    }
    .stat-info .trend.up { background: #d1fae5; color: #065f46; }
    .stat-info .trend.down { background: #fef2f2; color: #991b1b; }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e8ecf1;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .card-header h3 {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-header h3 i { color: #eab308; }
    .card-header .count {
        background: #f1f5f9;
        color: #64748b;
        font-size: 11px;
        padding: 2px 10px;
        border-radius: 20px;
    }
    .card-body { padding: 16px 20px; }
    .card-body .empty {
        text-align: center;
        padding: 32px 0;
        color: #94a3b8;
    }
    .card-body .empty i { font-size: 32px; opacity: 0.3; display: block; margin-bottom: 8px; }

    .surat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .surat-item:last-child { border-bottom: none; }
    .surat-item .icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .surat-item .icon.baru { background: #fef2f2; color: #dc2626; }
    .surat-item .icon.sudah { background: #f1f5f9; color: #94a3b8; }
    .surat-item .info { flex: 1; min-width: 0; }
    .surat-item .info .judul {
        font-weight: 600;
        font-size: 14px;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .surat-item .info .meta {
        font-size: 12px;
        color: #94a3b8;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .surat-item .info .meta i { width: 14px; }
    .surat-item .status {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
        flex-shrink: 0;
    }
    .surat-item .status.baru { background: #fef2f2; color: #dc2626; }
    .surat-item .status.sudah { background: #f1f5f9; color: #94a3b8; }

    .view-all {
        display: block;
        text-align: center;
        padding: 10px 0 0;
        margin-top: 8px;
        border-top: 1px solid #f1f5f9;
        padding-top: 12px;
        color: #0f3b5e;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        transition: 0.2s;
    }
    .view-all:hover { color: #eab308; }

    .aktivitas-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .aktivitas-item:last-child { border-bottom: none; }
    .aktivitas-item .icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #fff;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .aktivitas-item .text {
        flex: 1;
        font-size: 13px;
        color: #1e293b;
    }
    .aktivitas-item .text strong { color: #0f3b5e; }
    .aktivitas-item .time {
        font-size: 11px;
        color: #94a3b8;
        flex-shrink: 0;
        margin-top: 2px;
    }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .dashboard-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .header { flex-direction: column; align-items: flex-start; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .surat-item { flex-wrap: wrap; }
    }
</style>
@endsection

@section('content')
<div class="header">
    <div class="header-left">
        <h1><i class="fas fa-home"></i> Dashboard</h1>
        <span class="info">Selamat datang kembali, <strong>{{ auth()->user()->nama_admin ?? 'Admin' }}</strong> <span style="color:#94a3b8; margin-left:6px;">• {{ date('d F Y') }}</span></span>
    </div>
    <div class="admin-welcome">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->nama_admin ?? 'A', 0, 1)) }}</div>
        <div>
            <div>{{ auth()->user()->nama_admin ?? 'Admin' }}</div>
            <div class="role">Administrator</div>
        </div>
    </div>
</div>

<!-- STATISTIK - 6 KOLOM -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-inbox"></i></div>
        <div class="stat-info">
            <div class="number">{{ $total_surat }}</div>
            <div class="label">Total Surat</div>
            <div class="trend up"><i class="fas fa-arrow-up"></i> {{ $total_surat > 0 ? 'Aktif' : 'Belum ada' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="number">
                {{ $total_surat_baru }}
                @if($total_surat_baru > 0)
                <span class="new-badge">Baru!</span>
                @endif
            </div>
            <div class="label">Belum Dibaca</div>
            <div class="trend {{ $total_surat_baru > 0 ? 'up' : '' }}">
                {{ $total_surat_baru > 0 ? 'Perlu perhatian' : 'Semua terbaca ✅' }}
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-images"></i></div>
        <div class="stat-info">
            <div class="number">{{ $total_slider }}/6</div>
            <div class="label">Slide Aktif</div>
            <div class="trend">{{ $total_slider >= 6 ? 'Penuh' : (6 - $total_slider) . ' slot tersisa' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-clipboard-check"></i></div>
        <div class="stat-info">
            <div class="number">{{ $total_akip }}</div>
            <div class="label">Dokumen AKIP</div>
            <div class="trend">{{ $total_akip > 0 ? 'Tersedia' : 'Belum ada' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon cyan"><i class="fas fa-user-check"></i></div>
        <div class="stat-info">
            <div class="number">{{ $total_iki }}</div>
            <div class="label">Dokumen IKI</div>
            <div class="trend">{{ $total_iki > 0 ? 'Tersedia' : 'Belum ada' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-chart-pie"></i></div>
        <div class="stat-info">
            <div class="number">{{ $total_monev_bulanan + $total_monev_akumulasi }}</div>
            <div class="label">Data Monev</div>
            <div class="trend up">
                <i class="fas fa-arrow-up"></i> 
                {{ $total_monev_bulanan > 0 ? $total_monev_bulanan . ' bulanan' : '' }}
                {{ $total_monev_akumulasi > 0 ? ($total_monev_bulanan > 0 ? ' & ' : '') . $total_monev_akumulasi . ' akumulasi' : '' }}
                {{ ($total_monev_bulanan + $total_monev_akumulasi) == 0 ? 'Belum ada' : '' }}
            </div>
        </div>
    </div>
</div>

<!-- DASHBOARD GRID -->
<div class="dashboard-grid">

    <!-- SURAT TERBARU -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-envelope"></i> Surat Masuk Terbaru</h3>
            <span class="count">{{ count($surat_terbaru) }}</span>
        </div>
        <div class="card-body">
            @if(empty($surat_terbaru))
            <div class="empty">
                <i class="fas fa-inbox"></i>
                <p>Belum ada surat masuk</p>
            </div>
            @else
            @foreach($surat_terbaru as $s)
            <div class="surat-item">
                <div class="icon {{ $s->status == 'baru' ? 'baru' : 'sudah' }}">
                    <i class="fas {{ $s->status == 'baru' ? 'fa-circle' : 'fa-envelope' }}"></i>
                </div>
                <div class="info">
                    <div class="judul">{{ $s->perihal ?? 'Tanpa Judul' }}</div>
                    <div class="meta">
                        <span><i class="fas fa-building"></i> {{ $s->asal_instansi ?? '-' }}</span>
                        <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($s->tanggal_masuk)->format('d/m/Y') }}</span>
                    </div>
                </div>
                <span class="status {{ $s->status == 'baru' ? 'baru' : 'sudah' }}">
                    {{ $s->status == 'baru' ? 'Baru' : 'Dibaca' }}
                </span>
            </div>
            @endforeach
            <a href="{{ route('admin.surat.index') }}" class="view-all">
                Lihat Semua Surat <i class="fas fa-arrow-right"></i>
            </a>
            @endif
        </div>
    </div>

    <!-- AKTIVITAS TERBARU -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-bolt"></i> Aktivitas Terbaru</h3>
            <span class="count">{{ count($aktivitas) }}</span>
        </div>
        <div class="card-body">
            @if(empty($aktivitas))
            <div class="empty">
                <i class="fas fa-clock"></i>
                <p>Belum ada aktivitas</p>
            </div>
            @else
            @php
                $icon_colors = [
                    'surat' => '#3b82f6',
                    'akip' => '#8b5cf6',
                    'iki' => '#06b6d4',
                    'slider' => '#f59e0b',
                    'monev' => '#10b981'
                ];
                $icon_icons = [
                    'surat' => 'fa-envelope',
                    'akip' => 'fa-file-pdf',
                    'iki' => 'fa-file-alt',
                    'slider' => 'fa-image',
                    'monev' => 'fa-chart-pie'
                ];
            @endphp
            @foreach($aktivitas as $act)
            <div class="aktivitas-item">
                <div class="icon" style="background: {{ $icon_colors[$act['type']] ?? '#94a3b8' }};">
                    <i class="fas {{ $icon_icons[$act['type']] ?? 'fa-file' }}"></i>
                </div>
                <div class="text">{{ $act['deskripsi'] }}</div>
                <div class="time">
                    @php
                        $waktu = strtotime($act['waktu']);
                        $diff = time() - $waktu;
                        if ($diff < 60) echo 'Baru saja';
                        elseif ($diff < 3600) echo floor($diff/60) . ' menit lalu';
                        elseif ($diff < 86400) echo floor($diff/3600) . ' jam lalu';
                        else echo date('d/m/Y', $waktu);
                    @endphp
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection