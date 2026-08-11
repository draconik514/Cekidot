@extends('layouts.admin')
@section('title', 'Folder Dokumen - CEKIDOT')

@section('content')
<div class="page-header"><h1>Folder Dokumen</h1></div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

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
                    <label>Untuk Divisi</label>
                    <select name="divisi" class="form-control">
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
        </form>
    </div>
</div>

<div class="card" style="margin-top:24px">
    <div class="card-header"><h3><i class="fas fa-folder"></i> Daftar Folder</h3></div>
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr><th>Nama Folder</th><th>Divisi</th><th>Deskripsi</th><th>Jumlah Dokumen</th><th>Dibuat Oleh</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($folders as $folder)
                <tr>
                    <td><i class="fas fa-folder" style="color:#eab308"></i> {{ $folder->nama }}</td>
                    <td>{{ $folder->divisi }}</td>
                    <td>{{ $folder->deskripsi ?? '-' }}</td>
                    <td>{{ $folder->uploads()->count() }}</td>
                    <td>{{ $folder->pembuat->nama_admin ?? '-' }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editFolder({{ $folder->id }}, '{{ addslashes($folder->nama) }}', '{{ addslashes($folder->deskripsi) }}', '{{ $folder->divisi }}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="{{ route('admin.folder.destroy', $folder->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus folder ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modalEdit" class="modal" style="display:none">
    <div class="modal-box">
        <h3>Edit Folder</h3>
        <form id="formEdit" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Folder</label>
                <input type="text" name="nama" id="edit_nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Untuk Divisi</label>
                <select name="divisi" id="edit_divisi" class="form-control">
                    @foreach($divisi_list as $d)
                    <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" name="deskripsi" id="edit_deskripsi" class="form-control">
            </div>
            <div style="display:flex;gap:8px;margin-top:16px">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalEdit').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editFolder(id, nama, deskripsi, divisi) {
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('edit_divisi').value = divisi;
    document.getElementById('formEdit').action = '/admin/folder-dokumen/' + id;
    document.getElementById('modalEdit').style.display = 'flex';
}
</script>
@endsection
