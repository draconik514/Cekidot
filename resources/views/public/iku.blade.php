@extends('layouts.app')

@section('title', 'IKU - CEKIDOT')

@section('styles')
<style>
    /* ===== STYLE SAMA DENGAN IKU.PHP PUBLIK ASLI ===== */
    .iku-page {
        background-image: url('{{ asset('assets/img/background.jpg') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        padding: 30px 0 50px;
    }
    .iku-page .iku-wrapper {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 32px 36px;
        border-radius: 24px;
        max-width: 1100px;
        margin: 0 auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        border: 1px solid rgba(255,255,255,0.4);
    }

    .iku-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .iku-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.5px;
    }
    .iku-header h1 i { color: #eab308; }
    .iku-header .info {
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }

    .iku-subtitle {
        color: #475569;
        font-size: 14px;
        margin-bottom: 24px;
        line-height: 1.7;
        padding: 14px 20px;
        background: #f8fafc;
        border-radius: 12px;
        border-left: 5px solid #eab308;
        font-weight: 500;
    }

    .filter-kategori {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 16px;
        padding: 6px;
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        justify-content: center;
        flex-wrap: wrap;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .filter-kategori .btn-kategori {
        padding: 8px 28px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        background: transparent;
        color: #64748b;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .filter-kategori .btn-kategori .icon { font-size: 16px; opacity: 0.4; transition: 0.3s; }
    .filter-kategori .btn-kategori:hover { color: #0f3b5e; background: #f1f5f9; }
    .filter-kategori .btn-kategori:hover .icon { opacity: 1; }
    .filter-kategori .btn-kategori.active {
        background: #0f3b5e;
        color: #fff;
        box-shadow: 0 4px 12px rgba(15,59,94,0.25);
    }
    .filter-kategori .btn-kategori.active .icon { opacity: 1; color: #eab308; }

    .tahun-nav {
        display: flex;
        align-items: center;
        gap: 4px;
        justify-content: center;
        margin-bottom: 20px;
        padding: 4px 10px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }
    .tahun-nav .btn-tahun {
        padding: 4px 14px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: 0.3s;
        background: transparent;
        color: #64748b;
        text-decoration: none;
    }
    .tahun-nav .btn-tahun:hover { background: #e2e8f0; color: #0f3b5e; }
    .tahun-nav .btn-tahun.active { background: #0f3b5e; color: #fff; }

    .wisatawan-sub-nav {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .wisatawan-sub-nav .btn-sub {
        padding: 4px 16px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: 0.3s;
        background: transparent;
        color: #64748b;
        text-decoration: none;
    }
    .wisatawan-sub-nav .btn-sub:hover { background: #f1f5f9; color: #0f3b5e; }
    .wisatawan-sub-nav .btn-sub.active { background: #0f3b5e; color: #fff; }

    /* Infografis */
    .infografis-section {
        margin-bottom: 24px;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .infografis-section .infografis-wrapper {
        position: relative;
        width: 100%;
        padding-top: 56.25%;
        background: #f1f5f9;
    }
    .infografis-section .infografis-wrapper img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .infografis-section .infografis-wrapper .no-infografis {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        color: #94a3b8;
        gap: 6px;
    }
    .infografis-section .infografis-wrapper .no-infografis i { font-size: 32px; opacity: 0.3; }
    .infografis-section .infografis-wrapper .no-infografis span { font-size: 14px; font-weight: 500; }

    /* Result Box */
    .result-box {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    .result-box .card {
        background: linear-gradient(145deg, #0f3b5e 0%, #1a5276 100%);
        border-radius: 16px;
        padding: 22px 20px;
        text-align: center;
        color: #fff;
        box-shadow: 0 8px 24px rgba(15,59,94,0.15);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .result-box .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(15,59,94,0.25);
    }
    .result-box .card .label {
        font-size: 12px;
        opacity: 0.75;
        font-weight: 500;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 6px;
    }
    .result-box .card .value {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: 0.3px;
    }
    .result-box .card .value .persen {
        font-size: 18px;
        font-weight: 400;
        opacity: 0.8;
    }
    .result-box .card .value.gold { color: #eab308; }

    /* Tables */
    .table-wrapper, .wisatawan-table-wrapper, .ekraf-table-wrapper {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #fff;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .table-header, .wisatawan-header, .ekraf-header {
        padding: 16px 20px;
        background: #fafbfc;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .table-header .table-title, .wisatawan-title, .ekraf-title {
        font-weight: 700;
        color: #0f3b5e;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .table-header .table-title i, .wisatawan-title i, .ekraf-title i { color: #eab308; }
    .table-header .table-note {
        font-size: 12px;
        color: #94a3b8;
        background: #fff;
        padding: 3px 14px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    table td { padding: 10px 18px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    table tr:last-child td { border-bottom: none; }
    table .total-row { background: #f8fafc; font-weight: 700; border-top: 2px solid #e2e8f0; }
    table .total-row td { padding: 12px 18px; }
    table .total-row .label-kontribusi {
        font-size: 14px;
        font-weight: 600;
        color: #0f3b5e;
        line-height: 1.4;
    }
    .hasil-persen { color: #eab308; font-size: 18px; font-weight: 700; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }

    /* Wisatawan Table */
    .wisatawan-table-wrapper table { font-size: 12px; min-width: 700px; }
    .wisatawan-table-wrapper table th {
        text-align: center; padding: 8px 6px; background: #fafbfc;
        font-weight: 600; color: #1e293b; border-bottom: 1px solid #e2e8f0;
        font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;
    }
    .wisatawan-table-wrapper table th:first-child { text-align: left; min-width: 120px; }
    .wisatawan-table-wrapper table td { padding: 6px 4px; border-bottom: 1px solid #f1f5f9; }
    .wisatawan-table-wrapper table td:first-child { font-weight: 600; color: #0f3b5e; font-size: 11px; }
    .wisatawan-table-wrapper table .total-kab { font-weight: 700; color: #0f3b5e; text-align: right; }
    .wisatawan-table-wrapper table .total-bulan { font-weight: 700; color: #eab308; text-align: right; background: #fefce8; }
    .wisatawan-table-wrapper table .total-row td { background: #f8fafc; border-top: 2px solid #e2e8f0; font-weight: 700; color: #0f3b5e; }
    .wisatawan-table-wrapper table .grand-total td { background: #fef3c7; border-top: 2px solid #eab308; }
    .wisatawan-caption { padding: 10px 20px; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; background: #fafbfc; text-align: right; }
    .wisatawan-caption i { color: #0f3b5e; margin-right: 4px; }

    /* Ekraf Table */
    .ekraf-table-wrapper table { font-size: 12px; min-width: 700px; }
    .ekraf-table-wrapper table th {
        text-align: center; padding: 8px 6px; background: #fafbfc;
        font-weight: 600; color: #1e293b; border-bottom: 1px solid #e2e8f0;
        font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .ekraf-table-wrapper table td { padding: 6px 6px; border-bottom: 1px solid #f1f5f9; }
    .ekraf-table-wrapper table .total-row { background: #f8fafc; font-weight: 700; border-top: 2px solid #e2e8f0; }
    .ekraf-table-wrapper table .total-row td { padding: 10px 8px; }
    .ekraf-table-wrapper table .proporsi-row { background: #fef3c7; border-top: 2px solid #eab308; }
    .ekraf-table-wrapper table .proporsi-row td { padding: 10px 8px; }
    .ekraf-table-wrapper table .proporsi-row .proporsi-value { color: #dc2626; font-size: 18px; font-weight: 700; text-align: right; }

    /* PDRB Section */
    .pdrb-section {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .pdrb-section .pdrb-title {
        font-weight: 700;
        color: #0f3b5e;
        font-size: 15px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pdrb-section .pdrb-title i { color: #eab308; }
    .pdrb-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .pdrb-grid .pdrb-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 14px 16px;
        text-align: center;
        border: 1px solid #e2e8f0;
        transition: 0.2s;
    }
    .pdrb-grid .pdrb-item:hover { border-color: #0f3b5e; background: #fff; }
    .pdrb-grid .pdrb-item .label {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .pdrb-grid .pdrb-item .value {
        font-size: 20px;
        font-weight: 700;
        color: #0f3b5e;
        margin-top: 2px;
    }
    .pdrb-grid .pdrb-item .value .persen {
        font-size: 16px;
        font-weight: 400;
        color: #94a3b8;
    }

    /* Predikat */
    .predikat-box {
        margin-top: 10px;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        text-align: center;
        transition: all 0.3s;
        letter-spacing: 0.5px;
    }
    .predikat-box .predikat-icon { margin-right: 8px; }
    .predikat-box.istimewa {
        background: #dbeafe;
        color: #1d4ed8;
        border: 2px solid #93c5fd;
        box-shadow: 0 2px 8px rgba(29,78,216,0.15);
    }
    .predikat-box.baik {
        background: #d1fae5;
        color: #065f46;
        border: 2px solid #86efac;
        box-shadow: 0 2px 8px rgba(6,95,70,0.15);
    }
    .predikat-box.butuh-perbaikan {
        background: #fef3c7;
        color: #92400e;
        border: 2px solid #fcd34d;
        box-shadow: 0 2px 8px rgba(146,64,14,0.15);
    }
    .predikat-box.kurang {
        background: #ffedd5;
        color: #9a3412;
        border: 2px solid #fdba74;
        box-shadow: 0 2px 8px rgba(154,52,18,0.15);
    }
    .predikat-box.sangat-kurang {
        background: #fef2f2;
        color: #991b1b;
        border: 2px solid #fca5a5;
        box-shadow: 0 2px 8px rgba(153,27,27,0.15);
    }
    .predikat-box.belum-ada {
        background: #f1f5f9;
        color: #64748b;
        border: 2px solid #cbd5e1;
        box-shadow: 0 2px 8px rgba(100,116,139,0.10);
    }

    /* Formula & Sumber */
    .formula-box {
        background: #f8fafc;
        padding: 18px 22px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }
    .formula-box .formula-title {
        font-weight: 700;
        color: #0f3b5e;
        margin-bottom: 10px;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .formula-box .formula-title i { color: #eab308; }
    .formula-box .formula {
        font-size: 20px;
        font-weight: 700;
        color: #0f3b5e;
        text-align: center;
        padding: 14px;
        background: #fff;
        border-radius: 12px;
        font-family: 'Times New Roman', serif;
        font-style: italic;
        margin-bottom: 14px;
        border: 1px solid #e2e8f0;
    }
    .formula-box .formula-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.8;
    }
    .formula-box .formula-desc strong { color: #0f3b5e; }

    .sumber-section {
        background: #f8fafc;
        padding: 18px 22px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        margin-top: 4px;
    }
    .sumber-section .sumber-title {
        font-weight: 700;
        color: #0f3b5e;
        margin-bottom: 12px;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sumber-section .sumber-title i { color: #eab308; }
    .sumber-section .sumber-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        margin-bottom: 6px;
    }
    .sumber-section .sumber-link i { color: #0f3b5e; }
    .sumber-section .sumber-link a {
        color: #0f3b5e;
        text-decoration: none;
        word-break: break-all;
        font-size: 13px;
        font-weight: 500;
        transition: 0.2s;
    }
    .sumber-section .sumber-link a:hover { color: #eab308; text-decoration: underline; }
    .sumber-section .sumber-file {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 14px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }
    .sumber-section .sumber-file i { font-size: 20px; color: #0f3b5e; }
    .sumber-section .sumber-file .file-name { color: #1e293b; font-weight: 500; font-size: 13px; flex: 1; }
    .sumber-section .sumber-file .btn-group { display: flex; gap: 6px; }
    .sumber-section .sumber-file .btn-group .btn {
        padding: 4px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.2s;
    }
    .sumber-section .sumber-file .btn-group .btn-view { background: #dbeafe; color: #1d4ed8; }
    .sumber-section .sumber-file .btn-group .btn-view:hover { background: #93c5fd; }
    .sumber-section .sumber-file .btn-group .btn-download { background: #0f3b5e; color: #fff; }
    .sumber-section .sumber-file .btn-group .btn-download:hover { background: #0a2a44; transform: translateY(-1px); }

    .btn-back-home {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 20px;
        background: #0f3b5e;
        color: #fff;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-back-home:hover {
        background: #0a2a44;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(15,59,94,0.3);
    }
    .btn-back-home i { font-size: 14px; }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 24px;
        max-width: 800px;
        width: 100%;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 30px 80px rgba(0,0,0,0.3);
        animation: modalIn 0.25s ease-out;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-box .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 24px;
        border-bottom: 2px solid #e8ecf1;
        flex-shrink: 0;
        background: #fafbfc;
    }
    .modal-box .modal-header h3 { font-size: 17px; color: #0f3b5e; display: flex; align-items: center; gap: 10px; }
    .modal-box .modal-header h3 i { color: #eab308; }
    .modal-box .modal-header .modal-close {
        background: none; border: none; font-size: 26px; color: #94a3b8;
        cursor: pointer; transition: 0.3s; padding: 0 8px; border-radius: 6px; line-height: 1;
    }
    .modal-box .modal-header .modal-close:hover { color: #dc2626; background: #fef2f2; }
    .modal-box .modal-body { flex: 1; padding: 16px 24px 20px; overflow-y: auto; background: #f1f5f9; }
    .modal-box .modal-body .preview-container {
        background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;
        min-height: 250px; height: 55vh;
    }
    .modal-box .modal-body .preview-container iframe { width: 100%; height: 100%; border: none; }
    .modal-box .modal-body .preview-container .no-preview {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        height: 100%; color: #94a3b8; padding: 40px; text-align: center;
    }
    .modal-box .modal-body .preview-container .no-preview i { font-size: 44px; opacity: 0.3; margin-bottom: 10px; }
    .modal-box .modal-body .preview-container .no-preview .ext { font-size: 15px; font-weight: 500; color: #1e293b; }
    .modal-box .modal-actions {
        display: flex; gap: 10px; justify-content: flex-end;
        padding: 10px 24px; border-top: 1px solid #e8ecf1; background: #fafbfc; flex-shrink: 0;
    }
    .modal-box .modal-actions .btn {
        padding: 7px 20px; border-radius: 10px; font-weight: 600; font-size: 13px;
        border: none; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none;
    }
    .modal-box .modal-actions .btn-close-modal { background: #f1f5f9; color: #1e293b; }
    .modal-box .modal-actions .btn-close-modal:hover { background: #e2e8f0; }
    .modal-box .modal-actions .btn-download-modal { background: #0f3b5e; color: #fff; }
    .modal-box .modal-actions .btn-download-modal:hover { background: #0a2a44; }

    @media (max-width: 992px) {
        .iku-page .iku-wrapper { padding: 24px 20px; margin: 0 12px; }
        .result-box { gap: 12px; }
        .result-box .card .value { font-size: 22px; }
        .pdrb-grid { gap: 12px; }
        .pdrb-grid .pdrb-item .value { font-size: 18px; }
    }
    @media (max-width: 768px) {
        .iku-page .iku-wrapper { padding: 18px 14px; }
        .iku-header h1 { font-size: 22px; }
        .filter-kategori .btn-kategori { padding: 6px 16px; font-size: 13px; }
        .result-box { grid-template-columns: 1fr; gap: 10px; }
        .result-box .card .value { font-size: 20px; }
        .pdrb-grid { grid-template-columns: 1fr; gap: 8px; }
        .formula-box .formula { font-size: 16px; }
        .sumber-section .sumber-file { flex-direction: column; align-items: stretch; }
        .sumber-section .sumber-file .btn-group { justify-content: center; }
        .sumber-section .sumber-file .btn-group .btn { flex: 1; justify-content: center; }
        .modal-box { max-width: 95%; max-height: 95vh; }
        .modal-box .modal-body .preview-container { height: 35vh; }
        .modal-box .modal-actions { flex-direction: column; }
        .modal-box .modal-actions .btn { width: 100%; justify-content: center; }
        .wisatawan-table-wrapper table { font-size: 10px; min-width: 500px; }
        .ekraf-table-wrapper table { font-size: 10px; min-width: 500px; }
    }
    @media (max-width: 480px) {
        .iku-page .iku-wrapper { padding: 14px 10px; }
        .iku-header h1 { font-size: 18px; }
        .filter-kategori .btn-kategori { padding: 4px 12px; font-size: 12px; }
        .result-box .card .value { font-size: 16px; }
        .pdrb-grid .pdrb-item .value { font-size: 15px; }
        .formula-box .formula { font-size: 14px; }
        .modal-box .modal-body .preview-container { height: 25vh; min-height: 150px; }
        .wisatawan-table-wrapper table { font-size: 9px; min-width: 400px; }
        .ekraf-table-wrapper table { font-size: 9px; min-width: 400px; }
    }
</style>
@endsection

@section('content')
<div class="iku-page">
    <div class="container">
        <div class="iku-wrapper">
            <div class="iku-header">
                <div>
                    <h1><i class="fas fa-chart-line"></i> IKU</h1>
                    <span class="info">Indikator Kinerja Utama</span>
                </div>
                <a href="{{ route('home') }}" class="btn-back-home">
                    <i class="fas fa-arrow-left"></i> Kembali ke Home
                </a>
            </div>

            <div class="iku-subtitle">
                <i class="fas fa-info-circle" style="color:#eab308; margin-right:10px;"></i>
                @if($kategori_aktif == 'Makan Minum')
                Monitoring dan evaluasi capaian kinerja utama Dinas Pariwisata Provinsi Sulawesi Tengah - Sektor Penyediaan Akomodasi dan Makan Minum
                @elseif($kategori_aktif == 'Wisatawan')
                Monitoring dan evaluasi capaian kinerja utama Dinas Pariwisata Provinsi Sulawesi Tengah - Jumlah Tamu Wisatawan Mancanegara (Wisman)
                @elseif($kategori_aktif == 'Ekraf')
                Monitoring dan evaluasi capaian kinerja utama Dinas Pariwisata Provinsi Sulawesi Tengah - Proporsi PDRB Ekraf
                @endif
            </div>

            <!-- Filter Kategori -->
            <div class="filter-kategori">
                @php $icons = ['Makan Minum' => 'fa-utensils', 'Wisatawan' => 'fa-globe-asia', 'Ekraf' => 'fa-palette']; @endphp
                @foreach($kategori_list as $k)
                <a href="{{ route('iku.public', ['kategori' => $k, 'tahun' => $tahun_aktif, 'sub' => $subkategori_wisata]) }}" class="btn-kategori {{ $kategori_aktif == $k ? 'active' : '' }}">
                    <span class="icon"><i class="fas {{ $icons[$k] ?? 'fa-tag' }}"></i></span>
                    {{ $k }}
                </a>
                @endforeach
            </div>

            <!-- Tahun Nav -->
            <div class="tahun-nav">
                @foreach($tahun_list as $t)
                <a href="{{ route('iku.public', ['kategori' => $kategori_aktif, 'tahun' => $t, 'sub' => $subkategori_wisata]) }}" class="btn-tahun {{ $tahun_aktif == $t ? 'active' : '' }}">
                    {{ $t }}
                </a>
                @endforeach
            </div>

            <!-- Sub Wisatawan -->
            @if($kategori_aktif == 'Wisatawan')
            <div class="wisatawan-sub-nav">
                @foreach($subkategori_list as $sub)
                <a href="{{ route('iku.public', ['kategori' => 'Wisatawan', 'tahun' => $tahun_aktif, 'sub' => $sub]) }}" class="btn-sub {{ $subkategori_wisata == $sub ? 'active' : '' }}">
                    {{ $sub }}
                </a>
                @endforeach
            </div>
            @endif

            <!-- Infografis -->
            <div class="infografis-section">
                <div class="infografis-wrapper">
                    @if($infografis_exists && $infografis_file)
                    <img src="{{ asset('uploads/iku/' . $kategori_aktif . '/' . $infografis_file) }}" alt="Infografis IKU {{ $kategori_aktif }}">
                    @else
                    <div class="no-infografis">
                        <i class="fas fa-image"></i>
                        <span>Belum ada infografis yang diupload</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Result Box -->
            <div class="result-box">
                @if($kategori_aktif == 'Ekraf')
                <div class="card">
                    <div class="label">PDRB EKRAF (Miliar)</div>
                    <div class="value gold">{{ $total_ekraf_formatted }}</div>
                </div>
                <div class="card">
                    <div class="label">PDRB ADHB SULTENG (Miliar)</div>
                    <div class="value">{{ $pdrb_adhb_ekraf_display }}</div>
                </div>
                <div class="card">
                    <div class="label">PROPORSI EKRAF</div>
                    <div class="value gold">{{ $proporsi_ekraf_formatted }} <span class="persen">%</span></div>
                </div>
                @elseif($kategori_aktif == 'Wisatawan')
                <div class="card">
                    <div class="label">Wisatawan Nusantara</div>
                    <div class="value">{{ number_format($total_nusantara, 0, ',', '.') }}</div>
                </div>
                <div class="card">
                    <div class="label">Wisatawan Mancanegara</div>
                    <div class="value">{{ number_format($total_mancanegara, 0, ',', '.') }}</div>
                </div>
                <div class="card">
                    <div class="label">TOTAL KUNJUNGAN</div>
                    <div class="value gold">{{ number_format($total_nusantara + $total_mancanegara, 0, ',', '.') }}</div>
                </div>
                @else
                <div class="card">
                    <div class="label">{{ $kriteria[0]['nama_kriteria'] ?? 'Kriteria 1' }} (Miliar)</div>
                    <div class="value gold">{{ $nilai1_formatted }}</div>
                </div>
                <div class="card">
                    <div class="label">{{ $kriteria[1]['nama_kriteria'] ?? 'Kriteria 2' }} (Miliar)</div>
                    <div class="value">{{ $nilai2_formatted }}</div>
                </div>
                <div class="card">
                    <div class="label">KONTRIBUSI</div>
                    <div class="value gold">{{ $hasil_formatted }} <span class="persen">%</span></div>
                </div>
                @endif
            </div>

            <!-- Wisatawan Table -->
            @if($kategori_aktif == 'Wisatawan')
            <div class="wisatawan-table-wrapper">
                <div class="wisatawan-header">
                    <div class="wisatawan-title">
                        <i class="fas fa-users"></i> Data Wisatawan {{ $tahun_aktif }} - {{ $subkategori_wisata }}
                    </div>
                </div>
                <div class="table-scroll" style="overflow-x:auto; padding:0 4px;">
                    @if($subkategori_wisata == 'Akumulasi' && !empty($akumulasi_data))
                    <table>
                        <thead>
                            <tr><th style="text-align:left;">Kab/Kota</th><th style="text-align:right;">Wisnus</th><th style="text-align:right;">Wisman</th><th style="text-align:right; min-width:80px;">TOTAL</th></tr>
                        </thead>
                        <tbody>
                            @php $akumulasi_total = 0; @endphp
                            @foreach($akumulasi_data as $data)
                            @php
                                $nus_total = $data['nusantara'] ? (float) $data['nusantara']['total'] : 0;
                                $man_total = $data['mancanegara'] ? (float) $data['mancanegara']['total'] : 0;
                                $total_kab = $nus_total + $man_total;
                                $akumulasi_total += $total_kab;
                            @endphp
                            <tr>
                                <td>{{ $data['kabkota'] }}</td>
                                <td class="text-right">{{ number_format($nus_total, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($man_total, 0, ',', '.') }}</td>
                                <td class="total-kab">{{ number_format($total_kab, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="total-row">
                                <td>Total</td>
                                <td class="text-right">{{ number_format($total_nusantara, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($total_mancanegara, 0, ',', '.') }}</td>
                                <td class="text-right" style="font-size:16px; color:#dc2626;">{{ number_format($akumulasi_total, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <table>
                        <thead>
                            <tr><th style="text-align:left;">Kab/Kota</th><th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>Mei</th><th>Jun</th><th>Jul</th><th>Ags</th><th>Sep</th><th>Okt</th><th>Nov</th><th>Des</th><th style="min-width:80px;">Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach($wisatawan_data as $w)
                            <tr>
                                <td>{{ $w['kabkota'] }}</td>
                                <td class="text-right">{{ number_format($w['januari'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['februari'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['maret'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['april'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['mei'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['juni'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['juli'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['agustus'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['september'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['oktober'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['november'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($w['desember'], 0, ',', '.') }}</td>
                                <td class="total-kab">{{ number_format($w['total'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="total-row">
                                <td>Total</td>
                                @foreach($bulan_keys as $key)
                                <td class="total-bulan">{{ number_format($total_bulan[$key] ?? 0, 0, ',', '.') }}</td>
                                @endforeach
                                <td class="total-bulan" style="font-size:14px; color:#dc2626;">{{ number_format($total_keseluruhan, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
                <div class="wisatawan-caption"><i class="fas fa-info-circle"></i> Data kunjungan wisatawan per bulan</div>
            </div>

            <!-- PDRB Wisatawan -->
            <div class="pdrb-section">
                <div class="pdrb-title"><i class="fas fa-chart-bar"></i> PDRB Wisatawan</div>
                <div class="pdrb-grid">
                    <div class="pdrb-item"><div class="label">Target Mancanegara</div><div class="value">{{ $target_formatted }}</div></div>
                    <div class="pdrb-item"><div class="label">Realisasi Mancanegara</div><div class="value">{{ number_format($total_mancanegara, 0, ',', '.') }}</div></div>
                    <div class="pdrb-item">
                        <div class="label">Capaian</div>
                        <div class="value">{{ $capaian_formatted }} <span class="persen">%</span></div>
                        <div class="predikat-box {{ $predikat['class'] }}">
                            <i class="fas {{ $predikat['icon'] }} predikat-icon"></i>
                            <span>{{ $predikat['label'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ekraf Table -->
            @if($kategori_aktif == 'Ekraf' && !empty($ekraf_data))
            <div class="ekraf-table-wrapper">
                <div class="ekraf-header"><div class="ekraf-title"><i class="fas fa-calculator"></i> Data Ekraf</div></div>
                <div class="table-scroll" style="overflow-x:auto; padding:0 4px;">
                    <table>
                        <thead><tr><th style="width:35px;">No</th><th style="min-width:200px; text-align:left;">Sektor</th><th style="width:90px;">Koofisien</th><th style="width:130px;">Nilai BPS (Miliar)</th><th style="width:150px;">Jumlah Rp.</th><th style="width:170px;">Hasil Penjumlahan</th></tr></thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($ekraf_data as $e)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $e['sektor'] }}</td>
                                <td class="text-right">{{ number_format($e['koofisien'], 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($e['nilai_bps'], 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($e['jumlah_rp'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($e['hasil_penjumlahan'] / 1000000000, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="total-row"><td colspan="5" style="font-weight:700; color:#0f3b5e; text-align:left; padding-left:8px;">Total PDRB EKRAF (Miliar)</td><td class="text-right" style="font-weight:700; color:#eab308;">{{ $total_ekraf_formatted }}</td></tr>
                            <tr class="total-row"><td colspan="3" style="font-weight:700; color:#0f3b5e; text-align:left; padding-left:8px;">Total PDRB ADHB (Miliar)</td><td class="text-right" style="font-weight:600; color:#0f3b5e;">{{ $pdrb_adhb_ekraf_display }}</td><td></td><td class="text-right" style="font-weight:600; color:#0f3b5e;">{{ $pdrb_adhb_ekraf_display }}</td></tr>
                            <tr class="proporsi-row"><td colspan="5" style="font-weight:700; color:#0f3b5e; text-align:left; padding-left:8px;">PROPORSI</td><td class="proporsi-value">{{ $proporsi_ekraf_formatted }} %</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PDRB Ekraf -->
            <div class="pdrb-section">
                <div class="pdrb-title"><i class="fas fa-chart-bar"></i> PDRB Ekraf</div>
                <div class="pdrb-grid">
                    <div class="pdrb-item"><div class="label">Target</div><div class="value">{{ $target_formatted }} <span class="persen">%</span></div></div>
                    <div class="pdrb-item"><div class="label">Realisasi</div><div class="value">{{ $hasil_formatted }} <span class="persen">%</span></div></div>
                    <div class="pdrb-item">
                        <div class="label">Capaian</div>
                        <div class="value">{{ $capaian_formatted }} <span class="persen">%</span></div>
                        <div class="predikat-box {{ $predikat['class'] }}">
                            <i class="fas {{ $predikat['icon'] }} predikat-icon"></i>
                            <span>{{ $predikat['label'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Makan Minum Table -->
            @if($kategori_aktif == 'Makan Minum')
            <div class="table-wrapper">
                <div class="table-header"><div class="table-title"><i class="fas fa-calculator"></i> Data Perhitungan</div><div class="table-note"><i class="fas fa-info-circle"></i> Angka dalam Miliar Rupiah</div></div>
                <table>
                    <tbody>
                        @foreach($kriteria as $k)
                        <tr><td>{{ $k['nama_kriteria'] }}</td><td class="text-center" style="width:160px;">{{ number_format($k['nilai'], 2, ',', '.') }}</td></tr>
                        @endforeach
                        <tr class="total-row"><td class="text-right"><span class="label-kontribusi">Kontribusi PDRB sektor penyediaan akomodasi dan makan minum terhadap total PDRB</span></td><td class="text-center hasil-persen">{{ $hasil_formatted }} %</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- PDRB Makan Minum -->
            <div class="pdrb-section">
                <div class="pdrb-title"><i class="fas fa-chart-bar"></i> PDRB Makan Minum</div>
                <div class="pdrb-grid">
                    <div class="pdrb-item"><div class="label">Target</div><div class="value">{{ $target_formatted }} <span class="persen">%</span></div></div>
                    <div class="pdrb-item"><div class="label">Realisasi</div><div class="value">{{ $hasil_formatted }} <span class="persen">%</span></div></div>
                    <div class="pdrb-item">
                        <div class="label">Capaian</div>
                        <div class="value">{{ $capaian_formatted }} <span class="persen">%</span></div>
                        <div class="predikat-box {{ $predikat['class'] }}">
                            <i class="fas {{ $predikat['icon'] }} predikat-icon"></i>
                            <span>{{ $predikat['label'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Formula -->
            <div class="formula-box">
                <div class="formula-title"><i class="fas fa-calculator"></i> Formula Perhitungan</div>
                @if($kategori_aktif == 'Makan Minum')
                <div class="formula">R = (PDRB Pariwisata / PDRB Total ADHB) × 100%</div>
                <div class="formula-desc"><strong>Keterangan:</strong><br><strong>R</strong> : Rasio PDRB Penyediaan Akomodasi dan Makan Minum (%)<br><strong>PDRB Pariwisata</strong> : Nilai nominal PDRB gabungan sektor akomodasi dan makan minum (dalam miliar)<br><strong>PDRB Total ADHB</strong> : Nilai nominal total PDRB seluruh kategori lapangan usaha di Sulawesi Tengah (dalam miliar)</div>
                @elseif($kategori_aktif == 'Wisatawan')
                <div class="formula">Jumlah Tamu Wisman = Σ (Wisatawan Mancanegara per Kab/Kota)</div>
                <div class="formula-desc"><strong>Keterangan:</strong><br><strong>Jumlah Tamu Wisman</strong> : Total kunjungan wisatawan mancanegara di seluruh Kabupaten/Kota Sulawesi Tengah<br><strong>Sumber Data</strong> : Data kunjungan wisatawan mancanegara yang menggunakan hotel berbintang dan tidak berbintang di seluruh Kabupaten/Kota Sulawesi Tengah</div>
                @elseif($kategori_aktif == 'Ekraf')
                <div class="formula">Proporsi PDRB EKRAF = (Nilai Tambah Bruto 17 Subsektor Ekraf / PDRB ADHB Sulawesi Tengah) × 100%</div>
                <div class="formula-desc"><strong>Keterangan:</strong><br><strong>Proporsi PDRB EKRAF</strong> : Persentase kontribusi ekonomi kreatif terhadap PDRB Sulawesi Tengah<br><strong>Nilai Tambah Bruto 17 Subsektor Ekraf</strong> : Total nilai tambah dari 17 subsektor ekonomi kreatif (dalam rupiah)<br><strong>PDRB ADHB Sulawesi Tengah</strong> : Total PDRB atas dasar harga berlaku seluruh lapangan usaha di Sulawesi Tengah</div>
                @endif
            </div>

            <!-- Sumber Data -->
            <div class="sumber-section">
                <div class="sumber-title"><i class="fas fa-database"></i> Sumber Data</div>
                @if(!empty($sumber_data['link_sumber']))
                <div class="sumber-link"><i class="fas fa-link"></i><a href="{{ $sumber_data['link_sumber'] }}" target="_blank">{{ $sumber_data['link_sumber'] }}</a></div>
                @endif
                @if(!empty($sumber_data['file_sumber']))
                    @php $files = explode('|', $sumber_data['file_sumber']); @endphp
                    @foreach($files as $file)
                        @if(empty($file)) @continue @endif
                        @php $file_path = public_path('uploads/iku/' . $kategori_aktif . '/' . $file); @endphp
                        @if(file_exists($file_path))
                        <div class="sumber-file">
                            <i class="fas fa-file"></i>
                            <span class="file-name">{{ $file }}</span>
                            <div class="btn-group">
                                <button class="btn btn-view" onclick="openFileModal('{{ asset('uploads/iku/' . $kategori_aktif . '/' . $file) }}')"><i class="fas fa-eye"></i> Lihat</button>
                                <a href="{{ asset('uploads/iku/' . $kategori_aktif . '/' . $file) }}" class="btn btn-download" download><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
                @if(empty($sumber_data['link_sumber']) && empty($sumber_data['file_sumber']))
                <div style="color:#94a3b8; font-size:13px; padding:4px 0;"><i class="fas fa-info-circle"></i> Belum ada sumber data yang diupload</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview File -->
<div class="modal-overlay" id="fileModal">
    <div class="modal-box">
        <div class="modal-header"><h3><i class="fas fa-file"></i> Preview File</h3><button class="modal-close" onclick="closeFileModal()">&times;</button></div>
        <div class="modal-body"><div class="preview-container" id="previewContainer"><div class="no-preview"><i class="fas fa-file"></i><span class="ext">Memuat file...</span></div></div></div>
        <div class="modal-actions">
            <button class="btn btn-close-modal" onclick="closeFileModal()"><i class="fas fa-times"></i> Tutup</button>
            <a href="#" class="btn btn-download-modal" id="modalDownloadBtn" download><i class="fas fa-download"></i> Download</a>
        </div>
    </div>
</div>

<script>
function openFileModal(filePath) {
    var modal = document.getElementById('fileModal');
    var container = document.getElementById('previewContainer');
    var ext = filePath.split('.').pop().toLowerCase();
    var isPDF = ext === 'pdf';
    var isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
    document.getElementById('modalDownloadBtn').href = filePath;

    if (isPDF) {
        container.innerHTML = '<iframe src="' + filePath + '#toolbar=1" style="width:100%; height:100%; border:none;"></iframe>';
    } else if (isImage) {
        container.innerHTML = '<img src="' + filePath + '" style="width:100%; height:100%; object-fit:contain;" alt="Preview">';
    } else {
        container.innerHTML = '<div class="no-preview"><i class="fas fa-file"></i><span class="ext">File ' + ext.toUpperCase() + '</span><p style="font-size:14px; color:#94a3b8; margin-top:8px;">File tidak dapat ditampilkan langsung di browser.</p><p style="font-size:14px; color:#94a3b8;">Silakan download untuk membuka.</p></div>';
    }
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeFileModal() {
    document.getElementById('fileModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('previewContainer').innerHTML = '<div class="no-preview"><i class="fas fa-file"></i><span class="ext">Memuat file...</span></div>';
}

document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeFileModal(); });
document.getElementById('fileModal').addEventListener('click', function(e) { if (e.target === this) closeFileModal(); });
</script>
@endsection