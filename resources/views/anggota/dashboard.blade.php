@extends('layouts.admin')
@section('title', 'Dashboard Anggota - CEKIDOT')

@section('content')
<div class="page-header">
    <h1>Dashboard Anggota</h1>
    <p>Selamat datang, <strong>{{ auth()->user()->nama_admin }}</strong> — Divisi {{ auth()->user()->divisi }}</p>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-header"><h3><i class="fas fa-upload"></i> Upload Dokumen</h3></div>
    <div class="card-body">
        <form action="{{ route('anggota.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Folder Dokumen <span class="required">*</span></label>
                    <select name="folder_id" required class="form-control">
                        <option value="">-- Pilih Folder --</option>
                        @foreach($folders as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->nama }} @if($folder->divisi !== 'Semua')({{ $folder->divisi }})@endif</option>
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
                    <input type="file" name="file_dokumen" class="form-control" required>
                    <small>Maks 50MB</small>
                </div>
                <div class="form-group full-width">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
        </form>
    </div>
</div>

<div class="card" style="margin-top:24px">
    <div class="card-header"><h3><i class="fas fa-history"></i> Riwayat Upload Saya</h3></div>
    <div class="card-body">
        @if($uploads->isEmpty())
        <p class="text-muted">Belum ada dokumen yang diupload.</p>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Folder</th>
                    <th>Judul</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($uploads as $up)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($up->tanggal_upload)->format('d/m/Y') }}</td>
                    <td>{{ $up->folder->nama ?? '-' }}</td>
                    <td>{{ $up->judul }}</td>
                    <td>
                        <a href="{{ Storage::url('uploads/anggota/' . $up->file_name) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('anggota.delete', $up->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dokumen ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
