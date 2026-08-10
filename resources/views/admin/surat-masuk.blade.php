@extends('layouts.admin')

@section('title', 'Kelola Surat Masuk - CEKIDOT')

@section('styles')
<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .header h1 {
        font-size: 24px;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .header h1 i { color: #eab308; }
    .header .info {
        color: #64748b;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .header .info .badge-count {
        background: #dc2626;
        color: #fff;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .header .info .total-count {
        background: #f1f5f9;
        color: #1e293b;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .header .admin-welcome {
        font-size: 14px;
        color: #64748b;
    }
    .header .admin-welcome i { color: #eab308; margin-right: 4px; }

    .alert {
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .alert-success i { color: #16a34a; font-size: 18px; }

    .surat-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 800px;
    }
    .surat-table th {
        text-align: left;
        padding: 12px 14px;
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 2px solid #e2e8f0;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .surat-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .surat-table tr:hover td {
        background: #f8fafc;
    }
    .surat-table tr:last-child td {
        border-bottom: none;
    }
    .surat-table tr.belum-dibaca td {
        background: #fef3c7;
    }
    .surat-table tr.belum-dibaca:hover td {
        background: #fde68a;
    }

    .badge-status {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
    }
    .badge-status.baru {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-status.dibaca {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-hapus {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        background: #fef2f2;
        color: #991b1b;
    }
    .btn-hapus:hover {
        background: #fecaca;
        transform: scale(1.02);
    }
    .btn-hapus i { font-size: 13px; }

    .btn-file {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        background: #dbeafe;
        color: #1d4ed8;
    }
    .btn-file:hover {
        background: #93c5fd;
    }
    .btn-file i { font-size: 14px; }

    .btn-tandai {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        background: #d1fae5;
        color: #065f46;
    }
    .btn-tandai:hover {
        background: #a7f3d0;
    }
    .btn-tandai i { font-size: 12px; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 48px;
        opacity: 0.3;
        display: block;
        margin-bottom: 16px;
    }
    .empty-state h3 {
        font-size: 18px;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 20px;
        max-width: 750px;
        width: 100%;
        max-height: 92vh;
        overflow-y: auto;
        padding: 32px;
        box-shadow: 0 30px 80px rgba(0,0,0,0.35);
        animation: modalIn 0.3s ease-out;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-box .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e8ecf1;
    }
    .modal-box .modal-header h3 {
        font-size: 20px;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-box .modal-header h3 i { color: #eab308; }
    .modal-box .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.3s;
        padding: 0 8px;
        border-radius: 6px;
        line-height: 1;
    }
    .modal-box .modal-close:hover {
        color: #dc2626;
        background: #fef2f2;
    }

    .modal-box .file-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 24px;
        background: #f8fafc;
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 16px;
    }
    .modal-box .file-info .item {
        display: flex;
        flex-direction: column;
    }
    .modal-box .file-info .item .label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    .modal-box .file-info .item .value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
        word-break: break-all;
    }
    .modal-box .file-preview {
        background: #f1f5f9;
        border-radius: 10px;
        margin-bottom: 16px;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .modal-box .file-preview iframe {
        width: 100%;
        height: 400px;
        border: none;
        border-radius: 10px;
    }
    .modal-box .file-preview .no-preview {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }
    .modal-box .file-preview .no-preview i {
        font-size: 48px;
        display: block;
        margin-bottom: 12px;
        opacity: 0.3;
    }
    .modal-box .file-preview .no-preview .ext {
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
    }
    .modal-box .security-warning {
        background: #fef3c7;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border: 1px solid #fde68a;
    }
    .modal-box .security-warning i {
        color: #b45309;
        font-size: 18px;
        margin-top: 2px;
    }
    .modal-box .security-warning div {
        font-size: 13px;
        color: #92400e;
    }
    .modal-box .security-warning div strong {
        display: block;
    }
    .modal-box .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    .modal-box .modal-actions .btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .modal-box .modal-actions .btn-primary {
        background: #0f3b5e;
        color: #fff;
    }
    .modal-box .modal-actions .btn-primary:hover {
        background: #0a2a44;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15,59,94,0.3);
    }
    .modal-box .modal-actions .btn-secondary {
        background: #f1f5f9;
        color: #1e293b;
    }
    .modal-box .modal-actions .btn-secondary:hover {
        background: #e2e8f0;
    }

    .confirm-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .confirm-overlay.show { display: flex; }
    .confirm-box {
        background: #ffffff;
        border-radius: 20px;
        padding: 32px 36px;
        max-width: 440px;
        width: 100%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: modalIn 0.3s ease;
    }
    .confirm-box .confirm-icon {
        font-size: 56px;
        margin-bottom: 12px;
        color: #dc2626;
    }
    .confirm-box .confirm-title {
        font-size: 20px;
        font-weight: 700;
        color: #0f3b5e;
        margin-bottom: 8px;
    }
    .confirm-box .confirm-text {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    .confirm-box .confirm-text .highlight {
        color: #dc2626;
        font-weight: 600;
    }
    .confirm-box .confirm-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    .confirm-box .confirm-actions .confirm-btn {
        padding: 10px 28px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .confirm-box .confirm-actions .confirm-btn-cancel {
        background: #f1f5f9;
        color: #475569;
    }
    .confirm-box .confirm-actions .confirm-btn-confirm {
        background: #dc2626;
        color: #fff;
    }
    .confirm-box .confirm-actions .confirm-btn-confirm:hover {
        background: #b91c1c;
        transform: scale(1.02);
    }

    .surat-cards { display: none; }

    @media (max-width: 992px) {
        .surat-table { display: none; }
        .surat-cards { display: block; }
        .surat-card-item {
            background: #fff;
            border: 1px solid #e8ecf1;
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 12px;
            transition: all 0.3s;
        }
        .surat-card-item.belum-dibaca {
            background: #fef3c7;
            border-color: #fde68a;
        }
        .surat-card-item .card-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 8px;
        }
        .surat-card-item .card-row .left { flex: 1; min-width: 200px; }
        .surat-card-item .card-row .right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .surat-card-item .nomor-surat {
            font-weight: 600;
            color: #0f3b5e;
            font-size: 14px;
        }
        .surat-card-item .tanggal { font-size: 12px; color: #64748b; }
        .surat-card-item .asal { font-size: 13px; color: #64748b; }
        .surat-card-item .nama-pengirim {
            font-size: 13px;
            color: #0f3b5e;
            font-weight: 600;
        }
        .surat-card-item .nama-pengirim i { color: #eab308; margin-right: 4px; }
        .surat-card-item .no_hp {
            font-size: 13px;
            color: #22c55e;
            font-weight: 500;
        }
        .surat-card-item .no_hp i { margin-right: 4px; }
        .surat-card-item .perihal {
            font-weight: 500;
            font-size: 14px;
            color: #1e293b;
            margin-top: 2px;
        }
        .surat-card-item .meta {
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 4px;
        }
        .surat-card-item .meta i { width: 14px; }
        .surat-card-item .card-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #f1f5f9;
        }
        .modal-box { padding: 20px; }
        .modal-box .file-info { grid-template-columns: 1fr; gap: 4px; }
        .modal-box .file-preview iframe { height: 250px; }
        .modal-box .modal-actions { flex-wrap: wrap; }
        .modal-box .modal-actions .btn { width: 100%; justify-content: center; }
        .confirm-box { padding: 24px 20px; max-width: 360px; }
    }

    @media (max-width: 768px) {
        .header { flex-direction: column; align-items: flex-start; }
        .surat-card-item .card-row { flex-direction: column; }
        .surat-card-item .card-row .right { width: 100%; justify-content: flex-start; }
        .modal-box .file-preview iframe { height: 180px; }
        .confirm-box .confirm-icon { font-size: 44px; }
        .confirm-box .confirm-title { font-size: 18px; }
    }
</style>
@endsection

@section('content')
<div class="header">
    <div>
        <h1><i class="fas fa-inbox"></i> Surat Masuk</h1>
        <div class="info">
            <span class="total-count"><i class="fas fa-envelope"></i> Total: {{ count($surat) }}</span>
            @if($total_baru > 0)
            <span class="badge-count">{{ $total_baru }} belum dibaca</span>
            <span style="color:#dc2626;">| {{ $total_baru }} surat baru</span>
            @else
            <span style="color:#16a34a;">| Semua surat sudah dibaca ✅</span>
            @endif
        </div>
    </div>
    <div class="admin-welcome">
        <i class="fas fa-user-circle"></i> {{ auth()->user()->nama_admin ?? 'Admin' }}
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="surat-container">
    <div class="surat-table-wrapper">
        <table class="surat-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th style="min-width:140px;">Nomor Surat</th>
                    <th style="min-width:110px;">Tgl Surat</th>
                    <th style="min-width:150px;">Asal Instansi</th>
                    <th style="min-width:130px;">Nama Pengirim</th>
                    <th style="min-width:120px;">No HP/WA</th>
                    <th style="min-width:140px;">Perihal</th>
                    <th style="min-width:100px;">Status</th>
                    <th style="min-width:80px;">File</th>
                    <th style="min-width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($surat->isEmpty())
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>Belum Ada Surat Masuk</h3>
                            <p style="font-size:14px;">Surat yang dikirim oleh tamu akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
                @else
                @php $no=1; @endphp
                @foreach($surat as $s)
                @php $is_belum_dibaca = ($s->dibaca == 0 || $s->dibaca === null); @endphp
                <tr class="{{ $is_belum_dibaca ? 'belum-dibaca' : '' }}">
                    <td>{{ $no++ }}</td>
                    <td>
                        <div style="font-size:13px; font-weight:600; color:#0f3b5e;">{{ $s->nomor_surat ?? '-' }}</div>
                        <div style="font-size:11px; color:#94a3b8;">Masuk: {{ \Carbon\Carbon::parse($s->tanggal_masuk)->format('d/m/Y H:i') }}</div>
                    </td>
                    <td>{{ !empty($s->tanggal_surat) ? \Carbon\Carbon::parse($s->tanggal_surat)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $s->asal_instansi ?? '-' }}</td>
                    <td>
                        @if(!empty($s->nama_pengirim))
                        <div style="font-weight:500; color:#0f3b5e;">{{ $s->nama_pengirim }}</div>
                        @else
                        <span style="color:#94a3b8; font-size:12px;">-</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($s->no_hp))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->no_hp) }}" target="_blank" style="color:#22c55e; text-decoration:none; font-weight:500;">
                            <i class="fab fa-whatsapp"></i> {{ $s->no_hp }}
                        </a>
                        @else
                        <span style="color:#94a3b8; font-size:12px;">-</span>
                        @endif
                    </td>
                    <td><div style="font-weight:500;">{{ $s->perihal }}</div></td>
                    <td>
                        @if($is_belum_dibaca)
                        <span class="badge-status baru">🔴 Baru</span>
                        @else
                        <span class="badge-status dibaca">✅ Dibaca</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($s->file_surat))
                        <button class="btn-file" onclick="openFileModal('{{ $s->id }}', '{{ $s->file_surat }}', '{{ $s->asal_instansi ?? '-' }}', '{{ $s->perihal }}', '{{ $s->nama_pengirim ?? '-' }}')">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        @else
                        <span style="color:#94a3b8; font-size:12px;">-</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:4px; flex-wrap:wrap;">
                            @if($is_belum_dibaca)
                            <a href="{{ route('admin.surat.tandai', $s->id) }}" class="btn-tandai" onclick="return confirm('Tandai surat ini sudah dibaca?')">
                                <i class="fas fa-check"></i> Tandai
                            </a>
                            @endif
                            <button class="btn-hapus" onclick="confirmDelete({{ $s->id }}, '{{ $s->nomor_surat ?? 'Tanpa Nomor' }}')" title="Hapus surat">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="surat-cards">
        @if($surat->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Belum Ada Surat Masuk</h3>
            <p style="font-size:14px;">Surat yang dikirim oleh tamu akan muncul di sini</p>
        </div>
        @else
        @php $no=1; @endphp
        @foreach($surat as $s)
        @php $is_belum_dibaca = ($s->dibaca == 0 || $s->dibaca === null); @endphp
        <div class="surat-card-item {{ $is_belum_dibaca ? 'belum-dibaca' : '' }}">
            <div class="card-row">
                <div class="left">
                    <div class="nomor-surat">{{ $no++ }}. {{ $s->nomor_surat ?? '-' }}
                        @if($is_belum_dibaca)
                        <span style="background:#dc2626; color:#fff; font-size:8px; padding:1px 8px; border-radius:10px; margin-left:6px;">BARU</span>
                        @endif
                    </div>
                    <div class="tanggal"><i class="fas fa-calendar"></i> Tgl Surat: {{ !empty($s->tanggal_surat) ? \Carbon\Carbon::parse($s->tanggal_surat)->format('d/m/Y') : '-' }}</div>
                    <div class="asal">Asal: {{ $s->asal_instansi ?? '-' }}</div>
                    @if(!empty($s->nama_pengirim))
                    <div class="nama-pengirim"><i class="fas fa-user"></i> {{ $s->nama_pengirim }}</div>
                    @endif
                    @if(!empty($s->no_hp))
                    <div class="no_hp"><i class="fab fa-whatsapp"></i> {{ $s->no_hp }}</div>
                    @endif
                    <div class="perihal">Perihal: {{ $s->perihal }}</div>
                    <div class="meta">
                        <span><i class="fas fa-clock"></i> Masuk: {{ \Carbon\Carbon::parse($s->tanggal_masuk)->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($s->keterangan)
                    <div style="font-size:12px; color:#64748b; margin-top:4px;">
                        Ket: {{ substr($s->keterangan, 0, 60) }}{{ strlen($s->keterangan) > 60 ? '...' : '' }}
                    </div>
                    @endif
                </div>
                <div class="right">
                    @if(!empty($s->file_surat))
                    <button class="btn-file" onclick="openFileModal('{{ $s->id }}', '{{ $s->file_surat }}', '{{ $s->asal_instansi ?? '-' }}', '{{ $s->perihal }}', '{{ $s->nama_pengirim ?? '-' }}')">
                        <i class="fas fa-eye"></i> Lihat
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-actions">
                @if($is_belum_dibaca)
                <a href="{{ route('admin.surat.tandai', $s->id) }}" class="btn-tandai" onclick="return confirm('Tandai surat ini sudah dibaca?')">
                    <i class="fas fa-check"></i> Tandai Dibaca
                </a>
                @endif
                <button class="btn-hapus" onclick="confirmDelete({{ $s->id }}, '{{ $s->nomor_surat ?? 'Tanpa Nomor' }}')">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>

<!-- Confirm Delete Modal -->
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="confirm-title">Hapus Surat?</div>
        <div class="confirm-text">
            Apakah Anda yakin ingin menghapus surat dengan nomor <br>
            <span class="highlight" id="confirmNomorSurat">-</span>?
            <br><small style="color:#94a3b8;">Data yang dihapus tidak dapat dikembalikan.</small>
        </div>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" onclick="closeConfirm()">
                <i class="fas fa-times"></i> Batal
            </button>
            <button class="confirm-btn confirm-btn-confirm" id="confirmDeleteBtn">
                <i class="fas fa-trash"></i> Ya, Hapus!
            </button>
        </div>
    </div>
</div>

<!-- File Modal -->
<div class="modal-overlay" id="fileModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-file"></i> <span id="modalTitle">Preview File</span></h3>
            <button class="modal-close" onclick="closeFileModal()">&times;</button>
        </div>
        <div class="file-info">
            <div class="item">
                <span class="label">Nama File</span>
                <span class="value" id="modalFileName">-</span>
            </div>
            <div class="item">
                <span class="label">Asal Instansi</span>
                <span class="value" id="modalAsal">-</span>
            </div>
            <div class="item">
                <span class="label">Nama Pengirim</span>
                <span class="value" id="modalNamaPengirim">-</span>
            </div>
            <div class="item" style="grid-column: 1 / -1;">
                <span class="label">Perihal</span>
                <span class="value" id="modalPerihal">-</span>
            </div>
        </div>
        <div class="file-preview" id="modalFilePreview">
            <div class="no-preview">
                <i class="fas fa-file"></i>
                <span class="ext">Memuat file...</span>
            </div>
        </div>
        <div class="security-warning">
            <i class="fas fa-shield-alt"></i>
            <div>
                <strong>⚠️ Peringatan Keamanan</strong>
                Pastikan file aman sebelum dibuka. File dari pengirim tidak dikenal berpotensi mengandung virus.
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeFileModal()"><i class="fas fa-times"></i> Tutup</button>
            <a href="#" class="btn btn-primary" id="btnDownloadFile" download><i class="fas fa-download"></i> Download</a>
        </div>
    </div>
</div>

<script>
var deleteId = null;

function confirmDelete(id, nomorSurat) {
    deleteId = id;
    document.getElementById('confirmNomorSurat').textContent = nomorSurat;
    document.getElementById('confirmOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeConfirm() {
    document.getElementById('confirmOverlay').classList.remove('show');
    document.body.style.overflow = 'auto';
    deleteId = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteId !== null) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.surat.destroy') }}';
        form.innerHTML = '<input type="hidden" name="delete_id" value="' + deleteId + '">@csrf';
        document.body.appendChild(form);
        form.submit();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirm();
        closeFileModal();
    }
});

function openFileModal(id, fileName, asal, perihal, namaPengirim) {
    const modal = document.getElementById('fileModal');
    const ext = fileName.split('.').pop().toLowerCase();
    const isImage = ['jpg','jpeg','png','gif','webp','bmp'].includes(ext);
    const isPDF = ext === 'pdf';
    
    document.getElementById('modalTitle').textContent = fileName;
    document.getElementById('modalFileName').textContent = fileName;
    document.getElementById('modalAsal').textContent = asal || '-';
    document.getElementById('modalNamaPengirim').textContent = namaPengirim || '-';
    document.getElementById('modalPerihal').textContent = perihal;
    
    const preview = document.getElementById('modalFilePreview');
    const filePath = '{{ asset('storage/uploads/surat') }}/' + fileName;
    
    if (isImage) {
        preview.innerHTML = `<img src="${filePath}" style="width:100%; max-height:400px; object-fit:contain; border-radius:8px;" alt="${fileName}">`;
    } else if (isPDF) {
        preview.innerHTML = `<iframe src="${filePath}#toolbar=1" style="width:100%; height:400px; border:none; border-radius:8px;"></iframe>`;
    } else {
        preview.innerHTML = `
            <div class="no-preview">
                <i class="fas fa-file"></i>
                <span class="ext">File ${ext.toUpperCase()}</span>
                <p style="font-size:13px; color:#94a3b8; margin-top:8px;">File tidak dapat ditampilkan langsung di browser. Silakan download.</p>
            </div>
        `;
    }
    
    document.getElementById('btnDownloadFile').href = filePath;
    document.getElementById('btnDownloadFile').download = fileName;
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeFileModal() {
    document.getElementById('fileModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

document.getElementById('fileModal').addEventListener('click', function(e) {
    if (e.target === this) closeFileModal();
});
</script>
@endsection