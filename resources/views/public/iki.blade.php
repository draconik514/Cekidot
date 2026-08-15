@extends('layouts.app')

@section('title', 'Daftar Dokumen IKI - CEKIDOT')

@section('styles')
<style>
    .iki-page {
        background-image: url('{{ asset('assets/img/background.jpg') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        padding: 0;
    }

    .iki-hero {
        background: linear-gradient(135deg, rgba(15, 59, 94, 0.92) 0%, rgba(26, 90, 122, 0.88) 100%);
        padding: 50px 0 40px;
        color: #fff;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border-bottom: 3px solid rgba(234, 179, 8, 0.3);
    }
    .iki-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -15%;
        width: 500px;
        height: 500px;
        background: rgba(234, 179, 8, 0.06);
        border-radius: 50%;
        pointer-events: none;
    }
    .iki-hero::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 50%;
        pointer-events: none;
    }
    .iki-hero .container { position: relative; z-index: 1; }
    .iki-hero .hero-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .iki-hero .hero-text h1 {
        font-size: 36px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .iki-hero .hero-text h1 i {
        color: #eab308;
        filter: drop-shadow(0 2px 8px rgba(234, 179, 8, 0.3));
    }
    .iki-hero .hero-text p {
        font-size: 16px;
        opacity: 0.85;
        max-width: 550px;
        line-height: 1.7;
        font-weight: 300;
    }
    .iki-hero .hero-stats {
        display: flex;
        gap: 30px;
        background: rgba(255,255,255,0.08);
        padding: 14px 28px;
        border-radius: 16px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    .iki-hero .hero-stats .stat { text-align: center; }
    .iki-hero .hero-stats .stat .number {
        font-size: 28px;
        font-weight: 800;
        color: #eab308;
        display: block;
        line-height: 1.2;
    }
    .iki-hero .hero-stats .stat .label {
        font-size: 11px;
        opacity: 0.6;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 400;
    }

    .iki-content {
        padding: 40px 0 60px;
        min-height: 60vh;
    }
    .iki-content .content-wrapper {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 32px 36px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        border: 1px solid rgba(255,255,255,0.3);
    }

    .filter-tahun {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 28px;
        background: #f1f5f9;
        border-radius: 14px;
        padding: 4px;
        border: 1px solid #e2e8f0;
        justify-content: center;
        flex-wrap: nowrap;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .filter-tahun .btn-tahun {
        width: 44px;
        height: 44px;
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 10px;
        margin: 2px;
    }
    .filter-tahun .btn-tahun:hover:not(:disabled) {
        background: #0f3b5e;
        color: #fff;
        transform: scale(1.05);
    }
    .filter-tahun .btn-tahun:disabled {
        opacity: 0.25;
        cursor: not-allowed;
    }
    .filter-tahun .tahun-items {
        display: flex;
        align-items: center;
        gap: 2px;
        flex: 1;
        justify-content: center;
        padding: 0 4px;
    }
    .filter-tahun .tahun-items .tahun-item {
        padding: 6px 16px;
        border: none;
        background: transparent;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'Inter', sans-serif;
        position: relative;
        min-width: 48px;
        text-align: center;
        text-decoration: none;
    }
    .filter-tahun .tahun-items .tahun-item:hover {
        color: #0f3b5e;
        background: rgba(15, 59, 94, 0.06);
    }
    .filter-tahun .tahun-items .tahun-item.active {
        background: #0f3b5e;
        color: #fff;
        box-shadow: 0 4px 12px rgba(15, 59, 94, 0.25);
        font-weight: 600;
    }
    .filter-tahun .tahun-items .tahun-item .count {
        font-size: 9px;
        opacity: 0.5;
        margin-left: 2px;
        font-weight: 400;
    }
    .filter-tahun .tahun-items .tahun-item.active .count {
        opacity: 0.7;
        color: rgba(255,255,255,0.7);
    }
    .filter-tahun .tahun-range-label {
        font-size: 11px;
        color: #94a3b8;
        padding: 0 12px;
        font-weight: 400;
        letter-spacing: 0.5px;
        flex-shrink: 0;
        border-left: 1px solid #e2e8f0;
        margin-left: 4px;
        padding-left: 16px;
    }
    .filter-tahun .tahun-range-label i { margin-right: 4px; font-size: 10px; }

    .dokumen-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
    .dokumen-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 20px;
        background: #ffffff;
        border-radius: 14px;
        border: 1.5px solid #e8ecf1;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
        position: relative;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .dokumen-card:hover {
        border-color: #0f3b5e;
        box-shadow: 0 8px 30px rgba(15, 59, 94, 0.10);
        transform: translateY(-2px);
    }
    .dokumen-card:active { transform: scale(0.98); }
    .dokumen-card .card-number {
        font-weight: 800;
        color: #0f3b5e;
        font-size: 14px;
        min-width: 32px;
        text-align: center;
        background: #f1f5f9;
        padding: 4px 0;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .dokumen-card .card-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #dbeafe;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        transition: all 0.3s;
    }
    .dokumen-card:hover .card-icon {
        background: #0f3b5e;
        color: #fff;
        transform: scale(1.05);
    }
    .dokumen-card .card-info { flex: 1; min-width: 0; }
    .dokumen-card .card-info .title {
        font-size: 15px;
        font-weight: 600;
        color: #0f3b5e;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .dokumen-card .card-info .title .badge-type {
        font-size: 10px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }
    .dokumen-card .card-info .title .badge-type.file {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .dokumen-card .card-info .title .badge-type.link {
        background: #fef3c7;
        color: #b45309;
    }
    .dokumen-card .card-info .description {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin-top: 2px;
    }
    .dokumen-card .card-action { flex-shrink: 0; }
    .dokumen-card .card-action .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        background: #0f3b5e;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }
    .dokumen-card .card-action .btn-view:hover {
        background: #eab308;
        color: #0f3b5e;
        transform: scale(1.02);
        box-shadow: 0 4px 16px rgba(234, 179, 8, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 56px;
        opacity: 0.2;
        display: block;
        margin-bottom: 16px;
        color: #0f3b5e;
    }
    .empty-state h3 { font-size: 20px; color: #1e293b; margin-bottom: 4px; }
    .empty-state p { font-size: 14px; }

    /* Modal Preview - Sama seperti di akip */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.show {
        display: flex;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .modal-box {
        background: #ffffff;
        border-radius: 24px;
        width: 100%;
        max-width: 1200px;
        height: 90vh;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 40px 100px rgba(0,0,0,0.5);
        animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-box .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 2px solid #e8ecf1;
        flex-shrink: 0;
        background: #fafbfc;
        gap: 12px;
    }
    .modal-box .modal-header .left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }
    .modal-box .modal-header .left .icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #dbeafe;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .modal-box .modal-header .left .title-group { flex: 1; min-width: 0; }
    .modal-box .modal-header .left .title-group h3 {
        font-size: 17px;
        font-weight: 700;
        color: #0f3b5e;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .modal-box .modal-header .left .title-group .sub {
        font-size: 13px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .modal-box .modal-header .left .title-group .sub .badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .modal-box .modal-header .left .title-group .sub .badge.file {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .modal-box .modal-header .left .title-group .sub .badge.link {
        background: #fef3c7;
        color: #b45309;
    }
    .modal-box .modal-header .left .title-group .sub .badge.aktif {
        background: #d1fae5;
        color: #065f46;
    }
    .modal-box .modal-header .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.3s;
        padding: 4px 12px;
        border-radius: 8px;
        line-height: 1;
        flex-shrink: 0;
    }
    .modal-box .modal-header .modal-close:hover {
        color: #dc2626;
        background: #fef2f2;
    }
    .modal-box .modal-body {
        flex: 1;
        padding: 16px 24px 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background: #f1f5f9;
        gap: 12px;
    }
    .modal-box .modal-body .info-simple {
        display: grid;
        grid-template-columns: 1fr;
        gap: 6px 0;
        background: #ffffff;
        padding: 14px 20px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .modal-box .modal-body .info-simple .item {
        display: flex;
        flex-direction: column;
    }
    .modal-box .modal-body .info-simple .item .label {
        font-size: 10px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 600;
    }
    .modal-box .modal-body .info-simple .item .value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
        word-break: break-all;
    }
    .modal-box .modal-body .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
        flex-wrap: wrap;
        gap: 8px;
    }
    .modal-box .modal-body .toolbar .file-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }
    .modal-box .modal-body .toolbar .file-info .name {
        font-weight: 500;
        color: #1e293b;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .modal-box .modal-body .toolbar .actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }
    .modal-box .modal-body .toolbar .actions .btn {
        padding: 7px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
    }
    .modal-box .modal-body .toolbar .actions .btn-download {
        background: #0f3b5e;
        color: #fff;
    }
    .modal-box .modal-body .toolbar .actions .btn-download:hover {
        background: #0a2a44;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15,59,94,0.3);
    }
    .modal-box .modal-body .toolbar .actions .btn-link {
        background: #fef3c7;
        color: #b45309;
    }
    .modal-box .modal-body .toolbar .actions .btn-link:hover {
        background: #fde68a;
    }
    .modal-box .modal-body .preview-container {
        flex: 1;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        min-height: 200px;
        position: relative;
    }
    .modal-box .modal-body .preview-container iframe {
        width: 100%;
        height: 100%;
        border: none;
        min-height: 400px;
    }
    .modal-box .modal-body .preview-container .no-preview {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #94a3b8;
        padding: 40px;
        text-align: center;
    }
    .modal-box .modal-body .preview-container .no-preview i {
        font-size: 72px;
        opacity: 0.2;
        margin-bottom: 16px;
        color: #0f3b5e;
    }
    .modal-box .modal-body .preview-container .no-preview .ext {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
    }
    .modal-box .modal-body .preview-container .no-preview .hint {
        font-size: 14px;
        color: #94a3b8;
        margin-top: 4px;
    }
    .modal-box .modal-body .security-warning {
        background: #fef3c7;
        padding: 8px 16px;
        border-radius: 8px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border: 1px solid #fde68a;
        flex-shrink: 0;
    }
    .modal-box .modal-body .security-warning i {
        color: #b45309;
        font-size: 16px;
        margin-top: 1px;
    }
    .modal-box .modal-body .security-warning div {
        font-size: 12px;
        color: #92400e;
    }
    .modal-box .modal-body .security-warning div strong { display: block; }

    @media (max-width: 992px) {
        .iki-hero .hero-content { flex-direction: column; align-items: flex-start; }
        .iki-hero .hero-stats { width: 100%; justify-content: center; }
        .iki-content .content-wrapper { padding: 24px 20px; }
        .filter-tahun .tahun-items .tahun-item { padding: 4px 10px; font-size: 12px; min-width: 36px; }
        .filter-tahun .tahun-range-label { font-size: 10px; padding: 0 8px; padding-left: 12px; }
        .modal-box { max-width: 100%; height: 85vh; border-radius: 16px; }
        .modal-box .modal-body { padding: 12px 16px; }
    }
    @media (max-width: 768px) {
        .iki-hero { padding: 30px 0 24px; }
        .iki-hero .hero-text h1 { font-size: 26px; }
        .iki-hero .hero-text p { font-size: 14px; }
        .iki-hero .hero-stats { padding: 10px 16px; gap: 16px; }
        .iki-hero .hero-stats .stat .number { font-size: 20px; }
        .iki-hero .hero-stats .stat .label { font-size: 10px; }
        .iki-content .content-wrapper { padding: 16px 12px; }
        .filter-tahun { flex-wrap: wrap; padding: 4px; gap: 2px; }
        .filter-tahun .btn-tahun { width: 36px; height: 36px; font-size: 14px; }
        .filter-tahun .tahun-items { flex-wrap: wrap; gap: 2px; padding: 2px; }
        .filter-tahun .tahun-items .tahun-item { padding: 4px 8px; font-size: 12px; min-width: 32px; }
        .filter-tahun .tahun-range-label { display: none; }
        .dokumen-card { flex-wrap: wrap; padding: 14px 16px; }
        .dokumen-card .card-number { min-width: 28px; font-size: 12px; }
        .dokumen-card .card-icon { width: 38px; height: 38px; font-size: 16px; }
        .dokumen-card .card-info .title { font-size: 14px; }
        .dokumen-card .card-action .btn-view { padding: 6px 16px; font-size: 12px; width: 100%; justify-content: center; }
        .dokumen-card .card-action { width: 100%; }
        .modal-box { height: 80vh; border-radius: 12px; }
        .modal-box .modal-header { padding: 12px 16px; }
        .modal-box .modal-header .left .title-group h3 { font-size: 15px; }
        .modal-box .modal-body { padding: 10px 12px; }
        .modal-box .modal-body .toolbar { flex-direction: column; align-items: stretch; gap: 8px; }
        .modal-box .modal-body .toolbar .actions { justify-content: center; }
        .modal-box .modal-body .toolbar .actions .btn { flex: 1; justify-content: center; }
        .modal-box .modal-body .preview-container iframe { min-height: 200px; }
        .modal-box .modal-body .preview-container .no-preview i { font-size: 48px; }
        .modal-box .modal-body .preview-container .no-preview .ext { font-size: 15px; }
    }
    @media (max-width: 480px) {
        .iki-hero .hero-text h1 { font-size: 22px; }
        .iki-hero .hero-stats { flex-wrap: wrap; gap: 12px; justify-content: center; }
        .iki-hero .hero-stats .stat { flex: 1; min-width: 50px; }
        .iki-hero .hero-stats .stat .number { font-size: 18px; }
        .filter-tahun .btn-tahun { width: 30px; height: 30px; font-size: 12px; }
        .filter-tahun .tahun-items .tahun-item { padding: 3px 6px; font-size: 11px; min-width: 28px; }
        .filter-tahun .tahun-items .tahun-item .count { font-size: 8px; }
        .dokumen-card { padding: 12px 14px; }
        .dokumen-card .card-info .title { font-size: 13px; }
        .dokumen-card .card-info .description { font-size: 12px; }
        .modal-box .modal-header .left .icon { width: 32px; height: 32px; font-size: 14px; }
        .modal-box .modal-header .left .title-group h3 { font-size: 13px; }
        .modal-box .modal-body .toolbar .file-info .name { font-size: 13px; }
        .modal-box .modal-body .info-simple .item .value { font-size: 13px; }
    }
</style>
@endsection

@section('content')
<section class="iki-page">
    <section class="iki-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>
                        <i class="fas fa-user-check"></i> 
                        Dokumen IKI
                    </h1>
                    <p>
                        Dokumentasi Indeks Kinerja Instansi Pemerintah. 
                        Mengukur pencapaian kinerja dan efektivitas pelayanan publik.
                    </p>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="number">{{ $total_dokumen }}</span>
                        <span class="label">Total Dokumen</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="iki-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="filter-tahun">
                    <button class="btn-tahun" onclick="window.location.href='{{ route('iki.public') }}?tahun={{ $tahun_aktif - 1 }}'" {{ $tahun_aktif <= 2025 ? 'disabled' : '' }}>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="tahun-items">
                        @foreach($tahun_list as $t)
                            @php
                                $count = App\Models\DokumenIki::where('tahun', $t)->where('status', 'aktif')->count();
                            @endphp
                            <a href="{{ route('iki.public') }}?tahun={{ $t }}" class="tahun-item {{ $t == $tahun_aktif ? 'active' : '' }}">
                                {{ $t }}
                                <span class="count">({{ $count }})</span>
                            </a>
                        @endforeach
                    </div>
                    
                    <span class="tahun-range-label">
                        <i class="fas fa-calendar-alt"></i> 2025 - 2030
                    </span>
                    
                    <button class="btn-tahun" onclick="window.location.href='{{ route('iki.public') }}?tahun={{ $tahun_aktif + 1 }}'" {{ $tahun_aktif >= 2030 ? 'disabled' : '' }}>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="dokumen-grid">
                    @if($dokumen->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <h3>Belum Ada Dokumen</h3>
                        <p>Belum ada dokumen IKI yang tersedia untuk tahun {{ $tahun_aktif }}</p>
                    </div>
                    @else
                    @php $no = 1; @endphp
                    @foreach($dokumen as $d)
                    @php
                        $tipe = $d->tipe_konten ?? 'file';
                        $file_name = $d->file_dokumen ?? '';
                        $icon = 'fa-file';
                        $badge_type = $tipe == 'file' ? 'file' : 'link';
                        $badge_label = $tipe == 'file' ? 'File' : 'Link';
                    @endphp
                    <div class="dokumen-card" onclick="openPreviewModal(
                        '{{ addslashes($d->judul) }}',
                        '{{ addslashes($d->deskripsi ?? '') }}',
                        '{{ $d->tipe_konten ?? 'file' }}',
                        '{{ $d->status ?? 'aktif' }}',
                        '{{ addslashes($file_name) }}',
                        '{{ addslashes($d->link_url ?? '') }}'
                    )">
                        <div class="card-number">#{{ $no++ }}</div>
                        <div class="card-icon"><i class="fas {{ $icon }}"></i></div>
                        <div class="card-info">
                            <div class="title">
                                {{ $d->judul }}
                                <span class="badge-type {{ $badge_type }}">
                                    <i class="fas {{ $tipe == 'file' ? 'fa-upload' : 'fa-link' }}"></i>
                                    {{ $badge_label }}
                                </span>
                            </div>
                            @if(!empty($d->deskripsi))
                            <div class="description">{{ $d->deskripsi }}</div>
                            @endif
                        </div>
                        <div class="card-action">
                            <button class="btn-view" onclick="event.stopPropagation(); openPreviewModal(
                                '{{ addslashes($d->judul) }}',
                                '{{ addslashes($d->deskripsi ?? '') }}',
                                '{{ $d->tipe_konten ?? 'file' }}',
                                '{{ $d->status ?? 'aktif' }}',
                                '{{ addslashes($file_name) }}',
                                '{{ addslashes($d->link_url ?? '') }}'
                            )">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>

                @include('public.partials.upload-anggota-section', ['route_name' => route('iki.public')])

            </div>
        </div>
    </section>
</section>

<!-- Modal Preview - Sama dengan akip -->
<div class="modal-overlay" id="previewModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="left">
                <div class="icon" id="modalIcon"><i class="fas fa-file"></i></div>
                <div class="title-group">
                    <h3 id="modalTitle">Detail Dokumen</h3>
                    <div class="sub">
                        <span id="modalTipe"><i class="fas fa-tag"></i> <span class="badge file">File</span></span>
                        <span id="modalStatus"><i class="fas fa-circle"></i> <span class="badge aktif">Aktif</span></span>
                    </div>
                </div>
            </div>
            <button class="modal-close" onclick="closePreviewModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div class="info-simple">
                <div class="item">
                    <span class="label">Judul</span>
                    <span class="value" id="modalJudul">-</span>
                </div>
                <div class="item">
                    <span class="label">Deskripsi</span>
                    <span class="value" id="modalDeskripsi">-</span>
                </div>
            </div>

            <div class="toolbar">
                <div class="file-info">
                    <span class="name" id="modalFileTitle">-</span>
                </div>
                <div class="actions">
                    <a href="#" class="btn btn-download" id="downloadBtn" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                    <a href="#" class="btn btn-link" id="linkBtn" target="_blank" style="display:none;">
                        <i class="fas fa-external-link-alt"></i> Buka Link
                    </a>
                </div>
            </div>

            <div class="preview-container" id="previewContainer">
                <div class="no-preview">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span class="ext">Memuat file...</span>
                </div>
            </div>

            <div class="security-warning">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <strong>⚠️ Peringatan Keamanan</strong>
                    Pastikan file/link aman sebelum diakses. File dari sumber tidak dikenal berpotensi mengandung virus.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getFileIcon(ext) {
    return 'fa-file';
}

function openPreviewModal(judul, deskripsi, tipeKonten, status, fileName, linkUrl) {
    var modal = document.getElementById('previewModal');
    var container = document.getElementById('previewContainer');
    var tipe = tipeKonten || 'file';
    
    document.getElementById('modalTitle').textContent = judul;
    document.getElementById('modalJudul').textContent = judul;
    document.getElementById('modalDeskripsi').textContent = deskripsi || '-';
    
    var tipeText = tipe === 'file' ? 'File' : 'Link';
    var tipeClass = tipe === 'file' ? 'file' : 'link';
    document.getElementById('modalTipe').innerHTML = '<i class="fas fa-tag"></i> <span class="badge ' + tipeClass + '">' + tipeText + '</span>';
    
    var statusText = status || 'aktif';
    var statusClass = statusText === 'aktif' ? 'aktif' : 'nonaktif';
    document.getElementById('modalStatus').innerHTML = '<i class="fas fa-circle"></i> <span class="badge ' + statusClass + '">' + statusText + '</span>';
    
    document.getElementById('modalFileTitle').textContent = fileName || linkUrl || 'Tidak ada file';
    
    var downloadBtn = document.getElementById('downloadBtn');
    var linkBtn = document.getElementById('linkBtn');
    var iconEl = document.getElementById('modalIcon').querySelector('i');
    
    downloadBtn.style.display = 'inline-flex';
    linkBtn.style.display = 'none';
    downloadBtn.href = '#';
    downloadBtn.removeAttribute('download');
    iconEl.className = 'fas fa-file';
    
    if (tipe === 'file' && fileName) {
        var filePath = '{{ asset('storage/uploads/iki') }}/' + fileName;
        var ext = fileName.split('.').pop().toLowerCase();
        var isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext);
        var isPDF = ext === 'pdf';
        var isArchive = ['zip', 'rar', '7z', 'tar', 'gz', 'tgz', 'bz2', 'xz'].includes(ext);
        var isOffice = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(ext);
        
        if (isImage) {
            container.innerHTML = '<img src="' + filePath + '" style="width:100%; height:100%; object-fit:contain;" alt="' + fileName + '" onerror="this.parentElement.innerHTML=\'<div class=\\\'no-preview\\\'><i class=\\\'fas fa-file\\\' style=\\\'font-size:72px; color:#0f3b5e; opacity:0.2;\\\'></i><span class=\\\'ext\\\'>File tidak ditemukan</span></div>\'">';
        } else if (isPDF) {
            container.innerHTML = '<iframe src="' + filePath + '#toolbar=1" style="width:100%; height:100%; border:none;"></iframe>';
        } else if (isArchive || isOffice) {
            container.innerHTML = `
                <div class="no-preview">
                    <i class="fas fa-file" style="font-size:72px; color:#0f3b5e; opacity:0.3;"></i>
                    <span class="ext">File ${ext.toUpperCase()}</span>
                    <p class="hint">File ${isArchive ? 'arsip' : 'dokumen'} tidak dapat ditampilkan di browser.</p>
                    <p class="hint">Silakan download untuk membuka.</p>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="no-preview">
                    <i class="fas fa-file" style="font-size:72px; color:#0f3b5e; opacity:0.3;"></i>
                    <span class="ext">File ${ext.toUpperCase()}</span>
                    <p class="hint">File tidak dapat ditampilkan di browser.</p>
                    <p class="hint">Silakan download untuk membuka.</p>
                </div>
            `;
        }
        
        downloadBtn.href = filePath;
        downloadBtn.setAttribute('download', fileName);
        downloadBtn.innerHTML = '<i class="fas fa-download"></i> Download';
        downloadBtn.style.display = 'inline-flex';
        
    } else if (tipe === 'link' && linkUrl) {
        container.innerHTML = `
            <div class="no-preview">
                <i class="fas fa-external-link-alt" style="font-size:72px; color:#0f3b5e; opacity:0.3;"></i>
                <span class="ext">Dokumen via Link</span>
                <p class="hint">Klik tombol "Buka Link" untuk membuka dokumen.</p>
                <p class="hint" style="font-size:13px; color:#94a3b8; margin-top:8px; word-break:break-all;">${linkUrl}</p>
            </div>
        `;
        downloadBtn.style.display = 'none';
        linkBtn.style.display = 'inline-flex';
        linkBtn.href = linkUrl;
        linkBtn.innerHTML = '<i class="fas fa-external-link-alt"></i> Buka Link';
        
    } else {
        container.innerHTML = `
            <div class="no-preview">
                <i class="fas fa-file" style="font-size:72px; color:#0f3b5e; opacity:0.2;"></i>
                <span class="ext">Tidak ada file</span>
                <p class="hint">Dokumen ini tidak memiliki file atau link.</p>
            </div>
        `;
        downloadBtn.style.display = 'none';
        linkBtn.style.display = 'none';
    }
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});

document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});
</script>
@endsection