@extends('layouts.admin')

@section('title', 'Capaian Program - CEKIDOT')

@section('styles')
<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .header h1 {
        font-size: 24px;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .header h1 i { color: #eab308; }
    .header .info {
        color: #64748b;
        font-size: 14px;
    }
    .header .admin-welcome {
        font-size: 14px;
        color: #64748b;
    }
    .header .admin-welcome i { color: #eab308; margin-right: 4px; }

    .alert {
        padding: 10px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .alert-success i { color: #16a34a; }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .alert-danger i { color: #dc2626; }

    .stats-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .stats-grid {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .stats-grid .stat-card {
        background: #ffffff;
        padding: 14px 24px;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        text-align: center;
        min-width: 120px;
        transition: all 0.3s;
    }
    .stats-grid .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }
    .stats-grid .stat-card .stat-number {
        font-size: 24px;
        font-weight: 800;
        color: #0f3b5e;
        display: block;
    }
    .stats-grid .stat-card .stat-label {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 500;
    }

    .tahun-nav {
        display: flex;
        align-items: center;
        gap: 4px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        padding: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        flex-shrink: 0;
    }
    .tahun-nav .btn-tahun {
        padding: 6px 14px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        background: transparent;
        color: #64748b;
        text-decoration: none;
    }
    .tahun-nav .btn-tahun:hover {
        background: rgba(15, 59, 94, 0.05);
        color: #0f3b5e;
    }
    .tahun-nav .btn-tahun.active {
        background: #0f3b5e;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(15,59,94,0.2);
    }

    .table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 16px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        min-width: 1600px;
    }
    table th {
        text-align: center;
        padding: 10px 8px;
        background: #f8fafc;
        font-weight: 700;
        color: #0f3b5e;
        border-bottom: 2px solid #e2e8f0;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    table th:first-child { text-align: left; min-width: 180px; }
    table th:nth-child(2) { min-width: 150px; }
    table th:nth-child(3) { min-width: 170px; }
    table td {
        padding: 6px 8px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    table tr:hover td { background: #f8fafc; }
    table tr:last-child td { border-bottom: none; }

    table .input-text {
        width: 100%;
        padding: 4px 6px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 11px;
        font-family: inherit;
        background: #ffffff;
        transition: border-color 0.3s;
        min-width: 100px;
    }
    table .input-text:focus {
        outline: none;
        border-color: #0f3b5e;
        box-shadow: 0 0 0 3px rgba(15,59,94,0.06);
    }

    table .num-input {
        width: 100%;
        padding: 4px 6px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 12px;
        font-family: inherit;
        background: #ffffff;
        transition: border-color 0.3s;
        text-align: right;
        max-width: 120px;
    }
    table .num-input:focus {
        outline: none;
        border-color: #0f3b5e;
        box-shadow: 0 0 0 3px rgba(15,59,94,0.06);
    }
    table .num-input.target-input { background: #f0f7ff; }
    table .num-input.realisasi-input { background: #f0fdf4; }
    table .num-input.sumber-input {
        background: #fffbeb;
        max-width: 200px;
        text-align: left;
        font-size: 11px;
    }

    table .frekwensi-select {
        padding: 4px 6px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 11px;
        font-family: inherit;
        background: #ffffff;
        transition: border-color 0.3s;
        width: 100%;
        max-width: 120px;
        text-align: center;
    }
    table .frekwensi-select:focus {
        outline: none;
        border-color: #0f3b5e;
        box-shadow: 0 0 0 3px rgba(15,59,94,0.06);
    }

    table .capaian-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 13px;
        min-width: 50px;
        text-align: center;
    }
    table .capaian-badge.high { background: #d1fae5; color: #16a34a; }
    table .capaian-badge.medium { background: #fef3c7; color: #eab308; }
    table .capaian-badge.low { background: #fef2f2; color: #dc2626; }
    table .capaian-badge.zero { background: #f1f5f9; color: #94a3b8; }

    .predikat-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 11px;
        text-align: center;
        min-width: 80px;
        letter-spacing: 0.3px;
    }
    .predikat-istimewa { background: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
    .predikat-baik { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .predikat-butuh { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .predikat-kurang { background: #ffedd5; color: #9a3412; border: 1px solid #fdba74; }
    .predikat-sangat { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
    .predikat-belum { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

    .file-upload-wrapper {
        position: relative;
        display: inline-block;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
    }
    .file-upload-wrapper input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
        top: 0;
        left: 0;
    }
    .file-upload-wrapper .file-label {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
        border: 1px solid #e2e8f0;
    }
    .file-upload-wrapper .file-label:hover {
        background: #dbeafe;
        color: #0f3b5e;
        border-color: #0f3b5e;
    }

    .file-info-cell {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .file-info-cell .file-name {
        font-size: 10px;
        color: #0f3b5e;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .file-info-cell .file-name i { margin-right: 3px; }
    .file-info-cell .file-preview-text {
        font-size: 10px;
        color: #16a34a;
        font-weight: 500;
        background: #d1fae5;
        padding: 1px 8px;
        border-radius: 10px;
        display: none;
    }
    .file-info-cell .file-preview-text.show { display: inline-block; }

    .file-info-cell .btn-delete-file {
        width: 24px;
        height: 24px;
        border: none;
        border-radius: 50%;
        background: #fef2f2;
        color: #991b1b;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        text-decoration: none;
    }
    .file-info-cell .btn-delete-file:hover { background: #fecaca; }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 0;
        border-top: 1px solid #e8ecf1;
        margin-top: 4px;
    }
    .btn-save {
        padding: 10px 36px;
        background: #0f3b5e;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    .btn-save:hover {
        background: #0a2a44;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(15,59,94,0.3);
    }

    .btn-reset {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 50%;
        background: #fef2f2;
        color: #991b1b;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        text-decoration: none;
    }
    .btn-reset:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    .form-note {
        font-size: 12px;
        color: #94a3b8;
        text-align: right;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #f1f5f9;
    }
    .form-note i {
        color: #eab308;
        margin-right: 4px;
    }

    .confirm-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .confirm-overlay.show { display: flex; }
    .confirm-box {
        background: #ffffff;
        border-radius: 20px;
        padding: 32px 36px;
        max-width: 440px;
        width: 100%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .confirm-box .confirm-icon { font-size: 48px; margin-bottom: 12px; }
    .confirm-box .confirm-icon .fa-save { color: #0f3b5e; }
    .confirm-box .confirm-icon .fa-trash { color: #dc2626; }
    .confirm-box .confirm-title {
        font-size: 20px;
        font-weight: 700;
        color: #0f3b5e;
        margin-bottom: 8px;
    }
    .confirm-box .confirm-text {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    .confirm-box .confirm-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    .confirm-box .confirm-actions .confirm-btn {
        padding: 10px 28px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .confirm-box .confirm-actions .confirm-btn-cancel {
        background: #f1f5f9;
        color: #475569;
    }
    .confirm-box .confirm-actions .confirm-btn-cancel:hover {
        background: #e2e8f0;
    }
    .confirm-box .confirm-actions .confirm-btn-confirm {
        background: #0f3b5e;
        color: #fff;
    }
    .confirm-box .confirm-actions .confirm-btn-confirm.danger {
        background: #dc2626;
    }
    .confirm-box .confirm-actions .confirm-btn-confirm.danger:hover {
        background: #b91c1c;
    }

    @media (max-width: 992px) {
        .stats-wrapper {
            flex-direction: column;
            align-items: stretch;
        }
        .stats-grid { justify-content: center; }
        .tahun-nav { justify-content: center; }
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }
        .btn-save { width: 100%; justify-content: center; }
    }
    @media (max-width: 768px) {
        .header { flex-direction: column; align-items: flex-start; }
        .stats-grid .stat-card { padding: 10px 16px; min-width: 80px; }
        .stats-grid .stat-card .stat-number { font-size: 18px; }
        table { font-size: 10px; min-width: 900px; }
        table th, table td { padding: 4px 6px; }
        table .num-input { font-size: 10px; max-width: 80px; padding: 2px 4px; }
        table .num-input.sumber-input { max-width: 120px; }
        table .frekwensi-select { font-size: 10px; max-width: 90px; }
        table .input-text { font-size: 10px; min-width: 70px; }
        .file-info-cell .file-name { max-width: 60px; }
        .predikat-badge { font-size: 9px; min-width: 60px; padding: 2px 8px; }
        .confirm-box { padding: 24px 20px; max-width: 360px; }
        .confirm-box .confirm-icon { font-size: 44px; }
        .confirm-box .confirm-title { font-size: 18px; }
    }
    @media (max-width: 480px) {
        .header h1 { font-size: 20px; }
        .stats-grid .stat-card { padding: 8px 12px; min-width: 60px; }
        .stats-grid .stat-card .stat-number { font-size: 16px; }
        .stats-grid .stat-card .stat-label { font-size: 9px; }
        .tahun-nav .btn-tahun { padding: 4px 10px; font-size: 11px; }
        table { font-size: 9px; min-width: 700px; }
        table th, table td { padding: 3px 4px; }
        table .num-input { font-size: 9px; max-width: 60px; padding: 1px 3px; }
        table .num-input.sumber-input { max-width: 80px; }
        table .frekwensi-select { font-size: 9px; max-width: 70px; padding: 1px 3px; }
        table .input-text { font-size: 9px; min-width: 50px; padding: 2px 3px; }
        .file-info-cell .file-name { max-width: 40px; }
        .predikat-badge { font-size: 8px; min-width: 50px; padding: 1px 6px; }
        .confirm-box { padding: 20px 16px; max-width: 320px; }
        .confirm-box .confirm-icon { font-size: 38px; }
        .confirm-box .confirm-title { font-size: 16px; }
        .confirm-box .confirm-text { font-size: 13px; }
        .confirm-box .confirm-actions .confirm-btn { padding: 8px 20px; font-size: 12px; }
    }
</style>
@endsection

@section('content')
@php
    $admin_nama = auth()->user()->nama_admin ?? 'Admin';
@endphp

<div class="header">
    <div>
        <h1><i class="fas fa-flag-checkered"></i> Capaian Program</h1>
        <span class="info">Tahun {{ $tahun_aktif }}</span>
    </div>
    <div class="admin-welcome">
        <i class="fas fa-user-circle"></i> {{ $admin_nama }}
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
</div>
@endif

<div class="stats-wrapper">
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number">{{ $total_data }}</span>
            <span class="stat-label">Total Indikator</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ number_format($rata_capaian, 2, ',', '.') }}%</span>
            <span class="stat-label">Rata-rata Capaian</span>
        </div>
    </div>
    
    <div class="tahun-nav">
        @foreach($tahun_list as $t)
        <a href="{{ route('admin.capaian.index', ['tahun' => $t]) }}" class="btn-tahun {{ $tahun_aktif == $t ? 'active' : '' }}">
            {{ $t }}
        </a>
        @endforeach
    </div>
</div>

<form method="post" enctype="multipart/form-data" id="formCapaian" action="{{ route('admin.capaian.update', ['tahun' => $tahun_aktif]) }}">
    @csrf
    <input type="hidden" name="tahun" value="{{ $tahun_aktif }}">

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="min-width:180px;">Program</th>
                    <th style="min-width:150px;">Sasaran</th>
                    <th style="min-width:170px;">Indikator</th>
                    <th style="width:100px;">Target</th>
                    <th style="width:100px;">Realisasi</th>
                    <th style="width:80px;">Capaian</th>
                    <th style="width:110px;">Predikat</th>
                    <th style="width:110px;">Frekwensi</th>
                    <th style="min-width:180px;">Sumber Data</th>
                    <th style="min-width:150px;">Penanggung Jawab</th>
                </tr>
            </thead>
            <tbody>
                @foreach($capaian_data as $d)
                @php
                    $capaian = (float) $d->capaian;
                    $class = 'zero';
                    if ($capaian >= 80) $class = 'high';
                    elseif ($capaian >= 50) $class = 'medium';
                    elseif ($capaian > 0) $class = 'low';
                    
                    $predikat = App\Http\Controllers\Admin\CapaianController::getPredikatStatic($capaian);
                    
                    $target_formatted = number_format($d->target, 6, ',', '.');
                    $target_formatted = rtrim(rtrim($target_formatted, '0'), ',');
                    $realisasi_formatted = number_format($d->realisasi, 6, ',', '.');
                    $realisasi_formatted = rtrim(rtrim($realisasi_formatted, '0'), ',');
                @endphp
                <tr>
                    <td>
                        <input type="text" name="data[{{ $d->id }}][program]" 
                               value="{{ htmlspecialchars($d->program) }}" 
                               class="input-text" placeholder="Nama Program">
                    </td>
                    <td>
                        <input type="text" name="data[{{ $d->id }}][sasaran]" 
                               value="{{ htmlspecialchars($d->sasaran) }}" 
                               class="input-text" placeholder="Sasaran">
                    </td>
                    <td>
                        <input type="text" name="data[{{ $d->id }}][indikator]" 
                               value="{{ htmlspecialchars($d->indikator) }}" 
                               class="input-text" placeholder="Indikator">
                    </td>
                    <td class="text-center">
                        <input type="text" name="data[{{ $d->id }}][target]" 
                               value="{{ $target_formatted }}" 
                               class="num-input target-input" 
                               data-id="{{ $d->id }}"
                               placeholder="0">
                    </td>
                    <td class="text-center">
                        <input type="text" name="data[{{ $d->id }}][realisasi]" 
                               value="{{ $realisasi_formatted }}" 
                               class="num-input realisasi-input" 
                               data-id="{{ $d->id }}"
                               placeholder="0">
                    </td>
                    <td class="text-center">
                        <span class="capaian-badge {{ $class }}" id="capaian-{{ $d->id }}">
                            {{ number_format($capaian, 2, ',', '.') }}%
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="predikat-badge {{ $predikat['class'] }}" id="predikat-{{ $d->id }}">
                            {{ $predikat['label'] }}
                        </span>
                    </td>
                    <td class="text-center">
                        <select name="data[{{ $d->id }}][frekwensi]" class="frekwensi-select">
                            <option value="Tahunan" {{ $d->frekwensi == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                            <option value="Bulanan" {{ $d->frekwensi == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="Bulanan / Tahunan" {{ $d->frekwensi == 'Bulanan / Tahunan' ? 'selected' : '' }}>Bulanan / Tahunan</option>
                        </select>
                    </td>
                    <td>
                        <div class="file-info-cell">
                            <input type="text" name="data[{{ $d->id }}][sumber_data]" 
                                   value="{{ htmlspecialchars($d->sumber_data ?? '') }}" 
                                   class="num-input sumber-input" 
                                   placeholder="Contoh: https://bps.go.id" 
                                   style="flex:1; min-width:120px; max-width:200px;">
                            
                            <div class="file-upload-wrapper" id="uploadWrapper-{{ $d->id }}">
                                <input type="file" name="file_sumber[{{ $d->id }}]" 
                                       id="fileInput-{{ $d->id }}"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                       data-id="{{ $d->id }}">
                                <span class="file-label" title="Upload File Sumber">
                                    <i class="fas fa-upload"></i>
                                </span>
                            </div>
                            
                            <span class="file-preview-text" id="filePreview-{{ $d->id }}">
                                <i class="fas fa-check-circle"></i> <span id="fileName-{{ $d->id }}"></span>
                            </span>
                            
                            @if(!empty($d->file_sumber))
                            <span class="file-name" title="{{ htmlspecialchars($d->file_sumber) }}">
                                <i class="fas fa-file"></i> {{ htmlspecialchars($d->file_sumber) }}
                            </span>
                            <a href="{{ route('admin.capaian.delete.file', ['id' => $d->id, 'tahun' => $tahun_aktif]) }}" 
                               class="btn-delete-file" 
                               onclick="return confirm('Yakin ingin menghapus file ini?')" 
                               title="Hapus File">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                    <td>
                        <input type="text" name="data[{{ $d->id }}][penanggung_jawab]" 
                               value="{{ htmlspecialchars($d->penanggung_jawab) }}" 
                               class="input-text" placeholder="Penanggung Jawab">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="form-actions">
        <div class="form-actions-left">
            <button type="button" class="btn-reset" id="btnReset" title="Reset Semua Data">
                <i class="fas fa-undo-alt"></i>
            </button>
            <span style="font-size:12px; color:#94a3b8;">
                <i class="fas fa-info-circle"></i> Reset target, realisasi, dan sumber data menjadi kosong
            </span>
        </div>
        <div class="form-actions-right">
            <button type="button" class="btn-save" id="btnSave">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </div>
    
    <div class="form-note">
        <i class="fas fa-calculator"></i> Capaian dihitung otomatis = (Realisasi / Target) × 100%
        <span style="margin-left:16px;"><i class="fas fa-upload"></i> Upload file per program (PDF, DOC, XLS, JPG, PNG) Max 10MB</span>
        <span style="margin-left:16px;"><i class="fas fa-link"></i> Sumber Data dapat diisi link</span>
        <span style="margin-left:16px;">
            <i class="fas fa-tag"></i> 
            <span style="color:#1d4ed8;">ISTIMEWA</span> &gt; 100% | 
            <span style="color:#065f46;">BAIK</span> 80-100% | 
            <span style="color:#92400e;">BUTUH PERBAIKAN</span> 60-80% | 
            <span style="color:#9a3412;">KURANG</span> 20-60% | 
            <span style="color:#991b1b;">SANGAT KURANG</span> 0-20%
        </span>
    </div>
</form>

<!-- Confirm Reset -->
<div class="confirm-overlay" id="confirmResetOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-trash" style="color:#dc2626;"></i></div>
        <div class="confirm-title">Reset Semua Data?</div>
        <div class="confirm-text">
            Semua nilai <strong>Target</strong>, <strong>Realisasi</strong>, <strong>Capaian</strong>, dan <strong>Sumber Data</strong> akan direset menjadi <strong>0 / kosong</strong> untuk tahun {{ $tahun_aktif }}.<br>
            <span style="color:#dc2626;"><i class="fas fa-exclamation-triangle"></i> File yang diupload juga akan dihapus.</span><br>
            Tindakan ini <strong>tidak dapat dibatalkan</strong>.
        </div>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="resetCancel">Batal</button>
            <a href="{{ route('admin.capaian.reset', ['tahun' => $tahun_aktif]) }}" class="confirm-btn confirm-btn-confirm danger" id="resetConfirm">Ya, Reset</a>
        </div>
    </div>
</div>

<!-- Confirm Save -->
<div class="confirm-overlay" id="confirmSaveOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-save" style="color:#0f3b5e;"></i></div>
        <div class="confirm-title">Simpan Perubahan?</div>
        <div class="confirm-text">
            Apakah Anda yakin ingin menyimpan semua perubahan yang telah dilakukan pada data capaian program tahun {{ $tahun_aktif }}?
        </div>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="saveCancel">Batal</button>
            <button class="confirm-btn confirm-btn-confirm" id="saveConfirm">Ya, Simpan</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== GET PREDIKAT LABEL =====
    function getPredikatLabel(capaian) {
        if (capaian > 100) {
            return { label: 'ISTIMEWA', class: 'predikat-istimewa' };
        } else if (capaian > 80) {
            return { label: 'BAIK', class: 'predikat-baik' };
        } else if (capaian > 60) {
            return { label: 'BUTUH PERBAIKAN', class: 'predikat-butuh' };
        } else if (capaian > 20) {
            return { label: 'KURANG', class: 'predikat-kurang' };
        } else if (capaian > 0) {
            return { label: 'SANGAT KURANG', class: 'predikat-sangat' };
        } else {
            return { label: 'BELUM ADA', class: 'predikat-belum' };
        }
    }

    // ===== HITUNG CAPAIAN =====
    function hitungCapaian(id) {
        var targetInput = document.querySelector('input[name="data[' + id + '][target]"]');
        var realisasiInput = document.querySelector('input[name="data[' + id + '][realisasi]"]');
        var capaianEl = document.getElementById('capaian-' + id);
        var predikatEl = document.getElementById('predikat-' + id);
        
        if (!targetInput || !realisasiInput || !capaianEl || !predikatEl) return;
        
        var targetVal = targetInput.value.trim() || '0';
        var realisasiVal = realisasiInput.value.trim() || '0';
        
        var target = parseFloat(targetVal.replace(/\./g, '').replace(',', '.')) || 0;
        var realisasi = parseFloat(realisasiVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        var capaian = 0;
        if (target > 0) {
            capaian = (realisasi / target) * 100;
        }
        
        var capaianFormatted = capaian.toFixed(2).replace('.', ',');
        capaianEl.textContent = capaianFormatted + '%';
        
        capaianEl.className = 'capaian-badge';
        if (capaian >= 80) capaianEl.classList.add('high');
        else if (capaian >= 50) capaianEl.classList.add('medium');
        else if (capaian > 0) capaianEl.classList.add('low');
        else capaianEl.classList.add('zero');
        
        var predikat = getPredikatLabel(capaian);
        predikatEl.textContent = predikat.label;
        predikatEl.className = 'predikat-badge ' + predikat.class;
    }

    // ===== EVENT LISTENERS =====
    document.querySelectorAll('.target-input, .realisasi-input').forEach(function(input) {
        input.addEventListener('input', function() {
            hitungCapaian(this.dataset.id);
        });
        input.addEventListener('change', function() {
            hitungCapaian(this.dataset.id);
        });
    });

    // ===== FILE UPLOAD PREVIEW =====
    document.querySelectorAll('input[type="file"][name^="file_sumber"]').forEach(function(input) {
        input.addEventListener('change', function() {
            var id = this.dataset.id;
            var file = this.files[0];
            var previewEl = document.getElementById('filePreview-' + id);
            var fileNameEl = document.getElementById('fileName-' + id);
            
            if (file) {
                previewEl.classList.add('show');
                fileNameEl.textContent = file.name;
                
                var oldFile = this.closest('.file-info-cell').querySelector('.file-name');
                if (oldFile) {
                    oldFile.style.display = 'none';
                }
                var oldDelete = this.closest('.file-info-cell').querySelector('.btn-delete-file');
                if (oldDelete) {
                    oldDelete.style.display = 'none';
                }
                
                var label = this.parentElement.querySelector('.file-label');
                if (label) {
                    label.style.background = '#d1fae5';
                    label.style.color = '#16a34a';
                    label.style.borderColor = '#16a34a';
                    label.innerHTML = '<i class="fas fa-check"></i>';
                }
            }
        });
    });

    // ===== CONFIRM RESET =====
    var btnReset = document.getElementById('btnReset');
    var resetOverlay = document.getElementById('confirmResetOverlay');
    var resetCancel = document.getElementById('resetCancel');
    
    if (btnReset) {
        btnReset.addEventListener('click', function(e) {
            e.preventDefault();
            resetOverlay.classList.add('show');
        });
    }
    
    if (resetCancel) {
        resetCancel.addEventListener('click', function() {
            resetOverlay.classList.remove('show');
        });
    }
    
    if (resetOverlay) {
        resetOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    }

    // ===== CONFIRM SAVE =====
    var btnSave = document.getElementById('btnSave');
    var saveOverlay = document.getElementById('confirmSaveOverlay');
    var saveCancel = document.getElementById('saveCancel');
    var saveConfirm = document.getElementById('saveConfirm');
    var form = document.getElementById('formCapaian');
    
    if (btnSave) {
        btnSave.addEventListener('click', function(e) {
            e.preventDefault();
            saveOverlay.classList.add('show');
        });
    }
    
    if (saveCancel) {
        saveCancel.addEventListener('click', function() {
            saveOverlay.classList.remove('show');
        });
    }
    
    if (saveConfirm) {
        saveConfirm.addEventListener('click', function() {
            saveOverlay.classList.remove('show');
            form.submit();
        });
    }
    
    if (saveOverlay) {
        saveOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    }

    // ===== SUCCESS ALERT AUTO HIDE =====
    var successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.display = 'none';
        }, 5000);
    }
});
</script>
@endsection