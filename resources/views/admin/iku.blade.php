@extends('layouts.admin')

@section('title', 'Kelola IKU - CEKIDOT')

@section('styles')
<style>
    /* ===== STYLE SAMA DENGAN IKU.PHP ASLI ===== */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
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

    .filter-kategori {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 24px;
        padding: 6px;
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e8ecf1;
        justify-content: center;
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .filter-kategori .btn-kategori {
        padding: 8px 28px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        background: transparent;
        color: #64748b;
        text-decoration: none;
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .filter-kategori .btn-kategori .icon {
        font-size: 16px;
        opacity: 0.5;
        transition: all 0.3s;
    }
    .filter-kategori .btn-kategori:hover {
        color: #0f3b5e;
        background: rgba(15, 59, 94, 0.05);
    }
    .filter-kategori .btn-kategori:hover .icon { opacity: 1; }
    .filter-kategori .btn-kategori.active {
        background: #0f3b5e;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(15, 59, 94, 0.25);
    }
    .filter-kategori .btn-kategori.active .icon {
        opacity: 1;
        color: #eab308;
    }

    .tahun-nav {
        display: flex;
        align-items: center;
        gap: 6px;
        justify-content: center;
        margin-bottom: 16px;
        padding: 6px 12px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e8ecf1;
        flex-wrap: wrap;
    }
    .tahun-nav .btn-tahun {
        padding: 4px 14px;
        border: none;
        border-radius: 6px;
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
    }
    .tahun-nav .tahun-label {
        font-weight: 700;
        color: #0f3b5e;
        font-size: 14px;
        padding: 0 12px;
    }

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
    .wisatawan-sub-nav .btn-sub:hover {
        background: #f1f5f9;
        color: #0f3b5e;
    }
    .wisatawan-sub-nav .btn-sub.active {
        background: #0f3b5e;
        color: #fff;
    }

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

    /* Infografis */
    .infografis-section {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e8ecf1;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .infografis-section .infografis-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f3b5e;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .infografis-section .infografis-title i { color: #eab308; }
    .infografis-section .infografis-title .tahun-label {
        font-size: 12px;
        font-weight: 400;
        color: #94a3b8;
        margin-left: 8px;
    }
    .infografis-section .infografis-title .tahun-label i { color: #94a3b8; }

    .infografis-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
        align-items: start;
    }
    .infografis-preview .preview-wrapper {
        position: relative;
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
    }
    .infografis-preview .preview-wrapper .slide-wrapper {
        position: relative;
        width: 100%;
        padding-top: 56.25%;
        background: #f8fafc;
    }
    .infografis-preview .preview-wrapper .slide-wrapper img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .infografis-preview .preview-wrapper .slide-wrapper .empty-slide {
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
        gap: 4px;
    }
    .infografis-preview .preview-wrapper .slide-wrapper .empty-slide i { font-size: 32px; opacity: 0.3; }
    .infografis-preview .preview-wrapper .slide-wrapper .empty-slide span { font-size: 13px; }
    .infografis-preview .preview-caption {
        text-align: center;
        padding: 6px 0 0;
        font-size: 11px;
        color: #94a3b8;
    }
    .infografis-preview .preview-caption i {
        color: #0f3b5e;
        margin-right: 4px;
    }

    .infografis-panel {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .infografis-panel .status-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e8ecf1;
    }
    .infografis-panel .status-box .status-icon {
        font-size: 20px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .infografis-panel .status-box .status-icon.ada {
        background: #d1fae5;
        color: #16a34a;
    }
    .infografis-panel .status-box .status-icon.tidak {
        background: #f1f5f9;
        color: #94a3b8;
    }
    .infografis-panel .status-box .status-text .status-label {
        font-weight: 600;
        font-size: 14px;
        color: #0f3b5e;
    }
    .infografis-panel .status-box .status-text .status-label.ada { color: #16a34a; }
    .infografis-panel .status-box .status-text .status-label.tidak { color: #94a3b8; }
    .infografis-panel .status-box .status-text .file-name-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
        display: block;
    }

    .infografis-panel .upload-box {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        padding: 12px 16px;
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .infografis-panel .upload-box:hover {
        border-color: #0f3b5e;
        background: #f8fafc;
    }
    .infografis-panel .upload-box .file-upload-wrapper {
        position: relative;
        flex: 1;
        min-width: 160px;
    }
    .infografis-panel .upload-box .file-upload-wrapper input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }
    .infografis-panel .upload-box .file-upload-wrapper .file-label {
        display: block;
        padding: 8px 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #475569;
        font-size: 13px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
    }
    .infografis-panel .upload-box .file-upload-wrapper:hover .file-label {
        border-color: #0f3b5e;
        background: #f1f5f9;
    }
    .infografis-panel .upload-box .file-upload-wrapper .file-label i {
        margin-right: 6px;
        color: #0f3b5e;
    }
    .infografis-panel .upload-box .file-hint {
        font-size: 11px;
        color: #94a3b8;
        white-space: nowrap;
    }
    .infografis-panel .upload-box .file-hint i {
        color: #0f3b5e;
        margin-right: 4px;
    }
    .infografis-panel .upload-box .preview-status {
        font-size: 12px;
        color: #eab308;
        font-weight: 500;
        width: 100%;
        text-align: center;
        padding: 4px 0;
        display: none;
    }
    .infografis-panel .upload-box .preview-status.show { display: block; }
    .infografis-panel .action-box {
        display: flex;
        gap: 12px;
        align-items: center;
        padding: 4px 0;
        justify-content: flex-end;
    }
    .infografis-panel .action-box .btn-delete-icon {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 50%;
        background: #fef2f2;
        color: #991b1b;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        text-decoration: none;
    }
    .infografis-panel .action-box .btn-delete-icon:hover {
        background: #fecaca;
        transform: scale(1.05);
    }
    .infografis-panel .action-box .btn-delete-icon.disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }
    .btn-upload-infografis {
        padding: 6px 24px;
        background: #0f3b5e;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .btn-upload-infografis:hover {
        background: #0a2a44;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15,59,94,0.2);
    }

    .upload-loading {
        display: none;
        align-items: center;
        gap: 10px;
        color: #0f3b5e;
        font-weight: 500;
        font-size: 14px;
        padding: 8px 16px;
        background: #f0f4f8;
        border-radius: 8px;
    }
    .upload-loading.show { display: flex; }
    .upload-loading .spinner {
        width: 20px;
        height: 20px;
        border: 3px solid #e2e8f0;
        border-top: 3px solid #0f3b5e;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .confirm-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .confirm-overlay.show { display: flex; }
    .confirm-box {
        background: #fff;
        border-radius: 16px;
        padding: 30px 35px;
        max-width: 440px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .confirm-box .confirm-icon {
        font-size: 48px;
        color: #eab308;
        margin-bottom: 12px;
    }
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
        padding: 8px 28px;
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
    .confirm-box .confirm-actions .confirm-btn-confirm:hover {
        background: #0a2a44;
        transform: scale(1.02);
    }

    /* Result Box */
    .result-box {
        background: linear-gradient(135deg, #0f3b5e 0%, #1a5276 100%);
        border-radius: 16px;
        padding: 24px 32px;
        margin-bottom: 24px;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        text-align: center;
        color: #ffffff;
        box-shadow: 0 4px 20px rgba(15, 59, 94, 0.25);
    }
    .result-box .item {
        padding: 12px 16px;
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.06);
        transition: all 0.3s ease;
    }
    .result-box .item:hover {
        background: rgba(255,255,255,0.10);
        border-color: rgba(255,255,255,0.12);
        transform: translateY(-2px);
    }
    .result-box .item .label {
        font-size: 11px;
        opacity: 0.7;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .result-box .item .value {
        font-size: 24px;
        font-weight: 800;
        margin-top: 2px;
        word-break: break-all;
        line-height: 1.2;
    }
    .result-box .item .value .persen {
        font-size: 18px;
        font-weight: 400;
        opacity: 0.7;
    }
    .result-box .item .value.gold { color: #eab308; }

    /* Form Section */
    .form-section {
        background: #ffffff;
        padding: 20px 24px;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .form-section .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 6px;
    }
    .form-section .form-header .form-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section .form-header .form-title i { color: #eab308; }
    .form-section .form-header .form-note {
        font-size: 11px;
        color: #94a3b8;
        font-style: italic;
        background: #f1f5f9;
        padding: 3px 14px;
        border-radius: 20px;
    }

    .form-vertical {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .form-group-vertical {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #f8fafc;
        padding: 10px 18px;
        border-radius: 8px;
        border: 1px solid #e8ecf1;
        transition: border-color 0.3s;
    }
    .form-group-vertical:hover { border-color: #0f3b5e; }
    .form-group-vertical label {
        font-weight: 600;
        font-size: 13px;
        color: #1e293b;
        min-width: 200px;
        flex-shrink: 0;
    }
    .form-group-vertical .input-wrapper {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-group-vertical .input-wrapper input {
        flex: 1;
        padding: 6px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        font-family: inherit;
        background: #ffffff;
        transition: border-color 0.3s;
        text-align: right;
    }
    .form-group-vertical .input-wrapper input:focus {
        outline: none;
        border-color: #0f3b5e;
        box-shadow: 0 0 0 3px rgba(15,59,94,0.06);
    }
    .form-group-vertical .input-wrapper .unit {
        font-size: 12px;
        color: #94a3b8;
        min-width: 35px;
    }

    .kontribusi-row {
        background: #f0f4f8;
        padding: 12px 20px;
        border-radius: 8px;
        border: 1.5px solid #0f3b5e;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }
    .kontribusi-row .label-kontribusi {
        font-weight: 600;
        font-size: 13px;
        color: #0f3b5e;
    }
    .kontribusi-row .label-kontribusi i {
        color: #eab308;
        margin-right: 6px;
    }
    .kontribusi-row .value-kontribusi {
        font-size: 22px;
        font-weight: 800;
        color: #eab308;
    }
    .kontribusi-row .value-kontribusi .persen {
        font-size: 16px;
        font-weight: 400;
        color: #94a3b8;
    }

    /* PDRB Section */
    .pdrb-section {
        background: #ffffff;
        padding: 16px 24px;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .pdrb-section .pdrb-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f3b5e;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pdrb-section .pdrb-title i { color: #eab308; }

    .pdrb-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
    }
    .pdrb-grid .form-group { margin-bottom: 0; }
    .pdrb-grid .form-group label {
        font-weight: 600;
        font-size: 12px;
        display: block;
        margin-bottom: 3px;
        color: #1e293b;
    }
    .pdrb-grid .form-group input {
        width: 100%;
        padding: 6px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        font-family: inherit;
        background: #fff;
        transition: border-color 0.3s;
        text-align: right;
    }
    .pdrb-grid .form-group input:focus {
        outline: none;
        border-color: #0f3b5e;
    }
    .pdrb-grid .form-group .input-hint {
        font-size: 10px;
        color: #94a3b8;
        margin-top: 2px;
    }
    .pdrb-grid .form-group input[readonly] {
        background: #f1f5f9;
        cursor: not-allowed;
    }

    .pdrb-grid .form-group .predikat-box {
        margin-top: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        text-align: center;
        transition: all 0.3s;
    }
    .pdrb-grid .form-group .predikat-box .predikat-icon { margin-right: 6px; }
    .pdrb-grid .form-group .predikat-box.istimewa {
        background: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #93c5fd;
    }
    .pdrb-grid .form-group .predikat-box.baik {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #86efac;
    }
    .pdrb-grid .form-group .predikat-box.butuh-perbaikan {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
    }
    .pdrb-grid .form-group .predikat-box.kurang {
        background: #ffedd5;
        color: #9a3412;
        border: 1px solid #fdba74;
    }
    .pdrb-grid .form-group .predikat-box.sangat-kurang {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }
    .pdrb-grid .form-group .predikat-box.belum-ada {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    /* Sumber Data */
    .sumber-section {
        background: #ffffff;
        padding: 16px 24px;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .sumber-section .sumber-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f3b5e;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sumber-section .sumber-title i { color: #eab308; }
    .sumber-section .sumber-title .tahun-label {
        font-size: 12px;
        font-weight: 400;
        color: #94a3b8;
        margin-left: 8px;
    }
    .sumber-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .sumber-section .file-upload-wrapper {
        position: relative;
    }
    .sumber-section .file-upload-wrapper input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }
    .sumber-section .file-upload-wrapper .file-label {
        display: block;
        padding: 8px 14px;
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 6px;
        color: #475569;
        font-size: 12px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
    }
    .sumber-section .file-upload-wrapper:hover .file-label {
        background: #f8fafc;
        border-color: #0f3b5e;
    }
    .sumber-section .file-upload-wrapper .file-label i {
        margin-right: 4px;
        color: #0f3b5e;
    }
    .sumber-section .form-group { margin-bottom: 0; }
    .sumber-section .form-group label {
        font-weight: 600;
        font-size: 12px;
        display: block;
        margin-bottom: 3px;
        color: #1e293b;
    }
    .sumber-section .form-group textarea {
        width: 100%;
        padding: 6px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        font-size: 12px;
        font-family: inherit;
        background: #fff;
        transition: border-color 0.3s;
        resize: vertical;
        min-height: 80px;
    }
    .sumber-section .form-group textarea:focus {
        outline: none;
        border-color: #0f3b5e;
    }
    .sumber-section .input-hint {
        font-size: 10px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .file-list {
        margin-top: 6px;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .file-list .file-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 10px;
        background: #f8fafc;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        font-size: 12px;
    }
    .file-list .file-item i { color: #0f3b5e; font-size: 14px; }
    .file-list .file-item .file-name { flex: 1; color: #1e293b; font-weight: 500; }
    .file-list .file-item .file-size { color: #94a3b8; font-size: 11px; }
    .file-list .file-item .file-status-text { font-size: 10px; color: #16a34a; }

    .file-status-list {
        margin-top: 4px;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .file-status-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 10px;
        background: #f8fafc;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        font-size: 12px;
    }
    .file-status-item .status-icon.ada { color: #16a34a; }
    .file-status-item .status-text { flex: 1; color: #1e293b; }
    .file-status-item .status-text .nama-file { font-weight: 500; color: #0f3b5e; }
    .file-status-item .btn-lihat {
        color: #0f3b5e;
        text-decoration: none;
        padding: 1px 6px;
        border-radius: 4px;
        transition: all 0.3s;
        font-size: 12px;
    }
    .file-status-item .btn-lihat:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .file-status-item .btn-hapus-file {
        color: #991b1b;
        text-decoration: none;
        padding: 1px 6px;
        border-radius: 4px;
        transition: all 0.3s;
        background: #fef2f2;
        border: none;
        cursor: pointer;
        font-size: 12px;
    }
    .file-status-item .btn-hapus-file:hover {
        background: #fecaca;
    }

    /* Wisatawan Table */
    .wisatawan-section {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .wisatawan-section .wisatawan-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .wisatawan-section .wisatawan-header .wisatawan-title {
        font-weight: 700;
        color: #0f3b5e;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .wisatawan-section .wisatawan-header .wisatawan-title i { color: #eab308; }
    .wisatawan-section .wisatawan-header .wisatawan-sub {
        display: flex;
        gap: 6px;
    }
    .wisatawan-section .wisatawan-header .wisatawan-sub .btn-sub {
        padding: 4px 16px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        background: transparent;
        color: #64748b;
        text-decoration: none;
    }
    .wisatawan-section .wisatawan-header .wisatawan-sub .btn-sub:hover {
        background: rgba(15, 59, 94, 0.05);
        color: #0f3b5e;
    }
    .wisatawan-section .wisatawan-header .wisatawan-sub .btn-sub.active {
        background: #0f3b5e;
        color: #ffffff;
    }
    .wisatawan-section .table-scroll {
        overflow-x: auto;
        padding: 0 4px;
    }
    .wisatawan-section table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        min-width: 900px;
    }
    .wisatawan-section table th {
        text-align: center;
        padding: 8px 6px;
        background: #fafbfc;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    .wisatawan-section table th:first-child { text-align: left; min-width: 140px; }
    .wisatawan-section table td {
        padding: 6px 4px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .wisatawan-section table td:first-child {
        font-weight: 500;
        color: #0f3b5e;
        font-size: 11px;
    }
    .wisatawan-section table .wisatawan-input {
        width: 100%;
        padding: 4px 4px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 12px;
        font-family: inherit;
        background: #fff;
        transition: border-color 0.3s;
        text-align: right;
        min-width: 60px;
    }
    .wisatawan-section table .wisatawan-input:focus {
        outline: none;
        border-color: #0f3b5e;
    }
    .wisatawan-section table .wisatawan-input:disabled {
        background: #f8fafc;
        cursor: not-allowed;
    }
    .wisatawan-section table .total-kab {
        font-weight: 700;
        color: #0f3b5e;
        text-align: right;
    }
    .wisatawan-section table .total-bulan {
        font-weight: 700;
        color: #eab308;
        text-align: right;
        background: #fefce8;
    }
    .wisatawan-section table .total-row td {
        padding: 8px 4px;
        background: #f8fafc;
        border-top: 2px solid #e2e8f0;
    }
    .wisatawan-section table .grand-total td {
        padding: 8px 4px;
        background: #fef3c7;
        border-top: 2px solid #eab308;
        font-weight: 700;
    }
    .wisatawan-section table .grand-total .grand-label {
        color: #0f3b5e;
        font-size: 13px;
    }
    .wisatawan-section table .grand-total .grand-value {
        color: #dc2626;
        font-size: 16px;
    }
    .wisatawan-section .wisatawan-caption {
        padding: 10px 20px;
        font-size: 12px;
        color: #94a3b8;
        border-top: 1px solid #e8ecf1;
        background: #fafbfc;
        text-align: right;
    }
    .wisatawan-section .wisatawan-caption i {
        color: #0f3b5e;
        margin-right: 4px;
    }

    /* Ekraf Table */
    .ekraf-table-wrapper {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .ekraf-table-wrapper .ekraf-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .ekraf-table-wrapper .ekraf-header .ekraf-title {
        font-weight: 700;
        color: #0f3b5e;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ekraf-table-wrapper .ekraf-header .ekraf-title i { color: #eab308; }
    .ekraf-table-wrapper .table-scroll {
        overflow-x: auto;
        padding: 0 4px;
    }
    .ekraf-table-wrapper table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 850px;
    }
    .ekraf-table-wrapper table th {
        text-align: center;
        padding: 10px 8px;
        background: #fafbfc;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .ekraf-table-wrapper table td {
        padding: 8px 8px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .ekraf-table-wrapper table tr:last-child td { border-bottom: none; }
    .ekraf-table-wrapper table .text-center { text-align: center; }
    .ekraf-table-wrapper table .text-right { text-align: right; }
    .ekraf-table-wrapper table .text-left { text-align: left; }
    .ekraf-table-wrapper table .sektor-input {
        width: 100%;
        padding: 4px 8px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 13px;
        font-family: inherit;
        background: #fff;
        transition: border-color 0.3s;
        text-align: left;
    }
    .ekraf-table-wrapper table .sektor-input:focus {
        outline: none;
        border-color: #0f3b5e;
    }
    .ekraf-table-wrapper table .num-input {
        width: 100%;
        padding: 4px 8px;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 13px;
        font-family: inherit;
        background: #fff;
        transition: border-color 0.3s;
        text-align: right;
    }
    .ekraf-table-wrapper table .num-input:focus {
        outline: none;
        border-color: #0f3b5e;
    }
    .ekraf-table-wrapper table .value-display {
        font-weight: 500;
        color: #1e293b;
    }
    .ekraf-table-wrapper table .total-row {
        background: #f8fafc;
        font-weight: 700;
        border-top: 2px solid #e2e8f0;
    }
    .ekraf-table-wrapper table .total-row td { padding: 10px 8px; font-size: 14px; }
    .ekraf-table-wrapper table .total-row .total-label {
        color: #0f3b5e;
        font-weight: 700;
        text-align: left;
        padding-left: 8px;
    }
    .ekraf-table-wrapper table .total-row .total-value {
        color: #eab308;
        font-weight: 700;
        text-align: right;
    }
    .ekraf-table-wrapper table .adhb-row td { padding: 10px 8px; }
    .ekraf-table-wrapper table .adhb-row .adhb-label {
        color: #0f3b5e;
        font-weight: 700;
        text-align: left;
        padding-left: 8px;
    }
    .ekraf-table-wrapper table .adhb-row .adhb-value {
        font-weight: 600;
        color: #0f3b5e;
        text-align: right;
    }
    .ekraf-table-wrapper table .proporsi-row {
        background: #fef3c7;
        border-top: 2px solid #eab308;
    }
    .ekraf-table-wrapper table .proporsi-row td { padding: 10px 8px; }
    .ekraf-table-wrapper table .proporsi-row .proporsi-label {
        color: #0f3b5e;
        font-weight: 700;
        text-align: left;
        padding-left: 8px;
    }
    .ekraf-table-wrapper table .proporsi-row .proporsi-value {
        color: #dc2626;
        font-size: 18px;
        font-weight: 700;
        text-align: right;
    }

    .btn-save {
        padding: 10px 40px;
        background: #0f3b5e;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-save:hover {
        background: #0a2a44;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(15,59,94,0.3);
    }
    .btn-save i { margin-right: 8px; }

    @media (max-width: 992px) {
        .infografis-grid { grid-template-columns: 1fr; gap: 16px; }
        .result-box { grid-template-columns: 1fr 1fr; gap: 12px; }
        .result-box .item .value { font-size: 18px; }
        .pdrb-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
        .sumber-row { grid-template-columns: 1fr; gap: 12px; }
        .wisatawan-section table { font-size: 11px; min-width: 700px; }
        .ekraf-table-wrapper table { font-size: 12px; }
    }
    @media (max-width: 768px) {
        .header { flex-direction: column; align-items: flex-start; }
        .filter-kategori { padding: 4px; gap: 4px; border-radius: 10px; }
        .filter-kategori .btn-kategori { padding: 6px 16px; font-size: 13px; }
        .result-box { grid-template-columns: 1fr; gap: 10px; }
        .result-box .item .value { font-size: 16px; }
        .pdrb-grid { grid-template-columns: 1fr; gap: 8px; }
        .wisatawan-section .wisatawan-header { flex-direction: column; align-items: flex-start; }
        .wisatawan-section table { font-size: 10px; min-width: 500px; }
        .wisatawan-section table .wisatawan-input { font-size: 10px; min-width: 40px; }
        .infografis-panel .upload-box { flex-direction: column; align-items: stretch; }
        .form-group-vertical { flex-direction: column; align-items: stretch; gap: 6px; }
        .form-group-vertical label { min-width: auto; }
        .sumber-section .form-group textarea { min-height: 60px; }
    }
    @media (max-width: 480px) {
        .filter-kategori .btn-kategori { padding: 4px 12px; font-size: 12px; }
        .result-box { padding: 16px; }
        .result-box .item .value { font-size: 14px; }
        .wisatawan-section table { font-size: 9px; min-width: 400px; }
        .wisatawan-section table .wisatawan-input { font-size: 9px; min-width: 30px; }
        .wisatawan-section table th { font-size: 8px; }
        .wisatawan-section table td:first-child { font-size: 9px; }
        .ekraf-table-wrapper table { font-size: 11px; min-width: 500px; }
        .ekraf-table-wrapper table th { font-size: 9px; }
        .ekraf-table-wrapper table td { padding: 4px 4px; }
        .pdrb-grid .form-group input { font-size: 12px; padding: 4px 8px; }
        .confirm-box { padding: 20px; }
        .confirm-box .confirm-icon { font-size: 40px; }
        .confirm-box .confirm-title { font-size: 18px; }
        .confirm-box .confirm-text { font-size: 13px; }
        .confirm-box .confirm-actions .confirm-btn { padding: 6px 20px; font-size: 12px; }
    }
</style>
@endsection

@section('content')
@php
    $total_baru = App\Models\SuratMasuk::where('status', 'baru')->count();
    $admin_nama = auth()->user()->nama_admin ?? 'Admin';
    $icons = ['Makan Minum' => 'fa-utensils', 'Wisatawan' => 'fa-globe-asia', 'Ekraf' => 'fa-palette'];
    $kategori_list = ['Makan Minum', 'Wisatawan', 'Ekraf'];
    $subkategori_list = ['Nusantara', 'Mancanegara', 'Akumulasi'];
    $bulan_keys = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    
    // Fungsi getPredikat dari controller akan digunakan di sini
    // Tapi kita gunakan data dari controller
@endphp

<div class="header">
    <div>
        <h1><i class="fas fa-chart-line"></i> Kelola IKU</h1>
        <span class="info">{{ $kategori_aktif == 'Wisatawan' ? 'Kelola data wisatawan per tahun' : 'Kelola data untuk perhitungan IKU' }}</span>
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

<!-- Filter Kategori -->
<div class="filter-kategori">
    @foreach($kategori_list as $k)
    <a href="{{ route('admin.iku.index', ['kategori' => $k, 'tahun' => $tahun_aktif, 'sub' => $subkategori_wisata]) }}" class="btn-kategori {{ $kategori_aktif == $k ? 'active' : '' }}">
        <span class="icon"><i class="fas {{ $icons[$k] ?? 'fa-tag' }}"></i></span>
        {{ $k }}
    </a>
    @endforeach
</div>

<!-- Tahun Nav -->
<div class="tahun-nav">
    @foreach($tahun_list as $t)
    <a href="{{ route('admin.iku.index', ['kategori' => $kategori_aktif, 'tahun' => $t, 'sub' => $subkategori_wisata]) }}" class="btn-tahun {{ $tahun_aktif == $t ? 'active' : '' }}">
        {{ $t }}
    </a>
    @endforeach
</div>

<!-- Sub Wisatawan -->
@if($kategori_aktif == 'Wisatawan')
<div class="wisatawan-sub-nav">
    @foreach($subkategori_list as $sub)
    <a href="{{ route('admin.iku.index', ['kategori' => 'Wisatawan', 'tahun' => $tahun_aktif, 'sub' => $sub]) }}" class="btn-sub {{ $subkategori_wisata == $sub ? 'active' : '' }}">
        {{ $sub }}
    </a>
    @endforeach
</div>
@endif

<!-- Infografis Section -->
<div class="infografis-section">
    <div class="infografis-title">
        <i class="fas fa-image"></i> Infografis {{ $kategori_aktif }}
        @if($kategori_aktif == 'Wisatawan')
        <span class="tahun-label"><i class="fas fa-calendar"></i> {{ $tahun_aktif }}</span>
        @endif
    </div>
    
    <div class="infografis-grid">
        <div class="infografis-preview">
            <div class="preview-wrapper">
                <div class="slide-wrapper" id="previewContainer">
                    @if($infografis_exists && $infografis_path)
                    <img src="{{ asset('storage/uploads/iku/' . $kategori_aktif . '/' . $infografis_file . '?v=' . time()) }}" alt="Infografis IKU" id="previewImage">
                    @else
                    <div class="empty-slide" id="emptyPreview">
                        <i class="fas fa-image"></i>
                        <span>Belum ada infografis</span>
                        <span style="font-size:11px; color:#94a3b8;">Upload gambar di panel sebelah kanan</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="preview-caption">
                <i class="fas fa-info-circle"></i> Ukuran 16:9 (Rekomendasi: 1920 x 1080 px)
            </div>
        </div>
        
        <div class="infografis-panel">
            <div class="status-box" id="statusBox">
                <div class="status-icon {{ $infografis_exists ? 'ada' : 'tidak' }}" id="statusIcon">
                    <i class="fas {{ $infografis_exists ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                </div>
                <div class="status-text">
                    <span class="status-label {{ $infografis_exists ? 'ada' : 'tidak' }}" id="statusLabel">
                        {{ $infografis_exists ? 'Infografis sudah terupload' : 'Belum ada infografis' }}
                    </span>
                    @if($infografis_exists && $infografis_file)
                    <span class="file-name-text" id="fileNameText">
                        <i class="fas fa-file-image"></i> {{ $infografis_file }}
                    </span>
                    @else
                    <span class="file-name-text" id="fileNameText" style="display:none;"></span>
                    @endif
                </div>
            </div>
            
            <div class="upload-box">
                <div class="file-upload-wrapper">
                    <input type="file" name="infografis" id="infografisInput" accept=".jpg,.jpeg,.png,.gif,.webp">
                    <span class="file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih Infografis</span>
                </div>
                <span class="file-hint"><i class="fas fa-info-circle"></i> JPG, PNG, GIF, WEBP | 5MB</span>
                <button type="button" class="btn-upload-infografis" id="uploadInfografisBtn">
                    <i class="fas fa-upload"></i> Upload Infografis
                </button>
                <div class="upload-loading" id="uploadLoading">
                    <div class="spinner"></div>
                    <span>Mengupload...</span>
                </div>
                <div class="preview-status" id="previewStatus">
                    <i class="fas fa-eye"></i> Preview baru siap, klik "Upload Infografis" untuk menyimpan
                </div>
            </div>
            
            <div class="action-box">
                @if($infografis_exists)
                <a href="#" class="btn-delete-icon" id="deleteInfografisBtn" title="Hapus Infografis">
                    <i class="fas fa-trash"></i>
                </a>
                @else
                <a href="#" class="btn-delete-icon disabled" id="deleteInfografisBtn" title="Belum ada infografis untuk dihapus">
                    <i class="fas fa-trash"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Result Box -->
<div class="result-box" id="resultBox">
    @if($kategori_aktif == 'Ekraf')
    <div class="item">
        <div class="label">PDRB EKRAF <span style="font-size:9px; opacity:0.6;">(Miliar)</span></div>
        <div class="value" id="displayPariwisata" style="color: #eab308;">{{ $total_ekraf_formatted }}</div>
    </div>
    <div class="item">
        <div class="label">PDRB ADHB SULTENG <span style="font-size:9px; opacity:0.6;">(Miliar)</span></div>
        <div class="value" id="displayTotal">{{ $pdrb_adhb_ekraf_rp_formatted }}</div>
    </div>
    <div class="item">
        <div class="label">PROPORSI EKRAF</div>
        <div class="value" id="displayHasil" style="color: #eab308;">{{ $proporsi_ekraf_formatted }} <span class="persen">%</span></div>
    </div>
    @elseif($kategori_aktif == 'Wisatawan')
    <div class="item">
        <div class="label">Wisatawan Nusantara</div>
        <div class="value" id="displayPariwisata">{{ number_format($total_nusantara, 0, ',', '.') }}</div>
    </div>
    <div class="item">
        <div class="label">Wisatawan Mancanegara</div>
        <div class="value" id="displayTotal">{{ number_format($total_mancanegara, 0, ',', '.') }}</div>
    </div>
    <div class="item">
        <div class="label">TOTAL KUNJUNGAN</div>
        <div class="value" id="displayHasil" style="color: #eab308;">{{ number_format($total_nusantara + $total_mancanegara, 0, ',', '.') }}</div>
    </div>
    @else
    <div class="item">
        <div class="label">Penyediaan Akomodasi & Mamin <span style="font-size:9px; opacity:0.6;">(Miliar)</span></div>
        <div class="value" id="displayPariwisata" style="color: #eab308;">{{ $nilai1_formatted }}</div>
    </div>
    <div class="item">
        <div class="label">PDRB ADHB SULTENG <span style="font-size:9px; opacity:0.6;">(Miliar)</span></div>
        <div class="value" id="displayTotal">{{ $nilai2_formatted }}</div>
    </div>
    <div class="item">
        <div class="label">KONTRIBUSI</div>
        <div class="value" id="displayHasil" style="color: #eab308;">{{ $hasil_formatted }} <span class="persen">%</span></div>
    </div>
    @endif
</div>

<form method="post" enctype="multipart/form-data" autocomplete="off" id="mainForm" action="{{ route('admin.iku.update') }}">
    @csrf
    @if(! $can_edit)
    <div style="background:#fefce8;border:1px solid #fde047;color:#713f12;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:14px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-eye"></i> Anda dalam mode <strong>view-only</strong>. Data IKU tingkat Dinas hanya dapat diubah oleh Super Admin / Admin Divisi.
    </div>
    @endif
    <input type="hidden" name="kategori" value="{{ $kategori_aktif }}">
    <input type="hidden" name="tahun" value="{{ $tahun_aktif }}">
    <input type="hidden" name="sub" value="{{ $subkategori_wisata }}">

    @if($kategori_aktif == 'Wisatawan')
    <!-- Wisatawan Table -->
    <div class="wisatawan-section">
        <div class="wisatawan-header">
            <div class="wisatawan-title">
                <i class="fas fa-users"></i> Input Data Wisatawan {{ $tahun_aktif }}
            </div>
            <div class="wisatawan-sub">
                @foreach($subkategori_list as $sub)
                <a href="{{ route('admin.iku.index', ['kategori' => 'Wisatawan', 'tahun' => $tahun_aktif, 'sub' => $sub]) }}" class="btn-sub {{ $subkategori_wisata == $sub ? 'active' : '' }}">
                    {{ $sub }}
                </a>
                @endforeach
            </div>
        </div>
        <div class="table-scroll">
            @if($subkategori_wisata == 'Akumulasi' && !empty($akumulasi_data))
            <!-- Akumulasi Table -->
            <table class="akumulasi-table">
                <thead>
                    <tr>
                        <th style="text-align:left;">Kab/Kota</th>
                        <th>Wisatawan Nusantara</th>
                        <th>Wisatawan Mancanegara</th>
                        <th style="min-width:80px;">TOTAL</th>
                    </tr>
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
                        <td style="font-weight:700; color:#0f3b5e;">Total</td>
                        <td class="total-kab" style="text-align:right;">{{ number_format($total_nusantara, 0, ',', '.') }}</td>
                        <td class="total-kab" style="text-align:right;">{{ number_format($total_mancanegara, 0, ',', '.') }}</td>
                        <td class="total-kab" style="text-align:right; font-size:16px; color:#dc2626;">{{ number_format($akumulasi_total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            @else
            <!-- Nusantara / Mancanegara Table -->
            <table>
                <thead>
                    <tr>
                        <th style="text-align:left;">Kab/Kota</th>
                        <th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>Mei</th><th>Jun</th>
                        <th>Jul</th><th>Ags</th><th>Sep</th><th>Okt</th><th>Nov</th><th>Des</th>
                        <th style="min-width:80px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wisatawan_data as $w)
                    <tr>
                        <td>{{ $w['kabkota'] }}</td>
                        @foreach($bulan_keys as $key)
                        <td>
                            <input type="text" name="wisatawan[{{ $w['id'] }}][{{ $key }}]" 
                                   value="{{ number_format($w[$key], 0, ',', '.') }}" 
                                   class="wisatawan-input wisata-bulan" 
                                   data-id="{{ $w['id'] }}" data-bulan="{{ $key }}">
                        </td>
                        @endforeach
                        <td class="total-kab" data-total-kab="{{ $w['id'] }}">{{ number_format($w['total'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td style="font-weight:700; color:#0f3b5e;">Total</td>
                        @foreach($bulan_keys as $key)
                        <td class="total-bulan" data-total-bulan="{{ $key }}">{{ number_format($total_bulan[$key] ?? 0, 0, ',', '.') }}</td>
                        @endforeach
                        <td class="total-bulan" style="font-size:14px; color:#dc2626;" data-grand-total>{{ number_format($total_keseluruhan, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            @endif
        </div>
        <div class="wisatawan-caption">
            <i class="fas fa-edit"></i> Input angka kunjungan wisatawan per bulan (tanpa tanda pemisah)
        </div>
    </div>

    <!-- PDRB Wisatawan -->
    <div class="pdrb-section">
        <div class="pdrb-title">
            <i class="fas fa-chart-bar"></i> PDRB Wisatawan
        </div>
        <div class="pdrb-grid" style="grid-template-columns: 1fr 1fr 1fr;">
            <div class="form-group">
                <label>Target Mancanegara</label>
                <input type="text" name="target" id="wisatawanTarget" placeholder="0" value="{{ isset($pdrb_data->target) ? number_format($pdrb_data->target, 0, ',', '.') : '0' }}">
                <div class="input-hint">Jumlah orang</div>
            </div>
            <div class="form-group">
                <label>Realisasi Mancanegara</label>
                <input type="text" name="realitas" id="realitasWisatawan" placeholder="0" value="{{ number_format($total_mancanegara, 0, ',', '.') }}" readonly style="background:#f1f5f9; cursor:not-allowed;">
                <div class="input-hint">Total kunjungan mancanegara (otomatis)</div>
            </div>
            <div class="form-group">
                <label>Capaian <span style="color:#ef4444;">*</span></label>
                <input type="text" name="capaian" id="wisatawanCapaian" placeholder="0,000" value="{{ $capaian_formatted }}" readonly style="background:#f1f5f9; cursor:not-allowed;">
                <div class="input-hint">Otomatis dari (Realisasi / Target) × 100%</div>
                <div class="predikat-box {{ $predikat['class'] }}" id="predikatWisatawan">
                    <i class="fas {{ $predikat['icon'] }} predikat-icon"></i>
                    <span>{{ $predikat['label'] }}</span>
                </div>
            </div>
        </div>
    </div>

    @elseif($kategori_aktif == 'Ekraf')

    <!-- Ekraf Table -->
    <input type="hidden" name="ekraf_count" value="{{ count($ekraf_data) }}">
    <div class="ekraf-table-wrapper">
        <div class="ekraf-header">
            <div class="ekraf-title">
                <i class="fas fa-calculator"></i> Input Data Ekraf
            </div>
        </div>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th style="width:35px;">No</th>
                        <th style="min-width:220px; text-align:left;">Sektor</th>
                        <th style="width:100px;">Koofisien</th>
                        <th style="width:140px;">Nilai BPS (Miliar)</th>
                        <th style="width:170px;">Jumlah Rp.</th>
                        <th style="width:190px;">Hasil Penjumlahan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($ekraf_data as $index => $e)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>
                            <input type="text" name="ekraf[{{ $index }}][sektor]" value="{{ $e['sektor'] }}" class="sektor-input" placeholder="Nama sektor">
                        </td>
                        <td>
                            <input type="text" name="ekraf[{{ $index }}][koofisien]" value="{{ number_format($e['koofisien'], 2, ',', '.') }}" class="num-input" data-koofisien>
                        </td>
                        <td>
                            <input type="text" name="ekraf[{{ $index }}][nilai_bps]" value="{{ number_format($e['nilai_bps'], 2, ',', '.') }}" class="num-input" data-nilai-bps>
                        </td>
                        <td class="text-right value-display" data-jumlah>{{ number_format($e['jumlah_rp'], 0, ',', '.') }}</td>
                        <td class="text-right value-display" data-hasil>{{ number_format($e['hasil_penjumlahan'] / 1000000000, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="5" class="total-label">Total PDRB EKRAF (Milliar)</td>
                        <td class="total-value" data-total-ekraf>{{ $total_ekraf_formatted }}</td>
                    </tr>
                    <tr class="adhb-row">
                        <td colspan="3" class="adhb-label">PDRB ADHB Sulawesi Tengah (Milliar)</td>
                        <td>
                            <input type="text" name="pdrb_adhb_ekraf" id="pdrbAdhbEkraf" value="{{ $pdrb_adhb_ekraf_rp_formatted }}" class="num-input" style="max-width:150px;" placeholder="0,00">
                        </td>
                        <td></td>
                        <td class="adhb-value" data-adhb-rp>{{ $pdrb_adhb_ekraf_rp_formatted }}</td>
                    </tr>
                    <tr class="proporsi-row">
                        <td colspan="5" class="proporsi-label">PROPORSI</td>
                        <td class="proporsi-value" data-proporsi>{{ $proporsi_ekraf_formatted }} %</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- PDRB Ekraf -->
    <div class="pdrb-section">
        <div class="pdrb-title">
            <i class="fas fa-chart-bar"></i> PDRB Ekraf
        </div>
        <div class="pdrb-grid">
            <div class="form-group">
                <label>Target <span style="color:#ef4444;">*</span></label>
                <input type="text" name="target" id="ekrafTarget" placeholder="0,00" value="{{ $target_formatted }}">
                <div class="input-hint">Persen (%)</div>
            </div>
            <div class="form-group">
                <label>Realisasi <span style="color:#ef4444;">*</span></label>
                <input type="text" name="realitas" id="ekrafRealisasi" placeholder="0,0000" value="{{ $proporsi_ekraf_formatted }}" readonly>
                <div class="input-hint">Otomatis dari Proporsi (%)</div>
            </div>
            <div class="form-group">
                <label>Capaian <span style="color:#ef4444;">*</span></label>
                <input type="text" name="capaian" id="ekrafCapaian" placeholder="0,000" value="{{ $capaian_formatted }}" readonly style="background:#f1f5f9; cursor:not-allowed;">
                <div class="input-hint">Otomatis dari (Realisasi / Target) × 100%</div>
                <div class="predikat-box {{ $predikat['class'] }}" id="predikatEkraf">
                    <i class="fas {{ $predikat['icon'] }} predikat-icon"></i>
                    <span>{{ $predikat['label'] }}</span>
                </div>
            </div>
        </div>
    </div>

    @else

    <!-- Makan Minum -->
    <div class="form-section">
        <div class="form-header">
            <div class="form-title">
                <i class="fas fa-edit"></i> Input Data PDRB
            </div>
            <div class="form-note">
                <i class="fas fa-info-circle"></i> Angka dalam Miliar Rupiah
            </div>
        </div>
        
        <div class="form-vertical">
            @foreach($kriteria as $k)
            <div class="form-group-vertical">
                <label>{{ $k['nama_kriteria'] }}</label>
                <div class="input-wrapper">
                    <input type="text" name="nilai[{{ $k['id'] }}]" value="{{ number_format($k['nilai'], 2, ',', '.') }}" placeholder="0,0000" class="input-nilai" autocomplete="off">
                    <span class="unit">Miliar</span>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="kontribusi-row">
            <span class="label-kontribusi">
                <i class="fas fa-calculator"></i> Kontribusi PDRB sektor penyediaan akomodasi dan makan minum terhadap total PDRB
            </span>
            <span class="value-kontribusi" id="kontribusiDisplay">
                {{ $hasil_formatted }} <span class="persen">%</span>
            </span>
        </div>
    </div>

    <!-- PDRB Makan Minum -->
    <div class="pdrb-section">
        <div class="pdrb-title">
            <i class="fas fa-chart-bar"></i> PDRB Makan Minum
        </div>
        <div class="pdrb-grid">
            <div class="form-group">
                <label>Target <span style="color:#ef4444;">*</span></label>
                <input type="text" name="target" id="pdrbTarget" placeholder="0,00" value="{{ $target_formatted }}">
                <div class="input-hint">Persen (%)</div>
            </div>
            <div class="form-group">
                <label>Realisasi <span style="color:#ef4444;">*</span></label>
                <input type="text" name="realitas" id="pdrbRealisasi" placeholder="0,0000" value="{{ $hasil_formatted }}" readonly>
                <div class="input-hint">Otomatis dari Kontribusi (%)</div>
            </div>
            <div class="form-group">
                <label>Capaian <span style="color:#ef4444;">*</span></label>
                <input type="text" name="capaian" id="pdrbCapaian" placeholder="0,00" value="{{ $capaian_formatted }}" readonly style="background:#f1f5f9; cursor:not-allowed;">
                <div class="input-hint">Otomatis dari (Realisasi / Target) × 100%</div>
                <div class="predikat-box {{ $predikat['class'] }}" id="predikatMakanMinum">
                    <i class="fas {{ $predikat['icon'] }} predikat-icon"></i>
                    <span>{{ $predikat['label'] }}</span>
                </div>
            </div>
        </div>
    </div>

    @endif

    <!-- Sumber Data -->
    <div class="sumber-section">
        <div class="sumber-title">
            <i class="fas fa-database"></i> Sumber Data
            @if($kategori_aktif == 'Wisatawan')
            <span class="tahun-label"><i class="fas fa-calendar"></i> {{ $tahun_aktif }}</span>
            @endif
        </div>
        
        <div class="sumber-row">
            <div class="form-group">
                <label>Link Sumber (Maks 5, pisahkan dengan enter)</label>
                <textarea name="link_sumber" placeholder="https://example.com/sumber-data&#10;https://example.com/sumber-data-2">{{ $sumber_data['link_sumber'] ?? '' }}</textarea>
                <div class="input-hint">Link referensi sumber data, maksimal 5 link</div>
            </div>
            <div class="form-group">
                <label>Upload File (Maks 15 file)</label>
                <div class="file-upload-wrapper">
                    <input type="file" name="file_sumber[]" id="fileSumberInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" multiple>
                    <span class="file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih File</span>
                </div>
                <div class="input-hint">PDF, DOC, XLS, JPG, PNG | Max 10MB/file | Maks 15 file</div>
                
                <div class="file-list" id="filePreviewList"></div>
                
                @if(!empty($sumber_data['file_sumber']))
                <div class="file-status-list">
                    @php $files = explode('|', $sumber_data['file_sumber']); @endphp
                    @foreach($files as $f)
                        @if(empty($f)) @continue @endif
                    <div class="file-status-item" id="file-{{ md5($f) }}">
                        <span class="status-icon ada"><i class="fas fa-check-circle"></i></span>
                        <span class="status-text">
                            <span class="nama-file">{{ $f }}</span>
                        </span>
                        <a href="{{ asset('storage/uploads/iku/' . $kategori_aktif . '/' . $f) }}" target="_blank" class="btn-lihat" title="Lihat File">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.iku.delete.file', ['filename' => $f, 'kategori' => $kategori_aktif, 'tahun' => $tahun_aktif, 'sub' => $subkategori_wisata]) }}" 
                           class="btn-hapus-file" 
                           onclick="return confirm('Yakin hapus file ini?')"
                           title="Hapus File">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <div style="margin-top:16px; text-align:right;">
        @if($can_edit)
        <button type="submit" name="update" value="1" class="btn-save" id="btnSave"><i class="fas fa-save"></i> Simpan Perubahan</button>
        @endif
    </div>
</form>

<!-- Confirm Popup -->
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-save"></i></div>
        <div class="confirm-title">Simpan Perubahan?</div>
        <div class="confirm-text">Apakah Anda yakin ingin menyimpan semua perubahan yang telah dilakukan?</div>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="confirmCancel">Batal</button>
            <button class="confirm-btn confirm-btn-confirm" id="confirmConfirm">Ya, Simpan</button>
        </div>
    </div>
</div>

<!-- Swal Alert untuk Hapus Infografis -->
<div class="confirm-overlay" id="swalOverlay" style="z-index: 99999;">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-exclamation-triangle" style="color:#dc2626;"></i></div>
        <div class="confirm-title">Hapus Infografis?</div>
        <div class="confirm-text">Apakah Anda yakin ingin menghapus infografis ini? Tindakan ini tidak dapat dibatalkan.</div>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="swalCancel">Batal</button>
            <button class="confirm-btn confirm-btn-confirm" id="swalConfirm" style="background:#dc2626;">Hapus</button>
        </div>
    </div>
</div>

<script>
// ===== CONFIRM SAVE =====
var confirmOverlay = document.getElementById('confirmOverlay');
var confirmCancel = document.getElementById('confirmCancel');
var confirmConfirm = document.getElementById('confirmConfirm');
var mainForm = document.getElementById('mainForm');
var saveBtn = document.getElementById('btnSave');
var formSubmitted = false;

if (saveBtn) {
    saveBtn.addEventListener('click', function(e) {
        if (!formSubmitted) {
            e.preventDefault();
            confirmOverlay.classList.add('show');
        }
    });
}

if (confirmCancel) {
    confirmCancel.addEventListener('click', function() {
        confirmOverlay.classList.remove('show');
    });
}

if (confirmConfirm) {
    confirmConfirm.addEventListener('click', function() {
        confirmOverlay.classList.remove('show');
        formSubmitted = true;
        mainForm.submit();
    });
}

if (confirmOverlay) {
    confirmOverlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
        }
    });
}

// ===== WISATAWAN - PERHITUNGAN REAL-TIME =====
function hitungWisatawan() {
    var rows = document.querySelectorAll('.wisatawan-section table tbody tr');
    var bulanKeys = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    var totalPerBulan = {};
    var grandTotal = 0;
    
    bulanKeys.forEach(function(key) {
        totalPerBulan[key] = 0;
    });
    
    rows.forEach(function(row) {
        if (row.classList.contains('total-row')) return;
        
        var inputs = row.querySelectorAll('.wisata-bulan');
        var totalKab = 0;
        
        inputs.forEach(function(input) {
            if (input.disabled) return;
            var val = input.value.replace(/\./g, '').replace(',', '.') || '0';
            var num = parseFloat(val) || 0;
            totalKab += num;
            
            var bulan = input.dataset.bulan;
            if (bulan && totalPerBulan[bulan] !== undefined) {
                totalPerBulan[bulan] += num;
            }
        });
        
        var totalTd = row.querySelector('[data-total-kab]');
        if (totalTd) {
            totalTd.textContent = totalKab.toLocaleString('id-ID');
            totalTd.dataset.total = totalKab;
        }
        
        grandTotal += totalKab;
    });
    
    // Update total per bulan
    bulanKeys.forEach(function(key) {
        var td = document.querySelector('[data-total-bulan="' + key + '"]');
        if (td) {
            td.textContent = totalPerBulan[key].toLocaleString('id-ID');
        }
    });
    
    var grandTd = document.querySelector('[data-grand-total]');
    if (grandTd) {
        grandTd.textContent = grandTotal.toLocaleString('id-ID');
    }
    
    // Update Result Box - Wisatawan
    var subkategori = '{{ $subkategori_wisata }}';
    var displayPariwisata = document.getElementById('displayPariwisata');
    var displayTotal = document.getElementById('displayTotal');
    var displayHasil = document.getElementById('displayHasil');
    var realisasiInput = document.getElementById('realitasWisatawan');

    var totalNusantaraDb = {{ $total_nusantara }};
    var totalMancanegaraDb = {{ $total_mancanegara }};

    var wisnus = 0;
    var wisman = 0;

    if (subkategori === 'Nusantara') {
        wisnus = grandTotal;
        wisman = totalMancanegaraDb;
    } else if (subkategori === 'Mancanegara') {
        wisnus = totalNusantaraDb;
        wisman = grandTotal;
    } else if (subkategori === 'Akumulasi') {
        var akumulasiRows = document.querySelectorAll('.akumulasi-table tbody tr:not(.total-row)');
        var nusAkum = 0;
        var manAkum = 0;
        akumulasiRows.forEach(function(row) {
            var tds = row.querySelectorAll('td');
            if (tds.length >= 3) {
                var nusVal = parseFloat(tds[1].textContent.replace(/\./g, '').replace(',', '.')) || 0;
                var manVal = parseFloat(tds[2].textContent.replace(/\./g, '').replace(',', '.')) || 0;
                nusAkum += nusVal;
                manAkum += manVal;
            }
        });
        wisnus = nusAkum;
        wisman = manAkum;
    }

    if (displayPariwisata) {
        displayPariwisata.textContent = wisnus.toLocaleString('id-ID');
    }
    if (displayTotal) {
        displayTotal.textContent = wisman.toLocaleString('id-ID');
    }
    if (displayHasil) {
        var totalKeseluruhan = wisnus + wisman;
        displayHasil.textContent = totalKeseluruhan.toLocaleString('id-ID');
    }
    if (realisasiInput) {
        realisasiInput.value = wisman.toLocaleString('id-ID');
    }

    // Hitung Capaian Wisatawan
    var targetInput = document.getElementById('wisatawanTarget');
    var capaianInput = document.getElementById('wisatawanCapaian');
    
    if (targetInput && capaianInput && realisasiInput) {
        var targetVal = targetInput.value.trim() || '0';
        var target = parseFloat(targetVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        var realisasiVal = realisasiInput.value.trim() || '0';
        var realisasi = parseFloat(realisasiVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        var capaian = 0;
        if (target > 0) {
            capaian = (realisasi / target) * 100;
            capaian = Math.round(capaian * 1000) / 1000;
        }
        
        var capaianFormatted = capaian.toFixed(3).replace('.', ',');
        capaianInput.value = capaianFormatted;

        updatePredikat(capaianFormatted, 'predikatWisatawan');
    }
}

// ===== FUNGSI: HITUNG EKRAF =====
function hitungEkraf() {
    var total = 0;
    var rows = document.querySelectorAll('.ekraf-table-wrapper table tbody tr');
    
    rows.forEach(function(row) {
        if (row.classList.contains('total-row') || row.classList.contains('adhb-row') || row.classList.contains('proporsi-row')) {
            return;
        }
        
        var koofisienInput = row.querySelector('[data-koofisien]');
        var nilaiBpsInput = row.querySelector('[data-nilai-bps]');
        var jumlahTd = row.querySelector('[data-jumlah]');
        var hasilTd = row.querySelector('[data-hasil]');
        
        if (koofisienInput && nilaiBpsInput && jumlahTd && hasilTd) {
            var koofisienVal = koofisienInput.value || '0';
            var nilaiBpsVal = nilaiBpsInput.value || '0';
            
            var koofisien = parseFloat(koofisienVal.replace(/\./g, '').replace(',', '.')) || 0;
            var nilaiBps = parseFloat(nilaiBpsVal.replace(/\./g, '').replace(',', '.')) || 0;
            
            var jumlahRp = nilaiBps * 1000000000;
            var hasilPenjumlahan = jumlahRp * koofisien;
            
            jumlahTd.textContent = jumlahRp.toLocaleString('id-ID');
            hasilTd.textContent = (hasilPenjumlahan / 1000000000).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            total += hasilPenjumlahan;
        }
    });
    
    // Update Total Ekraf
    var totalEkrafEl = document.querySelector('[data-total-ekraf]');
    if (totalEkrafEl) {
        var totalMiliar = total / 1000000000;
        totalEkrafEl.textContent = totalMiliar.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    
    // Update Result Box - PDRB Ekraf
    var displayPariwisata = document.getElementById('displayPariwisata');
    if (displayPariwisata) {
        var totalMiliar = total / 1000000000;
        displayPariwisata.textContent = totalMiliar.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    
    // Update PDRB ADHB
    var adhbInput = document.getElementById('pdrbAdhbEkraf');
    var adhbRpTd = document.querySelector('[data-adhb-rp]');
    var displayTotal = document.getElementById('displayTotal');
    
    if (adhbInput && adhbRpTd) {
        var adhbVal = adhbInput.value || '0';
        var adhb = parseFloat(adhbVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        adhbRpTd.textContent = adhb.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        if (displayTotal) {
            displayTotal.textContent = adhb.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        // Hitung Proporsi
        var proporsiTd = document.querySelector('[data-proporsi]');
        var displayHasil = document.getElementById('displayHasil');
        var realisasiInput = document.getElementById('ekrafRealisasi');

        if (proporsiTd) {
            var proporsi = 0;
            var adhbRp = adhb * 1000000000;
            if (adhbRp > 0 && total > 0) {
                proporsi = (total / adhbRp) * 100;
            }
            proporsiTd.textContent = proporsi.toFixed(3).replace('.', ',') + ' %';
            
            if (displayHasil) {
                displayHasil.innerHTML = proporsi.toFixed(3).replace('.', ',') + ' <span class="persen">%</span>';
            }
            
            if (realisasiInput) {
                realisasiInput.value = proporsi.toFixed(4).replace('.', ',');
            }
            
            hitungCapaianEkrafOtomatis();
        }
    }
}

// ===== FUNGSI: HITUNG CAPAIAN EKRAF =====
function hitungCapaianEkrafOtomatis() {
    var targetInput = document.getElementById('ekrafTarget');
    var capaianInput = document.getElementById('ekrafCapaian');
    var realisasiInput = document.getElementById('ekrafRealisasi');
    
    if (!targetInput || !capaianInput) return;
    
    var targetVal = targetInput.value || '0';
    var target = parseFloat(targetVal.replace(/\./g, '').replace(',', '.')) || 0;
    
    var realisasiVal = realisasiInput ? realisasiInput.value || '0' : '0';
    var realisasi = parseFloat(realisasiVal.replace(/\./g, '').replace(',', '.')) || 0;
    
    var capaian = 0;
    if (target > 0) {
        capaian = (realisasi / target) * 100;
        capaian = Math.round(capaian * 1000) / 1000;
    }
    
    var capaianFormatted = capaian.toFixed(2).replace('.', ',');
    capaianInput.value = capaianFormatted;

    updatePredikat(capaianFormatted, 'predikatEkraf');
}

// ===== MAKAN MINUM =====
function hitungOtomatis() {
    var inputs = document.querySelectorAll('.input-nilai');
    if (inputs.length < 2) return;
    
    var raw1 = inputs[0]?.value || '0';
    var raw2 = inputs[1]?.value || '0';
    
    var nilai1 = parseFloat(raw1.replace(/\./g, '').replace(',', '.')) || 0;
    var nilai2 = parseFloat(raw2.replace(/\./g, '').replace(',', '.')) || 0;
    
    var hasil = 0;
    if (nilai2 > 0) {
        hasil = (nilai1 / nilai2) * 100;
    }
    
    var displayPariwisata = document.getElementById('displayPariwisata');
    var displayTotal = document.getElementById('displayTotal');
    var displayHasil = document.getElementById('displayHasil');
    var kontribusiDisplay = document.getElementById('kontribusiDisplay');
    var realisasiInput = document.getElementById('pdrbRealisasi');
    var capaianInput = document.getElementById('pdrbCapaian');
    var targetInput = document.getElementById('pdrbTarget');
    
    var nilai1Formatted = nilai1.toFixed(2).replace('.', ',');
    var parts1 = nilai1Formatted.split(',');
    parts1[0] = parts1[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    nilai1Formatted = parts1.join(',');
    
    var nilai2Formatted = nilai2.toFixed(2).replace('.', ',');
    var parts2 = nilai2Formatted.split(',');
    parts2[0] = parts2[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    nilai2Formatted = parts2.join(',');
    
    if (displayPariwisata) {
        displayPariwisata.textContent = nilai1Formatted;
    }
    if (displayTotal) {
        displayTotal.textContent = nilai2Formatted;
    }
    
    if (displayHasil) {
        var hasilDisplay = hasil.toFixed(4).replace('.', ',');
        var parts3 = hasilDisplay.split(',');
        parts3[0] = parts3[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        displayHasil.innerHTML = parts3.join(',') + ' <span class="persen">%</span>';
    }
    
    if (kontribusiDisplay) {
        var kontribusiFormatted = hasil.toFixed(4).replace('.', ',');
        var partsK = kontribusiFormatted.split(',');
        partsK[0] = partsK[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        kontribusiDisplay.innerHTML = partsK.join(',') + ' <span class="persen">%</span>';
    }
    
    if (realisasiInput) {
        realisasiInput.value = hasil.toFixed(4).replace('.', ',');
    }
    
    if (capaianInput && targetInput) {
        var targetVal = targetInput.value || '0';
        var target = parseFloat(targetVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        var capaian = 0;
        if (target > 0) {
            capaian = (hasil / target) * 100;
            capaian = Math.round(capaian * 100) / 100;
        }
        
        var capaianFormatted = capaian.toFixed(2).replace('.', ',');
        capaianInput.value = capaianFormatted;
        
        updatePredikat(capaianFormatted, 'predikatMakanMinum');
    }
}

// ===== FUNGSI UPDATE PREDIKAT =====
function updatePredikat(capaianValue, predikatElementId) {
    var predikatEl = document.getElementById(predikatElementId);
    if (!predikatEl) return;
    
    var capaian = parseFloat(capaianValue.replace(/\./g, '').replace(',', '.')) || 0;
    var label = '', className = '', icon = '';
    
    if (capaian > 100) {
        label = 'ISTIMEWA'; className = 'istimewa'; icon = 'fa-star';
    } else if (capaian >= 80) {
        label = 'BAIK'; className = 'baik'; icon = 'fa-check-circle';
    } else if (capaian >= 60) {
        label = 'BUTUH PERBAIKAN'; className = 'butuh-perbaikan'; icon = 'fa-exclamation-triangle';
    } else if (capaian >= 20) {
        label = 'KURANG'; className = 'kurang'; icon = 'fa-times-circle';
    } else if (capaian > 0) {
        label = 'SANGAT KURANG'; className = 'sangat-kurang'; icon = 'fa-exclamation-circle';
    } else {
        label = 'BELUM ADA'; className = 'belum-ada'; icon = 'fa-minus-circle';
    }
    
    predikatEl.className = 'predikat-box ' + className;
    predikatEl.innerHTML = '<i class="fas ' + icon + ' predikat-icon"></i><span>' + label + '</span>';
}

// ===== EVENT LISTENERS =====
document.addEventListener('DOMContentLoaded', function() {
    // Wisatawan
    if (document.querySelector('.wisata-bulan')) {
        document.querySelectorAll('.wisata-bulan').forEach(function(el) {
            el.addEventListener('input', function() { hitungWisatawan(); });
            el.addEventListener('change', function() { hitungWisatawan(); });
        });
        
        var wisatawanTarget = document.getElementById('wisatawanTarget');
        if (wisatawanTarget) {
            wisatawanTarget.addEventListener('input', function() { hitungWisatawan(); });
            wisatawanTarget.addEventListener('change', function() { hitungWisatawan(); });
        }
        
        hitungWisatawan();
    }
    
    // Ekraf
    if (document.querySelector('[data-koofisien]')) {
        document.querySelectorAll('[data-koofisien], [data-nilai-bps], #pdrbAdhbEkraf, #ekrafTarget').forEach(function(el) {
            el.addEventListener('input', function() { hitungEkraf(); });
            el.addEventListener('change', function() { hitungEkraf(); });
        });
        hitungEkraf();
    }
    
    var ekrafTarget = document.getElementById('ekrafTarget');
    if (ekrafTarget) {
        ekrafTarget.addEventListener('input', function() { hitungCapaianEkrafOtomatis(); });
        ekrafTarget.addEventListener('change', function() { hitungCapaianEkrafOtomatis(); });
    }
    
    // Makan Minum
    if (document.querySelector('.input-nilai')) {
        document.querySelectorAll('.input-nilai').forEach(function(el) {
            el.addEventListener('input', function() { hitungOtomatis(); });
            el.addEventListener('change', function() { hitungOtomatis(); });
        });
        hitungOtomatis();
    }
    
    var pdrbTarget = document.getElementById('pdrbTarget');
    if (pdrbTarget) {
        pdrbTarget.addEventListener('input', function() { hitungOtomatis(); });
        pdrbTarget.addEventListener('change', function() { hitungOtomatis(); });
    }
    
    // Update predikat awal
    var capaianMamin = document.getElementById('pdrbCapaian');
    if (capaianMamin) {
        updatePredikat(capaianMamin.value, 'predikatMakanMinum');
    }
    var capaianWisatawan = document.getElementById('wisatawanCapaian');
    if (capaianWisatawan) {
        updatePredikat(capaianWisatawan.value, 'predikatWisatawan');
    }
    var capaianEkraf = document.getElementById('ekrafCapaian');
    if (capaianEkraf) {
        updatePredikat(capaianEkraf.value, 'predikatEkraf');
    }
});

// ============================================================
// INFOGRAFIS UPLOAD
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    var infografisInput = document.getElementById('infografisInput');
    var previewImage = document.getElementById('previewImage');
    var emptyPreview = document.getElementById('emptyPreview');
    var previewContainer = document.getElementById('previewContainer');
    var previewStatus = document.getElementById('previewStatus');
    var statusIcon = document.getElementById('statusIcon');
    var statusLabel = document.getElementById('statusLabel');
    var fileNameText = document.getElementById('fileNameText');
    var deleteBtn = document.getElementById('deleteInfografisBtn');
    var uploadBtn = document.getElementById('uploadInfografisBtn');
    var uploadLoading = document.getElementById('uploadLoading');
    var kategoriAktif = '{{ $kategori_aktif }}';
    var tahunAktif = '{{ $tahun_aktif }}';
    var subAktif = '{{ $subkategori_wisata }}';
    
    function updateStatusUploaded(fileName, filePath) {
        if (emptyPreview) {
            emptyPreview.style.display = 'none';
        }
        if (previewImage) {
            previewImage.src = filePath + '?v=' + Date.now();
            previewImage.style.display = 'block';
        } else {
            var img = document.createElement('img');
            img.id = 'previewImage';
            img.src = filePath + '?v=' + Date.now();
            img.alt = 'Infografis IKU';
            previewContainer.appendChild(img);
        }
        if (statusIcon) {
            statusIcon.className = 'status-icon ada';
            statusIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
        }
        if (statusLabel) {
            statusLabel.className = 'status-label ada';
            statusLabel.textContent = 'Infografis sudah terupload';
        }
        if (fileNameText) {
            fileNameText.style.display = 'block';
            fileNameText.innerHTML = '<i class="fas fa-file-image"></i> ' + fileName;
        }
        if (previewStatus) {
            previewStatus.classList.remove('show');
        }
        if (deleteBtn) {
            deleteBtn.className = 'btn-delete-icon';
            deleteBtn.style.pointerEvents = 'auto';
            deleteBtn.style.opacity = '1';
        }
        if (uploadLoading) {
            uploadLoading.classList.remove('show');
        }
    }
    
    function updateStatusEmpty() {
        if (emptyPreview) {
            emptyPreview.style.display = 'flex';
        }
        if (previewImage) {
            previewImage.style.display = 'none';
        }
        if (statusIcon) {
            statusIcon.className = 'status-icon tidak';
            statusIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
        }
        if (statusLabel) {
            statusLabel.className = 'status-label tidak';
            statusLabel.textContent = 'Belum ada infografis';
        }
        if (fileNameText) {
            fileNameText.style.display = 'none';
        }
        if (previewStatus) {
            previewStatus.classList.remove('show');
        }
        if (deleteBtn) {
            deleteBtn.className = 'btn-delete-icon disabled';
            deleteBtn.style.pointerEvents = 'none';
            deleteBtn.style.opacity = '0.3';
        }
    }
    
    if (infografisInput) {
        infografisInput.addEventListener('change', function(e) {
            var file = this.files[0];
            if (!file) return;
            
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                this.value = '';
                return;
            }
            
            var ext = file.name.split('.').pop().toLowerCase();
            var allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!allowed.includes(ext)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WEBP.');
                this.value = '';
                return;
            }
            
            var reader = new FileReader();
            reader.onload = function(e) {
                if (emptyPreview) {
                    emptyPreview.style.display = 'none';
                }
                if (previewImage) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                } else {
                    var img = document.createElement('img');
                    img.id = 'previewImage';
                    img.src = e.target.result;
                    img.alt = 'Preview Infografis';
                    previewContainer.appendChild(img);
                }
                if (statusIcon) {
                    statusIcon.className = 'status-icon ada';
                    statusIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
                }
                if (statusLabel) {
                    statusLabel.className = 'status-label ada';
                    statusLabel.textContent = 'Preview baru siap (belum tersimpan)';
                }
                if (fileNameText) {
                    fileNameText.style.display = 'block';
                    fileNameText.innerHTML = '<i class="fas fa-file-image"></i> ' + file.name + ' (preview)';
                }
                if (previewStatus) {
                    previewStatus.classList.add('show');
                    previewStatus.innerHTML = '<i class="fas fa-eye"></i> Preview baru siap, klik "Upload Infografis" untuk menyimpan';
                }
            };
            reader.readAsDataURL(file);
        });
    }
    
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            var fileInput = document.getElementById('infografisInput');
            var file = fileInput.files[0];
            
            if (!file) {
                alert('Pilih file terlebih dahulu!');
                return;
            }
            
            if (uploadLoading) {
                uploadLoading.classList.add('show');
            }
            
            var formData = new FormData();
            formData.append('infografis', file);
            formData.append('ajax_upload_infografis', 1);
            formData.append('kategori', kategoriAktif);
            
            fetch('{{ route('admin.iku.upload.infografis') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateStatusUploaded(data.file_name, data.file_path);
                    showNotification('success', data.message);
                } else {
                    alert('Error: ' + data.message);
                    if (uploadLoading) {
                        uploadLoading.classList.remove('show');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat upload!');
                if (uploadLoading) {
                    uploadLoading.classList.remove('show');
                }
            });
        });
    }
    
    var swalOverlay = document.getElementById('swalOverlay');
    var swalCancel = document.getElementById('swalCancel');
    var swalConfirm = document.getElementById('swalConfirm');
    var deleteUrl = '';
    
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.classList.contains('disabled')) {
                return;
            }
            deleteUrl = '{{ route('admin.iku.delete.infografis', ['kategori' => $kategori_aktif, 'tahun' => $tahun_aktif, 'sub' => $subkategori_wisata]) }}';
            swalOverlay.classList.add('show');
        });
    }
    
    if (swalCancel) {
        swalCancel.addEventListener('click', function() {
            swalOverlay.classList.remove('show');
        });
    }
    
    if (swalConfirm) {
        swalConfirm.addEventListener('click', function() {
            swalOverlay.classList.remove('show');
            window.location.href = deleteUrl;
        });
    }
    
    if (swalOverlay) {
        swalOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    }
    
    function showNotification(type, message) {
        var oldAlert = document.querySelector('.alert-notification');
        if (oldAlert) {
            oldAlert.remove();
        }
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-' + type + ' alert-notification';
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '99999';
        alertDiv.style.maxWidth = '400px';
        alertDiv.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
        alertDiv.style.animation = 'slideDown 0.3s ease';
        alertDiv.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
        document.body.appendChild(alertDiv);
        setTimeout(function() {
            alertDiv.style.opacity = '0';
            alertDiv.style.transition = 'opacity 0.3s';
            setTimeout(function() {
                alertDiv.remove();
            }, 300);
        }, 4000);
    }
    
    // File Sumber Preview
    var fileInput = document.getElementById('fileSumberInput');
    var previewList = document.getElementById('filePreviewList');
    
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            previewList.innerHTML = '';
            var files = this.files;
            var maxFiles = 15;
            
            if (files.length > maxFiles) {
                alert('Maksimal ' + maxFiles + ' file!');
                this.value = '';
                return;
            }
            
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var size = (file.size / 1024 / 1024).toFixed(2);
                var ext = file.name.split('.').pop().toLowerCase();
                var icon = 'fa-file';
                
                if (['pdf'].includes(ext)) icon = 'fa-file-pdf';
                else if (['doc', 'docx'].includes(ext)) icon = 'fa-file-word';
                else if (['xls', 'xlsx'].includes(ext)) icon = 'fa-file-excel';
                else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) icon = 'fa-file-image';
                
                var div = document.createElement('div');
                div.className = 'file-item';
                div.innerHTML = `
                    <i class="fas ${icon}"></i>
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">(${size} MB)</span>
                    <span class="file-status-text"><i class="fas fa-check-circle"></i> siap upload</span>
                `;
                previewList.appendChild(div);
            }
            
            if (files.length > 0) {
                var info = document.createElement('div');
                info.style.cssText = 'font-size:11px; color:#0f3b5e; margin-top:4px; font-weight:500;';
                info.textContent = '📁 ' + files.length + ' file siap diupload. Klik "Simpan Perubahan" untuk menyimpan.';
                previewList.appendChild(info);
            }
        });
    }
    
    var successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.display = 'none';
        }, 5000);
    }

    @if(! $can_edit)
    var mainForm = document.getElementById('mainForm');
    if (mainForm) {
        mainForm.querySelectorAll('input, textarea, select, button').forEach(function(el) {
            el.disabled = true;
        });
        document.querySelectorAll('.btn-hapus-file, .confirm-overlay').forEach(function(el) {
            if (el) el.style.display = 'none';
        });
        ['uploadInfografisBtn', 'deleteInfografisBtn'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
    }
    @endif
});
</script>
@endsection