{{-- Search & Filter Form --}}
<form method="GET" action="{{ $route_name }}" style="margin-bottom:20px">
    <input type="hidden" name="tahun" value="{{ $tahun_aktif }}">
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari dokumen atau nama anggota..." style="flex:1;min-width:200px;padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:inherit">
        <select name="bulan" style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:inherit">
            <option value="">Semua Bulan</option>
            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
            <option value="{{ $i+1 }}" {{ ($bulan ?? '') == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
            @endforeach
        </select>
        <input type="date" name="tanggal" value="{{ $tgl ?? '' }}" style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:inherit">
        <button type="submit" style="padding:9px 20px;background:#0f3b5e;color:#fff;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-family:inherit">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(($search ?? '') || ($bulan ?? '') || ($tgl ?? ''))
        <a href="{{ $route_name }}?tahun={{ $tahun_aktif }}" style="padding:9px 16px;background:#f1f5f9;color:#64748b;border-radius:8px;font-size:14px;text-decoration:none">Reset</a>
        @endif
    </div>
</form>

{{-- Section Upload Anggota --}}
@if(isset($uploads_anggota) && ($uploads_anggota->count() > 0 || ($search ?? '') || ($bulan ?? '') || ($tgl ?? '')))
<div style="margin-top:32px">
    <h3 style="font-size:17px;font-weight:700;color:#0f3b5e;margin-bottom:16px;display:flex;align-items:center;gap:8px">
        <i class="fas fa-users" style="color:#eab308"></i> Dokumen Upload Anggota
        <span style="font-size:12px;background:#dbeafe;color:#1d4ed8;padding:2px 10px;border-radius:20px;font-weight:600">
            {{ $uploads_anggota->count() }} dokumen
        </span>
    </h3>
    @forelse($uploads_anggota as $up)
    <div class="dokumen-card" style="margin-bottom:10px">
        <div class="card-icon" style="background:#d1fae5;color:#065f46">
            <i class="fas fa-file-upload"></i>
        </div>
        <div class="card-info">
            <div class="title">
                {{ $up->judul }}
                <span class="badge-type file" style="background:#d1fae5;color:#065f46">
                    {{ $up->folder->nama ?? '-' }}
                </span>
            </div>
            <div class="description">
                <i class="fas fa-user" style="font-size:11px"></i> {{ $up->user->nama_admin ?? '-' }}
                &nbsp;&middot;&nbsp;
                <i class="fas fa-building" style="font-size:11px"></i> {{ $up->user->divisi ?? '-' }}
                &nbsp;&middot;&nbsp;
                <i class="fas fa-calendar" style="font-size:11px"></i>
                {{ \Carbon\Carbon::parse($up->tanggal_upload)->format('d/m/Y') }}
            </div>
        </div>
        <div class="card-action">
            <a href="{{ Storage::url('uploads/anggota/' . $up->file_name) }}" target="_blank" class="btn-view">
                <i class="fas fa-download"></i> Unduh
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state" style="padding:30px">
        <i class="fas fa-search"></i>
        <p>Tidak ada dokumen anggota yang sesuai filter.</p>
    </div>
    @endforelse
</div>
@endif
