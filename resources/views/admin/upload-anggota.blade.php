@extends('layouts.admin')
@section('title', 'Upload Anggota - CEKIDOT')

@section('content')
<div class="page-header"><h1>Dokumen Upload Anggota</h1></div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.upload.index') }}" class="filter-form">
            <div class="form-grid">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul / nama anggota..." value="{{ request('search') }}">
                </div>
                <div class="form-group">
                    <select name="folder_id" class="form-control">
                        <option value="">-- Semua Folder --</option>
                        @foreach($folders as $f)
                        <option value="{{ $f->id }}" {{ request('folder_id') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->isSuperAdmin())
                <div class="form-group">
                    <select name="divisi" class="form-control">
                        <option value="">-- Semua Divisi --</option>
                        @foreach($divisi_list as $d)
                        <option value="{{ $d }}" {{ request('divisi') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="form-group">
                    <select name="bulan" class="form-control">
                        <option value="">-- Semua Bulan --</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                        <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <select name="tahun" class="form-control">
                        <option value="">-- Semua Tahun --</option>
                        @foreach(range(date('Y'), 2025) as $y)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.upload.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card" style="margin-top:16px">
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr><th>Tanggal</th><th>Nama Anggota</th><th>Divisi</th><th>Folder</th><th>Judul</th><th>File</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($uploads as $up)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($up->tanggal_upload)->format('d/m/Y') }}</td>
                    <td>{{ $up->user->nama_admin ?? '-' }}</td>
                    <td>{{ $up->user->divisi ?? '-' }}</td>
                    <td>{{ $up->folder->nama ?? '-' }}</td>
                    <td>{{ $up->judul }}</td>
                    <td>
                        <a href="{{ Storage::url('uploads/anggota/' . $up->file_name) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.upload.destroy', $up->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dokumen ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">Belum ada dokumen.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top:16px">{{ $uploads->links() }}</div>
    </div>
</div>
@endsection
