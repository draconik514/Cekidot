@extends('layouts.admin')
@section('title', 'Dashboard Anggota - CEKIDOT')

@section('styles')
<style>
    .anggota-header {
        background: linear-gradient(135deg, #0f3b5e 0%, #1a5a7a 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        position: relative;
        overflow: hidden;
    }
    .anggota-header::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(234,179,8,0.08);
        border-radius: 50%;
        pointer-events: none;
    }
    .anggota-header .greet h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
    .anggota-header .greet h1 span { color: #eab308; }
    .anggota-header .greet p { font-size: 14px; opacity: 0.7; }
    .anggota-header .badge-divisi {
        background: rgba(234,179,8,0.15);
        border: 1px solid rgba(234,179,8,0.3);
        color: #eab308;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e8ecf1;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.06); }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .stat-icon.blue { background: #dbeafe; color: #1d4ed8; }
    .stat-icon.green { background: #d1fae5; color: #065f46; }
    .stat-icon.orange { background: #fef3c7; color: #b45309; }
    .stat-info .number { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1.2; }
    .stat-info .label { font-size: 13px; color: #94a3b8; margin-top: 2px; }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .panel {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
    }
    .panel-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .panel-header h3 { font-size: 15px; font-weight: 700; color: #0f172a; }
    .panel-header i { color: #eab308; font-size: 16px; }
    .panel-body { padding: 24px; }

    .alert { padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 14px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    .form-group { margin-bottom: 16px; }
    .form-group label { font-weight: 600; font-size: 13px; display: block; margin-bottom: 5px; color: #1e293b; }
    .form-group label .required { color: #ef4444; }
    .form-control {
        width: 100%;
        padding: 9px 13px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        background: #fff;
        transition: border-color 0.2s;
    }
    .form-control:focus { outline: none; border-color: #0f3b5e; }
    .form-control[type="file"] { padding: 7px 13px; }
    textarea.form-control { min-height: 70px; resize: vertical; }

    .btn-primary {
        padding: 10px 28px;
        background: #0f3b5e;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary:hover { background: #0a2a44; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15,59,94,0.25); }

    .upload-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .upload-table th { text-align: left; padding: 10px 14px; background: #f8fafc; font-weight: 600; color: #1e293b; border-bottom: 2px solid #e2e8f0; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
    .upload-table td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .upload-table tr:hover td { background: #f8fafc; }
    .upload-table tr:last-child td { border-bottom: none; }

    .btn-sm {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }
    .btn-info { background: #dbeafe; color: #1d4ed8; }
    .btn-info:hover { background: #93c5fd; }
    .btn-danger { background: #fef2f2; color: #991b1b; }
    .btn-danger:hover { background: #fecaca; }

    .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-state i { font-size: 36px; opacity: 0.25; display: block; margin-bottom: 10px; }
    .empty-state p { font-size: 14px; }

    @media (max-width: 992px) {
        .content-grid { grid-template-columns: 1fr; }
        .stats-row { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        .stats-row { grid-template-columns: 1fr; }
        .anggota-header { flex-direction: column; }
    }
</style>
@endsection

@section('content')
@php
    $totalUpload = $uploads->count();
    $uploadBulanIni = $uploads->filter(fn($u) => \Carbon\Carbon::parse($u->tanggal_upload)->isCurrentMonth())->count();
@endphp

{{-- Header --}}
<div class="anggota-header">
    <div class="greet">
        <h1>Selamat datang, <span>{{ auth()->user()->nama_admin }}</span> 👋</h1>
        <p>{{ date('l, d F Y') }} — Panel Upload Dokumen Anggota</p>
    </div>
    <div class="badge-divisi">
        <i class="fas fa-building"></i> Divisi {{ auth()->user()->divisi ?? '-' }}
    </div>
</div>

@if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-file-alt"></i></div>
        <div class="stat-info">
            <div class="number">{{ $totalUpload }}</div>
            <div class="label">Total Dokumen Saya</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="number">{{ $uploadBulanIni }}</div>
            <div class="label">Upload Bulan Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-folder-open"></i></div>
        <div class="stat-info">
            <div class="number">{{ $folders->count() }}</div>
            <div class="label">Folder Tersedia</div>
        </div>
    </div>
</div>

{{-- Content Grid --}}
<div class="content-grid">

    {{-- Upload Form --}}
    <div class="panel">
        <div class="panel-header">
            <i class="fas fa-cloud-upload-alt"></i>
            <h3>Upload Dokumen Baru</h3>
        </div>
        <div class="panel-body">
            <form action="{{ route('anggota.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Folder Dokumen <span class="required">*</span></label>
                    <select name="folder_id" required class="form-control">
                        <option value="">-- Pilih Folder --</option>
                        @foreach($folders as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->nama }}@if($folder->divisi !== 'Semua') ({{ $folder->divisi }})@endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Judul Dokumen <span class="required">*</span></label>
                    <input type="text" name="judul" class="form-control" required placeholder="Judul dokumen">
                </div>
                <div class="form-group">
                    <label>Tanggal <span class="required">*</span></label>
                    <input type="date" name="tanggal_upload" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label>File <span class="required">*</span></label>
                    <input type="file" name="file_dokumen" class="form-control" required>
                    <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;"><i class="fas fa-info-circle"></i> Maks 50MB</small>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
                <button type="submit" class="btn-primary"><i class="fas fa-upload"></i> Upload Dokumen</button>
            </form>
        </div>
    </div>

    {{-- Riwayat Upload --}}
    <div class="panel">
        <div class="panel-header">
            <i class="fas fa-history"></i>
            <h3>Riwayat Upload Saya</h3>
        </div>
        <div class="panel-body" style="padding:0; overflow-x:auto;">
            @if($uploads->isEmpty())
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <p>Belum ada dokumen yang diupload.</p>
            </div>
            @else
            <table class="upload-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Folder</th>
                        <th>Judul</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($uploads as $up)
                    <tr>
                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($up->tanggal_upload)->format('d/m/Y') }}</td>
                        <td>
                            <span style="background:#f1f5f9; padding:2px 10px; border-radius:10px; font-size:11px; font-weight:600; color:#475569;">
                                {{ $up->folder->nama ?? '-' }}
                            </span>
                        </td>
                        <td style="max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $up->judul }}">{{ $up->judul }}</td>
                        <td>
                            <div style="display:flex; gap:4px;">
                                <a href="{{ Storage::url('uploads/anggota/' . $up->file_name) }}" target="_blank" class="btn-sm btn-info">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('anggota.delete', $up->id) }}" class="btn-sm btn-danger" onclick="return confirm('Hapus dokumen ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

</div>
@endsection
