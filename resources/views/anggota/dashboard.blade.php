@extends('layouts.app')
@section('title', 'Arsip Dokumen - CEKIDOT')

@section('styles')
<style>
    html, body { margin: 0; padding: 0; width: 100%; overflow-x: hidden; }

    /* ===== HERO ===== */
    .arsip-hero {
        position: relative;
        background: linear-gradient(135deg, #0f3b5e 0%, #1a5a7a 60%, #256a8c 100%);
        padding: 90px 0 110px;
        overflow: hidden;
    }
    .arsip-hero::after {
        content: '';
        position: absolute; inset: 0;
        background-image:
            radial-gradient(circle at 12% 20%, rgba(234,179,8,0.15) 0%, transparent 40%),
            radial-gradient(circle at 88% 85%, rgba(255,255,255,0.08) 0%, transparent 45%),
            repeating-linear-gradient(45deg, transparent, transparent 30px, rgba(255,255,255,0.03) 30px, rgba(255,255,255,0.03) 31px);
        pointer-events: none;
    }
    .arsip-hero .container { position: relative; z-index: 1; }
    .hero-grid {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
    }
    .hero-text { flex: 1; }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(234,179,8,0.15);
        border: 1px solid rgba(234,179,8,0.3);
        color: #fcd34d;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 6px 16px;
        border-radius: 30px;
        margin-bottom: 18px;
    }
    .hero-text h1 {
        font-size: 36px;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 10px;
    }
    .hero-text h1 span { color: #eab308; }
    .hero-text .hero-sub { font-size: 16px; color: rgba(255,255,255,0.75); max-width: 520px; line-height: 1.6; }
    .hero-stats { display: flex; gap: 16px; margin-top: 28px; flex-wrap: wrap; }
    .hero-stat {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        backdrop-filter: blur(6px);
        border-radius: 14px;
        padding: 16px 22px;
        min-width: 140px;
    }
    .hero-stat .num { font-size: 28px; font-weight: 800; color: #eab308; line-height: 1; }
    .hero-stat .lbl { font-size: 12px; color: rgba(255,255,255,0.6); margin-top: 6px; }
    .hero-art {
        flex-shrink: 0;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        border: 2px dashed rgba(234,179,8,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 72px;
        color: #eab308;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    }

    /* ===== SECTION ===== */
    .arsip-section {
        position: relative;
        padding: 70px 0 80px;
        background: linear-gradient(160deg, #eef2f7 0%, #dce3ed 30%, #e8edf5 60%, #d5dee8 100%);
    }
    .arsip-section::after {
        content: '';
        position: absolute; inset: 0;
        background-image:
            radial-gradient(circle at 10% 20%, rgba(15,59,94,0.06) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(234,179,8,0.08) 0%, transparent 35%);
        pointer-events: none;
    }
    .arsip-section .container { position: relative; z-index: 1; }
    .arsip-section.alt { background: #f1f5f9; }
    .section-header { text-align: center; margin-bottom: 40px; }
    .section-header .header-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 62px; height: 62px;
        background: linear-gradient(135deg, #0f3b5e, #1a5a7a);
        border-radius: 50%; margin-bottom: 12px;
        box-shadow: 0 8px 30px rgba(15,59,94,0.2);
    }
    .section-header .header-icon i { font-size: 26px; color: #eab308; }
    .section-header .header-line {
        width: 70px; height: 4px;
        background: linear-gradient(90deg, #eab308, #f59e0b);
        border-radius: 4px; margin: 0 auto 14px;
    }
    .section-header h2 { font-size: 30px; font-weight: 800; color: #0f3b5e; letter-spacing: -0.5px; }
    .section-header h2 span { color: #eab308; }
    .section-header .subtitle { font-size: 14px; color: #64748b; margin-top: 4px; }

    /* ===== TOOL GRID (cari + upload) ===== */
    .tool-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; max-width: 1080px; margin: 0 auto; }
    .tool-card {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(8px);
        border-radius: 16px;
        padding: 26px 26px 24px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.06);
        border: 1px solid rgba(255,255,255,0.6);
        transition: all 0.3s;
    }
    .tool-card:hover { transform: translateY(-4px); box-shadow: 0 12px 45px rgba(15,59,94,0.10); }
    .tool-card h3 {
        font-size: 16px; font-weight: 700; color: #0f3b5e;
        display: flex; align-items: center; gap: 9px; margin-bottom: 16px;
    }
    .tool-card h3 i { color: #eab308; }
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 5px; }
    .form-group label .required { color: #dc2626; }
    .form-control {
        width: 100%; padding: 10px 12px; font-size: 14px; font-family: inherit;
        color: #1e293b; background: #fff;
        border: 1.5px solid #cbd5e1; border-radius: 9px; transition: all 0.2s;
    }
    .form-control:focus { outline: none; border-color: #0f3b5e; box-shadow: 0 0 0 3px rgba(15,59,94,0.12); }
    .form-group small { font-size: 12px; color: #94a3b8; }
    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        padding: 10px 20px; font-size: 14px; font-weight: 600; font-family: inherit;
        border: none; border-radius: 9px; cursor: pointer; text-decoration: none; transition: all 0.2s;
    }
    .btn-primary { background: #0f3b5e; color: #fff; }
    .btn-primary:hover { background: #eab308; color: #0f3b5e; }
    .btn-secondary { background: #f1f5f9; color: #334155; }
    .btn-secondary:hover { background: #e2e8f0; }
    .btn-sm { padding: 6px 12px; font-size: 13px; border-radius: 8px; }
    .btn-info { background: #dbeafe; color: #1d4ed8; }
    .btn-info:hover { background: #bfdbfe; }
    .btn-warning { background: #fef3c7; color: #b45309; }
    .btn-warning:hover { background: #fde68a; }

    /* ===== TREE PANEL ===== */
    .tree-panel {
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(8px);
        border-radius: 16px;
        padding: 26px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.06);
        border: 1px solid rgba(255,255,255,0.6);
        max-width: 1080px; margin: 0 auto;
    }
    .tree-toolbar { display: flex; justify-content: flex-end; margin-bottom: 16px; }
    .folder-tree details {
        border: 1px solid #e8ecf1; border-radius: 12px; margin-bottom: 10px; background: #fff;
    }
    .folder-tree details > summary {
        list-style: none; cursor: pointer; padding: 14px 18px;
        display: flex; align-items: center; gap: 12px;
        font-weight: 700; font-size: 15px; color: #0f172a;
        background: #f8fafc; border-radius: 12px;
    }
    .folder-tree details > summary::-webkit-details-marker { display: none; }
    .folder-tree details > summary::before {
        content: '\f107'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        color: #94a3b8; transition: transform 0.2s; width: 14px;
    }
    .folder-tree details[open] > summary::before { transform: rotate(180deg); }
    .folder-tree details > summary .icon { color: #eab308; }
    .folder-tree details > summary .count {
        margin-left: auto; background: #e2e8f0; color: #475569;
        font-size: 11px; font-weight: 700; padding: 2px 12px; border-radius: 20px;
    }
    .folder-tree .tree-child { padding: 10px 18px 18px; }
    .folder-tree details.child {
        border-color: #eef2f7; background: #fcfdfe;
    }
    .folder-tree details.child > summary { background: #fff; font-weight: 600; font-size: 14px; padding: 12px 16px; }
    .folder-tree .child-label {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 600; color: #334155;
        padding: 8px 12px; background: #f8fafc; border-radius: 8px; margin: 8px 0 6px;
    }
    .folder-tree .child-label i { color: #eab308; }
    .tree-upload-list { list-style: none; }
    .tree-upload-list li {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 12px; border-bottom: 1px dashed #e2e8f0; font-size: 13px;
    }
    .tree-upload-list li:last-child { border-bottom: none; }
    .tree-upload-list li .file-actions { margin-left: auto; display: flex; gap: 6px; flex-shrink: 0; }

    /* ===== FILE TABLE ===== */
    .file-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .file-table th {
        text-align: left; font-size: 12px; font-weight: 600; color: #64748b;
        text-transform: uppercase; letter-spacing: 0.4px;
        padding: 10px 12px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; white-space: nowrap;
    }
    .file-table td { padding: 11px 12px; border-bottom: 1px solid #f1f5f9; color: #1e293b; vertical-align: middle; }
    .file-table tbody tr:hover { background: #f8fafc; }
    .file-table tbody tr:last-child td { border-bottom: none; }
    .text-muted { color: #94a3b8; }
    .text-right { text-align: right; }

    /* ===== ALERT ===== */
    .alert {
        padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 500;
        margin: 20px auto 0; max-width: 1080px;
    }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* ===== MODAL ===== */
    .modal {
        position: fixed; inset: 0; background: rgba(15,23,42,0.6);
        display: none; align-items: center; justify-content: center; z-index: 300; padding: 20px;
    }
    .modal.show { display: flex; }
    .modal-box {
        background: #fff; border-radius: 16px; padding: 24px; width: 100%; max-width: 900px;
        max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .modal-box h3 { font-size: 16px; font-weight: 700; margin-bottom: 12px; color: #0f172a; display: flex; align-items: center; gap: 8px; }
    .modal-box h3 i { color: #eab308; }
    .modal-box iframe, .modal-box img { width: 100%; height: 70vh; border: 0; border-radius: 8px; background: #f1f5f9; }

    @media (max-width: 992px) {
        .hero-grid { flex-direction: column; text-align: center; }
        .hero-text h1 { font-size: 30px; }
        .hero-sub { margin: 0 auto; }
        .hero-art { display: none; }
        .hero-stats { justify-content: center; }
        .tool-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .arsip-hero { padding: 80px 0 90px; }
        .hero-text h1 { font-size: 26px; }
        .section-header h2 { font-size: 24px; }
        .hero-stat { min-width: 100px; padding: 12px 16px; }
        .hero-stat .num { font-size: 22px; }
        .tree-panel { padding: 16px; }
        .file-table { font-size: 13px; }
        .file-table th, .file-table td { padding: 8px 8px; }
        .folder-tree details > summary { padding: 12px 14px; font-size: 14px; }
    }
    @media (max-width: 480px) {
        .hero-stats { flex-direction: column; width: 100%; }
        .hero-stat { width: 100%; }
    }
</style>
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<!-- HERO -->
<section class="arsip-hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-text">
                <span class="hero-badge"><i class="fas fa-user-shield"></i> Staf — {{ auth()->user()->divisi }}</span>
                <h1>Selamat datang, <span>{{ auth()->user()->nama_admin }}</span>!</h1>
                <p class="hero-sub">Kelola, unggah, dan arsipkan dokumen bidang Anda secara terpusat. Semua tercatat rapi dengan kontrol akses sesuai jabatan.</p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="num">{{ $total_dokumen }}</div>
                        <div class="lbl">Dokumen di Bidang</div>
                    </div>
                    <div class="hero-stat">
                        <div class="num">{{ $total_folder }}</div>
                        <div class="lbl">Folder Arsip</div>
                    </div>
                    <div class="hero-stat">
                        <div class="num">{{ $dokumen_saya }}</div>
                        <div class="lbl">Upload Saya</div>
                    </div>
                </div>
            </div>
            <div class="hero-art"><i class="fas fa-folder-open"></i></div>
        </div>
    </div>
</section>

<!-- CARI & UPLOAD -->
<section class="arsip-section">
    <div class="container">
        <div class="section-header">
            <div class="header-icon"><i class="fas fa-tasks"></i></div>
            <div class="header-line"></div>
            <h2>Cari & <span>Unggah</span> Dokumen</h2>
            <p class="subtitle">Temukan berkas dengan cepat atau simpan dokumen baru ke folder bidang Anda</p>
        </div>

        <div class="tool-grid">
            <div class="tool-card">
                <h3><i class="fas fa-search"></i> Cari Dokumen</h3>
                <form method="GET" action="{{ route('anggota.dashboard') }}">
                    <div class="form-group">
                        <label>Kata Kunci</label>
                        <input type="text" name="q" class="form-control" placeholder="Nama file, keterangan, atau tanggal..." value="{{ $search }}">
                    </div>
                    <div style="display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                        @if($search !== '')
                        <a href="{{ route('anggota.dashboard') }}" class="btn btn-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="tool-card">
                <h3><i class="fas fa-upload"></i> Upload Dokumen</h3>
                <form action="{{ route('anggota.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Folder Tujuan <span class="required">*</span></label>
                        <select name="folder_id" required class="form-control">
                            <option value="">-- Pilih Folder --</option>
                            @foreach($uploadSelect as $fid => $nama)
                            <option value="{{ $fid }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Judul Dokumen <span class="required">*</span></label>
                        <input type="text" name="judul" class="form-control" required placeholder="Judul dokumen">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Upload <span class="required">*</span></label>
                        <input type="date" name="tanggal_upload" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>File <span class="required">*</span></label>
                        <input type="file" name="file_dokumen" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.webp,.txt,.zip,.rar">
                        <small>PDF, gambar, dokumen office, zip. Maks 50MB.</small>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- STRUKTUR ARSIP -->
<section class="arsip-section alt">
    <div class="container">
        <div class="section-header">
            <div class="header-icon"><i class="fas fa-sitemap"></i></div>
            <div class="header-line"></div>
            <h2>Struktur Arsip Divisi <span>{{ auth()->user()->divisi }}</span></h2>
            <p class="subtitle">Pohon folder yang bisa dilipat & diperluas, mirip Windows Explorer</p>
        </div>

        <div class="tree-panel">
            @if($search !== '')
                @if($results->isEmpty())
                <p class="text-muted">Tidak ada dokumen yang cocok dengan <strong>"{{ $search }}"</strong>.</p>
                @else
                <table class="file-table">
                    <thead>
                        <tr>
                            <th>Nama File</th>
                            <th>Folder</th>
                            <th>Diunggah Oleh</th>
                            <th>Tanggal</th>
                            <th>Ukuran</th>
                            <th class="text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $up)
                        <tr>
                            <td>{{ $up->judul }} <small class="text-muted">({{ strtoupper($up->file_type) }})</small></td>
                            <td>{{ $up->folder->nama ?? '-' }}</td>
                            <td>{{ $up->user->nama_admin ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($up->tanggal_upload)->format('d/m/Y') }}</td>
                            <td>{{ $up->ukuran }}</td>
                            <td class="text-right">
                                @if($up->dapat_dipreview)
                                <button class="btn btn-sm btn-warning" onclick="openPreview('{{ Storage::url('uploads/anggota/' . $up->file_name) }}')"><i class="fas fa-eye"></i> Pratinjau</button>
                                @endif
                                <a href="{{ route('anggota.download', $up->id) }}" class="btn btn-sm btn-info"><i class="fas fa-download"></i> Unduh</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            @elseif($parents->isEmpty())
                <p class="text-muted">Belum ada folder untuk divisi Anda.</p>
            @else
                <div class="tree-toolbar">
                    <span class="btn btn-sm btn-secondary" style="cursor:pointer" onclick="toggleAll()" id="toggleAllBtn"><i class="fas fa-expand-arrows-alt"></i> Perluas Semua</span>
                </div>
                <div class="folder-tree" id="folderTree">
                    @foreach($parents as $parent)
                    <details>
                        <summary>
                            <i class="fas fa-folder icon"></i>
                            {{ $parent->nama }}
                            <span class="count">{{ $parent->uploads->count() + $parent->children->sum(fn($c) => $c->uploads->count()) }} dokumen</span>
                        </summary>
                        <div class="tree-child">
                            @if($parent->uploads->isNotEmpty())
                            <div class="child-label"><i class="fas fa-file-alt"></i> Dokumen Langsung di {{ $parent->nama }}</div>
                            <ul class="tree-upload-list">
                                @foreach($parent->uploads as $up)
                                <li>
                                    <i class="fas fa-file" style="color:#94a3b8"></i>
                                    <span><strong>{{ $up->judul }}</strong> <small class="text-muted">• {{ \Carbon\Carbon::parse($up->tanggal_upload)->format('d/m/Y') }} • {{ $up->ukuran }}</small></span>
                                    <span class="file-actions">
                                        @if($up->dapat_dipreview)
                                        <a href="javascript:void(0)" class="btn btn-sm btn-warning" onclick="openPreview('{{ Storage::url('uploads/anggota/' . $up->file_name) }}')"><i class="fas fa-eye"></i></a>
                                        @endif
                                        <a href="{{ route('anggota.download', $up->id) }}" class="btn btn-sm btn-info"><i class="fas fa-download"></i></a>
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                            @endif

                            @foreach($parent->children as $child)
                            <details class="child">
                                <summary>
                                    <i class="fas fa-folder icon"></i>
                                    {{ $child->nama }}
                                    <span class="count">{{ $child->uploads->count() }} dokumen</span>
                                </summary>
                                <div class="tree-child">
                                    @if($child->uploads->isEmpty())
                                    <p class="text-muted" style="padding:6px 0">Folder kosong.</p>
                                    @else
                                    <table class="file-table">
                                        <thead>
                                            <tr>
                                                <th>Nama File</th>
                                                <th>Jenis</th>
                                                <th>Tanggal Unggah</th>
                                                <th>Ukuran</th>
                                                <th class="text-right">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($child->uploads as $up)
                                            <tr>
                                                <td>{{ $up->judul }}</td>
                                                <td>{{ strtoupper($up->file_type) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($up->tanggal_upload)->format('d/m/Y') }}</td>
                                                <td>{{ $up->ukuran }}</td>
                                                <td class="text-right">
                                                    @if($up->dapat_dipreview)
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-warning" onclick="openPreview('{{ Storage::url('uploads/anggota/' . $up->file_name) }}')"><i class="fas fa-eye"></i> Pratinjau</a>
                                                    @endif
                                                    <a href="{{ route('anggota.download', $up->id) }}" class="btn btn-sm btn-info"><i class="fas fa-download"></i> Unduh</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                            </details>
                            @endforeach
                        </div>
                    </details>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<div class="modal" id="previewModal">
    <div class="modal-box">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <h3 style="margin:0"><i class="fas fa-eye"></i> Pratinjau Dokumen</h3>
            <button class="btn btn-sm btn-secondary" onclick="closePreview()">Tutup</button>
        </div>
        <div id="previewFrame"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openPreview(url) {
    var isImage = /\.(jpg|jpeg|png|gif|webp)(\?|$)/i.test(url);
    var frame = document.getElementById('previewFrame');
    frame.innerHTML = isImage
        ? '<img src="' + url + '" alt="Preview">'
        : '<iframe src="' + url + '"></iframe>';
    document.getElementById('previewModal').classList.add('show');
}
function closePreview() {
    document.getElementById('previewModal').classList.remove('show');
    document.getElementById('previewFrame').innerHTML = '';
}
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePreview(); });

function toggleAll() {
    var allOpen = document.querySelectorAll('#folderTree details[open]').length === document.querySelectorAll('#folderTree details').length;
    document.querySelectorAll('#folderTree details').forEach(function(d) {
        if (allOpen) { d.removeAttribute('open'); } else { d.setAttribute('open', ''); }
    });
    var btn = document.getElementById('toggleAllBtn');
    btn.innerHTML = allOpen
        ? '<i class="fas fa-expand-arrows-alt"></i> Perluas Semua'
        : '<i class="fas fa-compress-arrows-alt"></i> Ciutkan Semua';
}
</script>
@endsection