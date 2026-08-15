@extends('layouts.admin')
@section('title', 'Log Aktivitas - CEKIDOT')

@section('content')
<div class="page-header"><h1><i class="fas fa-history"></i> Log Aktivitas</h1></div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.log.index') }}" class="filter-form">
            <div class="form-group" style="flex:1;min-width:220px">
                <input type="text" name="q" class="form-control" placeholder="Cari detail dokumen..." value="{{ request('q') }}">
            </div>
            <div class="form-group">
                <select name="aksi" class="form-control">
                    <option value="">-- Semua Aksi --</option>
                    <option value="upload" {{ request('aksi') == 'upload' ? 'selected' : '' }}>Upload</option>
                    <option value="unduh" {{ request('aksi') == 'unduh' ? 'selected' : '' }}>Unduh</option>
                    <option value="hapus" {{ request('aksi') == 'hapus' ? 'selected' : '' }}>Hapus</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('admin.log.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

<div class="card" style="margin-top:16px">
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr><th>Waktu</th><th>Pengguna</th><th>Peran</th><th>Aksi</th><th>Dokumen</th><th>Folder</th></tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->user->nama_admin ?? '-' }}</td>
                    <td>{{ $log->user->role ?? '-' }}</td>
                    <td>
                        @php $badge = ['upload' => '#d1fae5', 'unduh' => '#dbeafe', 'hapus' => '#fee2e2']; $color = $badge[$log->aksi] ?? '#f1f5f9'; @endphp
                        <span style="background:{{ $color }};color:#334155;padding:2px 12px;border-radius:20px;font-weight:600;font-size:12px">{{ ucfirst($log->aksi) }}</span>
                    </td>
                    <td>{{ $log->detail ?? $log->upload->judul ?? '-' }}</td>
                    <td>{{ $log->upload->folder->nama ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top:16px">{{ $logs->links() }}</div>
    </div>
</div>
@endsection