@extends('layouts.admin')
@section('title', 'Manajemen User - CEKIDOT')

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
    .data-table { width:100%; border-collapse:collapse; font-size:14px; }
    .data-table th { text-align:left; padding:10px 14px; background:#f8fafc; font-weight:600; color:#1e293b; border-bottom:2px solid #e2e8f0; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; }
    .data-table td { padding:10px 14px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
    .data-table tr:hover td { background:#f8fafc; }
    .data-table tr:last-child td { border-bottom:none; }
    .badge-role { display:inline-flex; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:600; text-transform:capitalize; }
    .badge-role.super_admin { background:#dbeafe; color:#1d4ed8; }
    .badge-role.admin_divisi { background:#d1fae5; color:#065f46; }
    .badge-role.anggota { background:#f1f5f9; color:#475569; }
    .modal-overlay { display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:#fff; border-radius:20px; max-width:560px; width:100%; max-height:92vh; overflow-y:auto; padding:28px; box-shadow:0 30px 80px rgba(0,0,0,0.35); animation:modalIn 0.3s ease-out; }
    @keyframes modalIn { from { opacity:0; transform:scale(0.95) translateY(20px); } to { opacity:1; transform:scale(1) translateY(0); } }
    .modal-box h3 { font-size:18px; font-weight:700; color:#0f3b5e; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .modal-box h3 i { color:#eab308; }
    .modal-box .form-group { margin-bottom:14px; }
    .modal-box .form-group label { font-weight:600; font-size:13px; display:block; margin-bottom:4px; color:#1e293b; }
    .modal-box .form-control { width:100%; padding:8px 12px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; font-family:inherit; }
    .modal-box .form-control:focus { outline:none; border-color:#0f3b5e; }
    .empty-state { text-align:center; padding:40px 20px; color:#94a3b8; }
    .empty-state i { font-size:40px; opacity:0.3; display:block; margin-bottom:12px; }
    @media (max-width:768px) { .form-grid { grid-template-columns:1fr; } .page-header { flex-direction:column; align-items:flex-start; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-users-cog"></i> Manajemen User</h1>
        <span class="info">Kelola akun admin dan anggota</span>
    </div>
    <div style="font-size:14px; color:#64748b;"><i class="fas fa-user-circle" style="color:#eab308;"></i> {{ auth()->user()->nama_admin ?? 'Admin' }}</div>
</div>

@if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

<div class="card">
    <div class="card-header"><h3><i class="fas fa-user-plus"></i> Tambah Akun</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama_admin" class="form-control" required placeholder="Nama lengkap">
                </div>
                <div class="form-group">
                    <label>Username <span class="required">*</span></label>
                    <input type="text" name="username" class="form-control" required placeholder="Username login">
                </div>
                <div class="form-group">
                    <label>Password <span class="required">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6" placeholder="Min. 6 karakter">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email (opsional)">
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
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Akun</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users"></i> Daftar User</h3>
        <span style="background:#f1f5f9; color:#64748b; font-size:11px; padding:2px 12px; border-radius:20px;">{{ count($users) }} akun</span>
    </div>
    <div class="card-body" style="padding:0;">
        @if(count($users) == 0)
        <div class="empty-state"><i class="fas fa-users"></i><p>Belum ada user terdaftar</p></div>
        @else
        <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr><th>#</th><th>Nama</th><th>Username</th><th>Divisi</th><th>Role</th><th style="width:100px;">Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($users as $i => $u)
                <tr>
                    <td style="color:#94a3b8; font-size:12px;">{{ $i+1 }}</td>
                    <td style="font-weight:600; color:#0f3b5e;">{{ $u->nama_admin }}</td>
                    <td style="color:#64748b;">{{ $u->username }}</td>
                    <td>
                        <span style="background:#f0fdf4; color:#166534; padding:2px 10px; border-radius:12px; font-size:12px; border:1px solid #bbf7d0;">{{ $u->divisi ?? '-' }}</span>
                    </td>
                    <td><span class="badge-role {{ $u->role }}">{{ str_replace('_', ' ', $u->role) }}</span></td>
                    <td>
                        <div style="display:flex; gap:4px;">
                        <button class="btn btn-sm btn-warning" onclick="editUser({{ $u->id }}, '{{ addslashes($u->nama_admin) }}', '{{ $u->username }}', '{{ $u->email }}', '{{ $u->divisi }}', '{{ $u->role }}')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="{{ route('admin.users.destroy', $u->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user {{ $u->nama_admin }}?')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>
</div>

<div class="modal-overlay" id="modalEdit">
    <div class="modal-box">
        <h3><i class="fas fa-user-edit"></i> Edit User</h3>
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
                <label>Password Baru <span style="color:#94a3b8; font-size:11px; font-weight:400;">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="form-control" minlength="6" placeholder="Min. 6 karakter">
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
            <div style="display:flex; gap:8px; margin-top:20px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalEdit').classList.remove('show')">Batal</button>
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
    document.getElementById('modalEdit').classList.add('show');
    document.body.style.overflow = 'hidden';
}
document.getElementById('modalEdit').addEventListener('click', function(e) {
    if (e.target === this) { this.classList.remove('show'); document.body.style.overflow = 'auto'; }
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { document.getElementById('modalEdit').classList.remove('show'); document.body.style.overflow = 'auto'; }
});
</script>
@endsection
