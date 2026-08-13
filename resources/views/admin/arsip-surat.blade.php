@extends('layouts.admin')

@section('title', 'Arsip Surat - CEKIDOT')

@section('styles')
<style>
    .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .header h1 { font-size:24px; color:#0f3b5e; display:flex; align-items:center; gap:10px; }
    .header h1 i { color:#eab308; }
    .header .info { color:#64748b; font-size:14px; }
    .alert { padding:12px 18px; border-radius:8px; margin-bottom:20px; display:flex; align-items:center; gap:10px; font-size:14px; }
    .alert-success { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
    .alert-danger  { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
    .stat-card { background:#fff; padding:18px 20px; border-radius:14px; border:1px solid #e8ecf1; display:flex; align-items:center; gap:14px; }
    .stat-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
    .stat-icon.blue { background:#dbeafe; color:#1d4ed8; }
    .stat-icon.green { background:#d1fae5; color:#065f46; }
    .stat-icon.orange { background:#fef3c7; color:#b45309; }
    .stat-icon.purple { background:#ede9fe; color:#7c3aed; }
    .stat-info .number { font-size:26px; font-weight:800; color:#0f172a; line-height:1.2; }
    .stat-info .label { font-size:13px; color:#94a3b8; margin-top:2px; }
    .upload-form { background:#f8fafc; padding:24px; border-radius:12px; margin-bottom:24px; border:1px solid #e8ecf1; }
    .upload-form h3 { font-size:15px; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px; margin-bottom:16px; }
    .upload-form h3 i { color:#eab308; }
    .upload-form .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .upload-form .form-group { margin-bottom:0; }
    .upload-form .form-group label { font-weight:600; font-size:13px; display:block; margin-bottom:4px; color:#1e293b; }
    .upload-form .form-group label .required { color:#ef4444; }
    .upload-form .form-group label .optional { color:#94a3b8; font-weight:400; font-size:11px; }
    .upload-form .form-group input, .upload-form .form-group textarea, .upload-form .form-group select { width:100%; padding:8px 12px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .upload-form .form-group textarea { min-height:60px; resize:vertical; }
    .file-upload-wrapper { position:relative; width:100%; }
    .file-upload-wrapper input[type="file"] { position:absolute; opacity:0; width:100%; height:100%; cursor:pointer; z-index:2; top:0; left:0; }
    .file-upload-wrapper .file-label { display:block; padding:8px 12px; background:#fff; border:1.5px solid #e2e8f0; border-radius:8px; color:#475569; font-size:13px; text-align:center; cursor:pointer; min-height:38px; line-height:20px; }
    .file-upload-wrapper .file-label i { margin-right:6px; color:#0f3b5e; }
    .format-hint { display:block; font-size:11px; color:#94a3b8; margin-top:6px; }
    .file-preview-wrapper { display:none; align-items:center; gap:10px; background:#f1f5f9; padding:6px 12px 6px 16px; border-radius:8px; margin-top:6px; border:1px solid #e2e8f0; }
    .file-preview-wrapper.show { display:flex; }
    .form-actions { margin-top:18px; display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
    .btn-upload { padding:10px 28px; background:#0f3b5e; color:#fff; border:none; border-radius:8px; font-weight:600; font-size:14px; cursor:pointer; transition:all 0.3s; display:inline-flex; align-items:center; gap:8px; }
    .btn-upload:hover { background:#0a2a44; }
    .filter-bar { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:16px; align-items:center; }
    .filter-bar form { display:flex; gap:10px; flex-wrap:wrap; flex:1; align-items:center; }
    .filter-bar input[type="text"], .filter-bar select { padding:8px 12px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .btn-filter { padding:8px 18px; background:#0f3b5e; color:#fff; border:none; border-radius:8px; font-weight:600; font-size:13px; cursor:pointer; transition:all 0.3s; display:inline-flex; align-items:center; gap:6px; text-decoration:none; }
    .btn-filter:hover { background:#0a2a44; }
    .btn-cetak { padding:8px 18px; background:#eab308; color:#fff; border:none; border-radius:8px; font-weight:600; font-size:13px; cursor:pointer; transition:all 0.3s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-cetak:hover { background:#ca8a04; }
    .table-wrapper { overflow-x:auto; border-radius:12px; border:1px solid #e8ecf1; background:#fff; }
    table { width:100%; border-collapse:collapse; font-size:14px; min-width:700px; }
    table th { text-align:left; padding:12px 16px; background:#f8fafc; font-weight:600; color:#1e293b; border-bottom:2px solid #e2e8f0; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; }
    table td { padding:12px 16px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
    table tr:hover td { background:#f8fafc; }
    table tr:last-child td { border-bottom:none; }
    .type-badge { display:inline-flex; align-items:center; gap:4px; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:600; text-transform:capitalize; }
    .type-badge.masuk { background:#dbeafe; color:#1d4ed8; }
    .type-badge.keluar { background:#fef3c7; color:#b45309; }
    .type-badge.internal { background:#ede9fe; color:#7c3aed; }
    .divisi-badge { display:inline-flex; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:600; background:#f1f5f9; color:#475569; }
    .action-group { display:flex; gap:6px; align-items:center; }
    .btn-action { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:6px; border:none; cursor:pointer; transition:all 0.3s; font-size:13px; text-decoration:none; }
    .btn-action.btn-download { background:#d1fae5; color:#065f46; }
    .btn-action.btn-download:hover { background:#a7f3d0; }
    .btn-action.btn-delete { background:#fef2f2; color:#991b1b; }
    .btn-action.btn-delete:hover { background:#fecaca; }
    .empty-state { text-align:center; padding:40px 20px; color:#94a3b8; }
    .empty-state i { font-size:40px; opacity:0.3; display:block; margin-bottom:12px; }
    .empty-state h3 { font-size:17px; color:#1e293b; margin-bottom:4px; }
    .pagination-info { padding:12px 16px; border-top:1px solid #f1f5f9; background:#f8fafc; font-size:12px; color:#64748b; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; }
    .modal-overlay { display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:#fff; border-radius:20px; max-width:420px; width:100%; padding:32px; box-shadow:0 30px 80px rgba(0,0,0,0.35); text-align:center; }
    .modal-box .confirm-icon { font-size:56px; color:#dc2626; margin-bottom:12px; }
    .modal-box h3 { font-size:20px; color:#1e293b; margin-bottom:4px; }
    .modal-box p { color:#64748b; font-size:14px; margin-bottom:20px; }
    .modal-box .modal-actions { display:flex; gap:12px; justify-content:center; }
    .modal-box .modal-actions .btn { padding:10px 24px; border-radius:8px; font-weight:600; font-size:14px; border:none; cursor:pointer; transition:all 0.3s; text-decoration:none; display:inline-flex; align-items:center; gap:8px; }
    .modal-box .modal-actions .btn-secondary { background:#f1f5f9; color:#1e293b; }
    .modal-box .modal-actions .btn-danger { background:#dc2626; color:#fff; }
    @media (max-width:992px) { .stats-grid { grid-template-columns:repeat(2,1fr); } .upload-form .form-grid { grid-template-columns:1fr; } }
    @media (max-width:768px) { .stats-grid { grid-template-columns:1fr 1fr; gap:10px; } table { font-size:12px; min-width:600px; } }
</style>
@endsection

@section('content')
@php $user = auth()->user(); @endphp

<div class="header">
    <div>
        <h1><i class="fas fa-archive"></i> Arsip Surat</h1>
        <span class="info">
            @if($user->isSuperAdmin()) Kelola arsip surat seluruh divisi
            @else Arsip surat divisi {{ $user->divisi ?? '-' }}
            @endif
        </span>
    </div>
    <div style="font-size:14px; color:#64748b;">
        <i class="fas fa-user-circle" style="color:#eab308;"></i> {{ $user->nama_admin ?? 'Admin' }}
    </div>
</div>

@if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif
@if($errors->any())<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>@endif

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-file-alt"></i></div>
        <div class="stat-info"><div class="number">{{ $totalArsip }}</div><div class="label">Total Arsip</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-arrow-down"></i></div>
        <div class="stat-info"><div class="number">{{ $totalMasuk }}</div><div class="label">Surat Masuk</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-arrow-up"></i></div>
        <div class="stat-info"><div class="number">{{ $totalKeluar }}</div><div class="label">Surat Keluar</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-folder"></i></div>
        <div class="stat-info"><div class="number">{{ $totalInternal }}</div><div class="label">Surat Internal</div></div>
    </div>
</div>

<!-- Upload Form -->
<div class="upload-form">
    <h3><i class="fas fa-cloud-upload-alt"></i> Upload Arsip Surat</h3>
    <form method="POST" action="{{ route('admin.arsip.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Nomor Surat <span class="required">*</span></label>
                <input type="text" name="nomor_surat" placeholder="Contoh: 005/DISPAR/2026" required>
            </div>
            <div class="form-group">
                <label>Tanggal Surat <span class="required">*</span></label>
                <input type="date" name="tanggal_surat" required>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label>Perihal <span class="required">*</span></label>
                <input type="text" name="perihal" placeholder="Perihal surat" required>
            </div>
            <div class="form-group">
                <label>Jenis Surat <span class="required">*</span></label>
                <select name="jenis_surat" required>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                    <option value="internal">Internal</option>
                </select>
            </div>
            @if($user->isSuperAdmin())
            <div class="form-group">
                <label>Divisi <span class="required">*</span></label>
                <select name="divisi" required>
                    <option value="">-- Pilih Divisi --</option>
                    @foreach($divisi_list as $d)
                    <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="form-group">
                <label>File Surat <span class="required">*</span> <span class="optional">(Maks 10MB)</span></label>
                <div class="file-upload-wrapper">
                    <input type="file" name="file_surat" id="fileSurat" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                    <span class="file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih File (PDF, JPG, PNG, DOC, DOCX)</span>
                </div>
                <span class="format-hint"><i class="fas fa-info-circle"></i> Format: PDF, JPG, PNG, DOC, DOCX | Maks 10MB</span>
                <div class="file-preview-wrapper" id="filePreview">
                    <span style="font-size:18px; color:#0f3b5e;"><i class="fas fa-file"></i></span>
                    <span id="fileName">-</span>
                </div>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label>Keterangan <span class="optional">(Opsional)</span></label>
                <textarea name="keterangan" placeholder="Keterangan tambahan..."></textarea>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-upload"><i class="fas fa-upload"></i> Simpan Arsip</button>
        </div>
    </form>
</div>

<!-- Filter -->
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.arsip.index') }}">
        <input type="text" name="search" placeholder="Cari nomor, perihal..." value="{{ request('search') }}">
        @if($user->isSuperAdmin())
        <select name="divisi">
            <option value="">Semua Divisi</option>
            @foreach($divisi_list as $d)
            <option value="{{ $d }}" {{ request('divisi') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        @endif
        <select name="jenis">
            <option value="">Semua Jenis</option>
            <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
            <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
            <option value="internal" {{ request('jenis') == 'internal' ? 'selected' : '' }}>Internal</option>
        </select>
        <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Cari</button>
        @if(request('search') || request('divisi') || request('jenis'))
        <a href="{{ route('admin.arsip.index') }}" class="btn-filter" style="background:#64748b;"><i class="fas fa-times"></i> Reset</a>
        @endif
    </form>
    @if($user->isSuperAdmin())
    <a href="{{ route('admin.arsip.cetak', request()->only(['divisi'])) }}" class="btn-cetak" target="_blank">
        <i class="fas fa-print"></i> Cetak Laporan
    </a>
    @endif
</div>

<!-- Table -->
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th>Nomor Surat</th>
                <th>Perihal</th>
                <th>Tanggal</th>
                @if($user->isSuperAdmin())<th>Divisi</th>@endif
                <th>Jenis</th>
                <th>File</th>
                <th style="width:90px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if($arsip->isEmpty())
            <tr>
                <td colspan="{{ $user->isSuperAdmin() ? 8 : 7 }}">
                    <div class="empty-state">
                        <i class="fas fa-archive"></i>
                        <h3>Belum Ada Arsip</h3>
                        <p>Belum ada arsip surat yang diunggah untuk divisi {{ $user->divisi ?? 'ini' }}</p>
                    </div>
                </td>
            </tr>
            @else
            @php $no = $arsip->firstItem(); @endphp
            @foreach($arsip as $s)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="font-weight:500;">{{ $s->nomor_surat }}</td>
                <td>
                    <div style="font-weight:500;">{{ $s->perihal }}</div>
                    @if($s->keterangan)
                    <div style="font-size:12px; color:#64748b;">{{ substr($s->keterangan, 0, 50) }}{{ strlen($s->keterangan) > 50 ? '...' : '' }}</div>
                    @endif
                </td>
                <td>{{ $s->tanggal_surat->format('d M Y') }}</td>
                @if($user->isSuperAdmin())
                <td><span class="divisi-badge">{{ $s->divisi ?? '-' }}</span></td>
                @endif
                <td>
                    <span class="type-badge {{ $s->jenis_surat }}">
                        <i class="fas {{ $s->jenis_surat == 'masuk' ? 'fa-arrow-down' : ($s->jenis_surat == 'keluar' ? 'fa-arrow-up' : 'fa-folder-open') }}"></i>
                        {{ $s->jenis_surat }}
                    </span>
                    @if($s->file_size)
                    <span style="font-size:10px; color:#94a3b8; display:block;">{{ number_format($s->file_size / 1024, 1) }} KB</span>
                    @endif
                </td>
                <td>
                    <span style="font-size:12px; color:#475569; word-break:break-all;">{{ $s->file_name }}</span>
                    @if($s->uploader)
                    <span style="font-size:11px; color:#94a3b8; display:block;">Oleh: {{ $s->uploader->nama_admin }}</span>
                    @endif
                </td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('admin.arsip.download', $s->id) }}" class="btn-action btn-download" title="Unduh">
                            <i class="fas fa-download"></i>
                        </a>
                        @if($user->isSuperAdmin() || $s->uploaded_by === $user->id)
                        <button class="btn-action btn-delete" onclick="openDeleteModal({{ $s->id }}, '{{ addslashes($s->nomor_surat) }}')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    @if($arsip->hasPages())
    <div class="pagination-info">
        <span>Menampilkan {{ $arsip->firstItem() }} - {{ $arsip->lastItem() }} dari {{ $arsip->total() }} arsip</span>
        <div>{!! $arsip->links() !!}</div>
    </div>
    @endif
</div>

<!-- Modal Delete -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="confirm-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Hapus Arsip?</h3>
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus arsip ini?</p>
        <form method="POST" action="{{ route('admin.arsip.destroy') }}">
            @csrf
            <input type="hidden" name="delete_id" id="deleteId">
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">Batal</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openDeleteModal(id, nomor) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteMessage').textContent = 'Hapus arsip "' + nomor + '"? Tindakan ini tidak dapat dibatalkan.';
    document.getElementById('deleteModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow = 'auto';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.show').forEach(el => { el.classList.remove('show'); document.body.style.overflow = 'auto'; });
});
document.addEventListener('DOMContentLoaded', function() {
    var fi = document.getElementById('fileSurat');
    if (fi) fi.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            document.getElementById('fileName').textContent = this.files[0].name;
            document.getElementById('filePreview').classList.add('show');
        }
    });
});
</script>
@endsection
