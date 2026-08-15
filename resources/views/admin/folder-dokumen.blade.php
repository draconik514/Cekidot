@extends('layouts.admin')
@section('title', 'Folder Dokumen - CEKIDOT')

@section('content')
<div class="page-header"><h1><i class="fas fa-folder"></i> Folder Dokumen</h1></div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

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
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <small class="text-muted" style="margin-left:8px">Folder anak otomatis mewarisi divisi folder induknya.</small>
        </form>
    </div>
</div>

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
        </div>
        @endif
    </div>
</div>

<div class="modal" id="modalEdit">
    <div class="modal-box">
        <h3>Edit Folder</h3>
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
                <input type="text" name="deskripsi" id="edit_deskripsi" class="form-control">
            </div>
            <div style="display:flex;gap:8px;margin-top:16px">
                <button type="submit" class="btn btn-primary">Simpan</button>
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
}
</script>
@endsection