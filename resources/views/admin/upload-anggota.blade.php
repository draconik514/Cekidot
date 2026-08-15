@extends('layouts.admin')
@section('title', 'Upload Anggota - CEKIDOT')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-file-upload"></i> Dokumen Upload Anggota</h1>
        <span class="info">Pantau semua dokumen yang diupload oleh anggota</span>
    </div>
    <div style="font-size:13px; color:#64748b;"><i class="fas fa-user-circle" style="color:#eab308;"></i> {{ auth()->user()->nama_admin ?? 'Admin' }}</div>
</div>

@if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif

<div class="card">
    <div class="card-header"><h3><i class="fas fa-filter"></i> Filter Dokumen</h3></div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.upload.index') }}">
            <div class="form-grid">
                <div class="form-group">
                    <label>Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari judul / nama anggota..." value="{{ request('search') }}">
                </div>
                <div class="form-group">
                    <label>Folder</label>
                    <select name="folder_id" class="form-control">
                        <option value="">-- Semua Folder --</option>
                        @foreach($folders as $f)
                        <option value="{{ $f->id }}" {{ request('folder_id') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->isSuperAdmin())
                <div class="form-group">
                    <label>Divisi</label>
                    <select name="divisi" class="form-control">
                        <option value="">-- Semua Divisi --</option>
                        @foreach($divisi_list as $d)
                        <option value="{{ $d }}" {{ request('divisi') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="form-group">
                    <label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <option value="">-- Semua Bulan --</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                        <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tahun</label>
                    <select name="tahun" class="form-control">
                        <option value="">-- Semua Tahun --</option>
                        @foreach(range(date('Y'), 2025) as $y)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="display:flex; align-items:flex-end; gap:8px;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.upload.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Daftar Dokumen</h3>
        <span style="background:#f1f5f9; color:#64748b; font-size:11px; padding:2px 12px; border-radius:20px;">{{ $uploads->total() }} dokumen</span>
    </div>
    <div class="card-body" style="padding:0;">
        @if($uploads->isEmpty())
        <div class="empty-state"><i class="fas fa-file-alt"></i><p>Belum ada dokumen yang diupload.</p></div>
        @else
        <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
<<<<<<< HEAD
                <tr><th>Tanggal</th><th>Nama Anggota</th><th>Divisi</th><th>Folder</th><th>Nama File</th><th>Jenis</th><th>Ukuran</th><th>Tindakan</th></tr>
=======
                <tr><th>#</th><th>Tanggal</th><th>Nama Anggota</th><th>Divisi</th><th>Folder</th><th>Judul</th><th style="width:80px;">Aksi</th></tr>
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
            </thead>
            <tbody>
                @foreach($uploads as $i => $up)
                <tr>
<<<<<<< HEAD
                    <td>{{ \Carbon\Carbon::parse($up->tanggal_upload)->format('d/m/Y') }}</td>
                    <td>{{ $up->user->nama_admin ?? '-' }}</td>
                    <td>{{ $up->user->divisi ?? '-' }}</td>
                    <td>{{ $up->folder->nama ?? '-' }}</td>
                    <td>{{ $up->judul }}</td>
                    <td>{{ strtoupper($up->file_type) }}</td>
                    <td>{{ $up->ukuran }}</td>
                    <td>
                        @if($up->dapat_dipreview)
                        <a href="javascript:void(0)" class="btn btn-sm btn-warning" onclick="openPreview('{{ Storage::url('uploads/anggota/' . $up->file_name) }}')"><i class="fas fa-eye"></i></a>
                        @endif
                        <a href="{{ route('admin.upload.download', $up->id) }}" class="btn btn-sm btn-info"><i class="fas fa-download"></i></a>
                        <a href="{{ route('admin.upload.destroy', $up->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dokumen ini?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted">Belum ada dokumen.</td></tr>
                @endforelse
=======
                    <td style="color:#94a3b8; font-size:12px;">{{ $uploads->firstItem() + $i }}</td>
                    <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($up->tanggal_upload)->format('d/m/Y') }}</td>
                    <td style="font-weight:600; color:#0f3b5e;">{{ $up->user->nama_admin ?? '-' }}</td>
                    <td>
                        <span style="background:#f0fdf4; color:#166534; padding:2px 10px; border-radius:12px; font-size:11px; border:1px solid #bbf7d0;">{{ $up->user->divisi ?? '-' }}</span>
                    </td>
                    <td>
                        <span style="background:#f1f5f9; color:#475569; padding:2px 10px; border-radius:12px; font-size:11px;">{{ $up->folder->nama ?? '-' }}</span>
                    </td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $up->judul }}">{{ $up->judul }}</td>
                    <td>
                        <div style="display:flex; gap:4px;">
                        <a href="{{ Storage::url('uploads/anggota/' . $up->file_name) }}" target="_blank" class="btn btn-sm btn-info" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="{{ route('admin.upload.destroy', $up->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dokumen ini?')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </a>
                        </div>
                    </td>
                </tr>
                @endforeach
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
            </tbody>
        </table>
        </div>
        <div style="padding:12px 16px; border-top:1px solid #f1f5f9; background:#f8fafc; font-size:12px; color:#64748b;">
            {{ $uploads->links() }}
        </div>
        @endif
    </div>
</div>

<div class="modal" id="previewModal">
    <div class="modal-box modal-lg">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <h3 style="margin:0"><i class="fas fa-eye" style="color:#eab308"></i> Pratinjau Dokumen</h3>
            <button class="btn btn-sm btn-secondary" onclick="closePreview()">Tutup</button>
        </div>
        <div id="previewFrame"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openPreview(url) {
    var isImage = /\.(jpg|jpeg|png|gif|webp)(\?|$)/i.test(url);
    var frame = document.getElementById('previewFrame');
    frame.innerHTML = isImage
        ? '<img src="' + url + '" alt="Preview" style="width:100%;border-radius:8px">'
        : '<iframe src="' + url + '" style="width:100%;height:70vh;border:0;border-radius:8px"></iframe>';
    document.getElementById('previewModal').classList.add('show');
}
function closePreview() {
    document.getElementById('previewModal').classList.remove('show');
    document.getElementById('previewFrame').innerHTML = '';
}
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePreview(); });
</script>
@endsection
