@extends('layouts.admin')
@section('title', 'Manajemen User - CEKIDOT')

@section('content')
<div class="page-header">
    <h1>Manajemen User</h1>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

<div class="card">
    <div class="card-header"><h3><i class="fas fa-user-plus"></i> Tambah Akun</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama_admin" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Username <span class="required">*</span></label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password <span class="required">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Divisi <span class="required">*</span></label>
                    <select name="divisi" class="form-control" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisi_list as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->isSuperAdmin())
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="anggota">Anggota</option>
                        <option value="admin_divisi">Admin Divisi</option>
                    </select>
                </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>

<div class="card" style="margin-top:24px">
    <div class="card-header"><h3><i class="fas fa-users"></i> Daftar User</h3></div>
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr><th>Nama</th><th>Username</th><th>Divisi</th><th>Role</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->nama_admin }}</td>
                    <td>{{ $u->username }}</td>
                    <td>{{ $u->divisi ?? '-' }}</td>
                    <td><span class="badge-role {{ $u->role }}">{{ str_replace('_', ' ', $u->role) }}</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editUser({{ $u->id }}, '{{ $u->nama_admin }}', '{{ $u->username }}', '{{ $u->email }}', '{{ $u->divisi }}', '{{ $u->role }}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="{{ route('admin.users.destroy', $u->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="modal" style="display:none">
    <div class="modal-box">
        <h3>Edit User</h3>
        <form id="formEdit" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_admin" id="edit_nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="edit_username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password Baru (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control" minlength="6">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="edit_email" class="form-control">
            </div>
            <div class="form-group">
                <label>Divisi</label>
                <select name="divisi" id="edit_divisi" class="form-control">
                    @foreach($divisi_list as $d)
                    <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->isSuperAdmin())
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="edit_role" class="form-control">
                    <option value="anggota">Anggota</option>
                    <option value="admin_divisi">Admin Divisi</option>
                </select>
            </div>
            @endif
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
function editUser(id, nama, username, email, divisi, role) {
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_divisi').value = divisi;
    var roleEl = document.getElementById('edit_role');
    if (roleEl) roleEl.value = role || 'anggota';
    document.getElementById('formEdit').action = '/admin/users/' + id;
    document.getElementById('modalEdit').style.display = 'flex';
}
</script>
@endsection
