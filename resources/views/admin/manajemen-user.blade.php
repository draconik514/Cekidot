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
                    <select name="role" class="form-control" id="addRoleSelect" onchange="toggleRoleFields()">
                        <option value="anggota">Anggota</option>
                        <option value="admin_divisi">Admin Divisi</option>
                        <option value="admin_bidang">Admin Bidang</option>
                    </select>
                </div>
                <div class="form-group" id="addBidangGroup" style="display:none">
                    <label>Bidang <span class="required">*</span></label>
                    <select name="bidang_id" class="form-control">
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidang_list as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                        @endforeach
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
                <tr><th>Nama</th><th>Username</th><th>Divisi/Bidang</th><th>Role</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->nama_admin }}</td>
                    <td>{{ $u->username }}</td>
                    <td>
                        @if($u->role === 'admin_bidang')
                        {{ $u->bidang?->nama_bidang ?? '-' }}
                        @else
                        {{ $u->divisi ?? '-' }}
                        @endif
                    </td>
                    <td><span class="badge-role {{ $u->role }}">{{ str_replace('_', ' ', $u->role) }}</span></td>
                    <td>
                        @if($u->is_active)
                        <span class="badge-role aktif">Aktif</span>
                        @else
                        <span class="badge-role nonaktif">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editUser({{ $u->id }}, '{{ $u->nama_admin }}', '{{ $u->username }}', '{{ $u->email }}', '{{ $u->divisi }}', '{{ $u->role }}', {{ $u->bidang_id ?? 'null' }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.users.toggle', $u->id) }}" class="btn btn-sm {{ $u->is_active ? 'btn-secondary' : 'btn-success' }}" onclick="return confirm('{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                            <i class="fas {{ $u->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                        </a>
                        @endif
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
                <select name="role" id="edit_role" class="form-control" onchange="toggleEditRoleFields()">
                    <option value="anggota">Anggota</option>
                    <option value="admin_divisi">Admin Divisi</option>
                    <option value="admin_bidang">Admin Bidang</option>
                </select>
            </div>
            <div class="form-group" id="editBidangGroup" style="display:none">
                <label>Bidang <span class="required">*</span></label>
                <select name="bidang_id" id="edit_bidang_id" class="form-control">
                    <option value="">-- Pilih Bidang --</option>
                    @foreach($bidang_list as $b)
                    <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                    @endforeach
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
function editUser(id, nama, username, email, divisi, role, bidang_id) {
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_divisi').value = divisi;
    var roleEl = document.getElementById('edit_role');
    if (roleEl) {
        roleEl.value = role || 'anggota';
        var bidIdEl = document.getElementById('edit_bidang_id');
        if (bidIdEl && bidang_id) bidIdEl.value = bidang_id;
        toggleEditRoleFields();
    }
    document.getElementById('formEdit').action = '/admin/users/' + id;
    document.getElementById('modalEdit').style.display = 'flex';
}

function toggleRoleFields() {
    var role = document.getElementById('addRoleSelect').value;
    var bidangGroup = document.getElementById('addBidangGroup');
    if (bidangGroup) bidangGroup.style.display = role === 'admin_bidang' ? 'block' : 'none';
}

function toggleEditRoleFields() {
    var role = document.getElementById('edit_role').value;
    var bidangGroup = document.getElementById('editBidangGroup');
    if (bidangGroup) bidangGroup.style.display = role === 'admin_bidang' ? 'block' : 'none';
}
</script>
@endsection
