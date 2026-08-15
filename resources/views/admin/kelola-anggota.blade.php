@extends('layouts.admin')
@section('title', 'Kelola Anggota - CEKIDOT')

@section('content')
<div class="page-header">
    <h1>Kelola Anggota</h1>
    <span class="info">Bidang: <strong>{{ auth()->user()->bidang?->nama_bidang ?? '-' }}</strong></span>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

<div class="card">
    <div class="card-header"><h3><i class="fas fa-user-plus"></i> Tambah Anggota</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.anggota.store') }}" method="POST">
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
                    <label>Divisi</label>
                    <select name="divisi" class="form-control">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach(['Kepegawaian', 'Program', 'Keuangan', 'Ekraf', 'Destinasi', 'Pemasaran', 'Sdm'] as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>

<div class="card" style="margin-top:24px">
    <div class="card-header"><h3><i class="fas fa-users"></i> Daftar Anggota</h3></div>
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr><th>Nama</th><th>Username</th><th>Divisi</th><th>Bidang</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($anggota as $a)
                <tr>
                    <td>{{ $a->nama_admin }}</td>
                    <td>{{ $a->username }}</td>
                    <td>{{ $a->divisi ?? '-' }}</td>
                    <td>{{ $a->bidang->nama_bidang ?? '-' }}</td>
                    <td>
                        @if($a->is_active)
                        <span class="badge" style="background:#16a34a">Aktif</span>
                        @else
                        <span class="badge" style="background:#dc2626">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.anggota.toggle', $a->id) }}" class="btn btn-sm {{ $a->is_active ? 'btn-warning' : 'btn-success' }}">
                            <i class="fas {{ $a->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                        </a>
                        <a href="{{ route('admin.anggota.destroy', $a->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus anggota ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty-state">Belum ada anggota</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection