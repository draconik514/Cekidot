@extends('layouts.admin')
@section('title', 'Folder Dokumen - CEKIDOT')

<<<<<<< HEAD
@section('content')
<div class="page-header"><h1><i class="fas fa-folder"></i> Folder Dokumen</h1></div>
=======
@section('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .page-header h1 { font-size:24px; color:#0f3b5e; display:flex; align-items:center; gap:10px; }
    .page-header h1 i { color:#eab308; }
    .page-header .info { color:#64748b; font-size:14px; }
    .alert { padding:10px 16px; border-radius:8px; margin-bottom:16px; display:flex; align-items:center; gap:10px; font-size:14px; }
    .alert-success { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
    .alert-error, .alert-danger { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
    .card { background:#fff; border-radius:14px; border:1px solid #e8ecf1; box-shadow:0 1px 3px rgba(0,0,0,0.02); overflow:hidden; margin-bottom:24px; }
    .card-header { display:flex; justify-content:space-between; align-items:center; padding:14px 20px; border-bottom:1px solid #f1f5f9; background:#f8fafc; }
    .card-header h3 { font-size:15px; font-weight:700; color:#0f3b5e; display:flex; align-items:center; gap:8px; }
    .card-header h3 i { color:#eab308; }
    .card-body { padding:20px; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
    .form-grid .full-width { grid-column:1/-1; }
    .form-group { margin-bottom:0; }
    .form-group label { font-weight:600; font-size:13px; display:block; margin-bottom:4px; color:#1e293b; }
    .form-group label .required { color:#ef4444; }
    .form-control { width:100%; padding:8px 12px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; font-family:inherit; background:#fff; transition:border-color 0.3s; }
    .form-control:focus { outline:none; border-color:#0f3b5e; }
    .btn { display:inline-flex; align-items:center; gap:6px; padding:8px 20px; border-radius:8px; font-weight:600; font-size:13px; border:none; cursor:pointer; transition:all 0.3s; text-decoration:none; }
    .btn-primary { background:#0f3b5e; color:#fff; }
    .btn-primary:hover { background:#0a2a44; transform:translateY(-1px); box-shadow:0 4px 12px rgba(15,59,94,0.25); }
    .btn-secondary { background:#f1f5f9; color:#1e293b; }
    .btn-secondary:hover { background:#e2e8f0; }
    .btn-sm { padding:5px 12px; font-size:12px; }
    .btn-warning { background:#fef3c7; color:#b45309; }
    .btn-warning:hover { background:#fde68a; }
    .btn-danger { background:#fef2f2; color:#991b1b; }
    .btn-danger:hover { background:#fecaca; }
    .data-table { width:100%; border-collapse:collapse; font-size:14px; min-width:700px; }
    .data-table th { text-align:left; padding:10px 14px; background:#f8fafc; font-weight:600; color:#1e293b; border-bottom:2px solid #e2e8f0; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; }
    .data-table td { padding:10px 14px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
    .data-table tr:hover td { background:#f8fafc; }
    .data-table tr:last-child td { border-bottom:none; }
    .folder-icon { color:#eab308; margin-right:6px; }
    .count-badge { display:inline-flex; align-items:center; gap:4px; background:#f1f5f9; color:#475569; padding:2px 10px; border-radius:12px; font-size:12px; font-weight:600; }
    .modal-overlay { display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:#fff; border-radius:20px; max-width:500px; width:100%; max-height:92vh; overflow-y:auto; padding:28px; box-shadow:0 30px 80px rgba(0,0,0,0.35); animation:modalIn 0.3s ease-out; }
    @keyframes modalIn { from { opacity:0; transform:scale(0.95) translateY(20px); } to { opacity:1; transform:scale(1) translateY(0); } }
    .modal-box h3 { font-size:18px; font-weight:700; color:#0f3b5e; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .modal-box h3 i { color:#eab308; }
    .modal-box .form-group { margin-bottom:14px; }
    .modal-box .form-group label { font-weight:600; font-size:13px; display:block; margin-bottom:4px; color:#1e293b; }
    .modal-box .form-control { width:100%; padding:8px 12px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; font-family:inherit; }
    .modal-box .form-control:focus { outline:none; border-color:#0f3b5e; }
    .empty-state { text-align:center; padding:40px 20px; color:#94a3b8; }
    .empty-state i { font-size:40px; opacity:0.3; display:block; margin-bottom:12px; }
    @media (max-width:768px) { .form-grid { grid-template-columns:1fr; } .form-grid .full-width { grid-column:1; } .page-header { flex-direction:column; align-items:flex-start; } }
</style>
@endsection
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-folder-open"></i> Folder Dokumen</h1>
        <span class="info">Kelola folder untuk upload dokumen anggota</span>
    </div>
    <div style="font-size:14px; color:#64748b;"><i class="fas fa-user-circle" style="color:#eab308;"></i> {{ auth()->user()->nama_admin ?? 'Admin' }}</div>
</div>

@if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

@php
    $parents = $folders->where('parent_id', null);
@endphp

<div class="card">
    <div class="card-header"><h3><i class="fas fa-folder-plus"></i> Tambah Folder</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.folder.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Folder <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-control" required placeholder="Contoh: Laporan Bulanan IKI">
                </div>
                <div class="form-group">
                    <label>Folder Induk (Parent)</label>
                    <select name="parent_id" class="form-control" id="parentSelect" onchange="toggleDivisi()">
                        <option value="">-- Tanpa Induk (Folder Bidang) --</option>
                        @foreach($parents as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Untuk Divisi</label>
                    <select name="divisi" class="form-control" id="divisiSelect">
                        @foreach($divisi_list as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi folder (opsional)">
                </div>
            </div>
<<<<<<< HEAD
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <small class="text-muted" style="margin-left:8px">Folder anak otomatis mewarisi divisi folder induknya.</small>
=======
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Folder</button>
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
        </form>
    </div>
</div>

<<<<<<< HEAD
<div class="card" style="margin-top:24px">
    <div class="card-header"><h3><i class="fas fa-sitemap"></i> Struktur Folder</h3></div>
    <div class="card-body">
        @if($parents->isEmpty())
        <p class="text-muted">Belum ada folder.</p>
        @else
        <div class="folder-tree">
            @foreach($parents as $parent)
            <details>
                <summary>
                    <i class="fas fa-folder icon"></i>
                    {{ $parent->nama }}
                    <span class="count">{{ $parent->children->count() }} sub-folder • {{ $parent->uploads()->count() }} dokumen</span>
                </summary>
                <div class="tree-child">
                    <table class="data-table" style="margin-bottom:10px">
                        <thead>
                            <tr><th>Nama</th><th>Divisi</th><th>Deskripsi</th><th>Jml Dokumen</th><th>Dibuat Oleh</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-folder" style="color:#eab308"></i> {{ $parent->nama }}</td>
                                <td>{{ $parent->divisi }}</td>
                                <td>{{ $parent->deskripsi ?? '-' }}</td>
                                <td>{{ $parent->uploads()->count() }}</td>
                                <td>{{ $parent->pembuat->nama_admin ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editFolder({{ $parent->id }}, '{{ addslashes($parent->nama) }}', '{{ addslashes($parent->deskripsi) }}', null)"><i class="fas fa-edit"></i></button>
                                    <a href="{{ route('admin.folder.destroy', $parent->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus folder ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @foreach($parent->children as $child)
                    <div class="child-label"><i class="fas fa-folder"></i> {{ $child->nama }}
                        <span class="text-muted" style="margin-left:auto;font-weight:400">{{ $child->uploads()->count() }} dokumen</span>
                    </div>
                    <table class="data-table" style="margin-bottom:10px">
                        <tbody>
                            <tr>
                                <td>{{ $child->deskripsi ?? '-' }}</td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-warning" onclick="editFolder({{ $child->id }}, '{{ addslashes($child->nama) }}', '{{ addslashes($child->deskripsi) }}', {{ $parent->id }})"><i class="fas fa-edit"></i></button>
                                    <a href="{{ route('admin.folder.destroy', $child->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus sub-folder ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endforeach
                </div>
            </details>
            @endforeach
=======
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-folder"></i> Daftar Folder</h3>
        <span style="background:#f1f5f9; color:#64748b; font-size:11px; padding:2px 12px; border-radius:20px;">{{ count($folders) }} folder</span>
    </div>
    <div class="card-body" style="padding:0;">
        @if(count($folders) == 0)
        <div class="empty-state"><i class="fas fa-folder-open"></i><p>Belum ada folder. Buat folder pertama di atas.</p></div>
        @else
        <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr><th>#</th><th>Nama Folder</th><th>Divisi</th><th>Deskripsi</th><th>Dokumen</th><th>Dibuat Oleh</th><th style="width:90px;">Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($folders as $i => $folder)
                <tr>
                    <td style="color:#94a3b8; font-size:12px;">{{ $i+1 }}</td>
                    <td style="font-weight:600;"><i class="fas fa-folder folder-icon"></i>{{ $folder->nama }}</td>
                    <td>
                        <span style="background:#f0fdf4; color:#166534; padding:2px 10px; border-radius:12px; font-size:12px; border:1px solid #bbf7d0;">{{ $folder->divisi ?? '-' }}</span>
                    </td>
                    <td style="color:#64748b; font-size:13px;">{{ $folder->deskripsi ?? '-' }}</td>
                    <td><span class="count-badge"><i class="fas fa-file"></i> {{ $folder->uploads()->count() }}</span></td>
                    <td style="font-size:13px; color:#64748b;">{{ $folder->pembuat->nama_admin ?? '-' }}</td>
                    <td>
                        <div style="display:flex; gap:4px;">
                        <button class="btn btn-sm btn-warning" onclick="editFolder({{ $folder->id }}, '{{ addslashes($folder->nama) }}', '{{ addslashes($folder->deskripsi) }}', '{{ $folder->divisi }}')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="{{ route('admin.folder.destroy', $folder->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus folder {{ addslashes($folder->nama) }}?')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
        </div>
        @endif
    </div>
</div>

<<<<<<< HEAD
<div class="modal" id="modalEdit">
=======
<div class="modal-overlay" id="modalEdit">
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
    <div class="modal-box">
        <h3><i class="fas fa-folder-open"></i> Edit Folder</h3>
        <form id="formEdit" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Folder</label>
                <input type="text" name="nama" id="edit_nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Folder Induk</label>
                <select name="parent_id" id="edit_parent" class="form-control">
                    <option value="">-- Tanpa Induk (Folder Bidang) --</option>
                    @foreach($parents as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" name="deskripsi" id="edit_deskripsi" class="form-control" placeholder="Deskripsi (opsional)">
            </div>
<<<<<<< HEAD
            <div style="display:flex;gap:8px;margin-top:16px">
                <button type="submit" class="btn btn-primary">Simpan</button>
=======
            <div style="display:flex; gap:8px; margin-top:20px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalEdit').classList.remove('show')">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleDivisi() {
    var parentSelected = document.getElementById('parentSelect').value !== '';
    document.getElementById('divisiSelect').disabled = parentSelected;
}
function editFolder(id, nama, deskripsi, parentId) {
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('edit_parent').value = parentId === null ? '' : parentId;
    document.getElementById('formEdit').action = '/admin/folder-dokumen/' + id;
    document.getElementById('modalEdit').classList.add('show');
<<<<<<< HEAD
=======
    document.body.style.overflow = 'hidden';
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
}
document.getElementById('modalEdit').addEventListener('click', function(e) {
    if (e.target === this) { this.classList.remove('show'); document.body.style.overflow = 'auto'; }
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { document.getElementById('modalEdit').classList.remove('show'); document.body.style.overflow = 'auto'; }
});
</script>
@endsection