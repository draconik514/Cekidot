@extends('layouts.admin')

@section('title', 'Kelola IKI - CEKIDOT')

@section('styles')
<style>
    /* ===== STYLE SAMA DENGAN IKI.PHP ASLI ===== */
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

    /* Filter Tahun */
    .filter-tahun {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 28px;
        background: rgba(255,255,255,0.6);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 6px;
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 4px 24px rgba(0,0,0,0.04), inset 0 1px 0 rgba(255,255,255,0.6);
        justify-content: center;
        flex-wrap: nowrap;
        position: relative;
        overflow: visible;
    }
    .filter-tahun::before {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: 17px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(234,179,8,0.15), transparent 50%, rgba(15,59,94,0.08));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }
    .filter-tahun .btn-tahun {
        width: 40px;
        height: 40px;
        border: none;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.4s cubic-bezier(0.34,1.56,0.64,1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 10px;
        margin: 0 2px;
        position: relative;
    }
    .filter-tahun .btn-tahun:hover:not(:disabled) {
        background: rgba(15,59,94,0.06);
        color: #0f3b5e;
        transform: scale(1.08);
    }
    .filter-tahun .btn-tahun:disabled {
        opacity: 0.2;
        cursor: not-allowed;
        transform: none !important;
    }
    .filter-tahun .btn-tahun .tooltip {
        position: absolute;
        bottom: calc(100% + 10px);
        left: 50%;
        transform: translateX(-50%) scale(0.8);
        background: rgba(15,59,94,0.9);
        backdrop-filter: blur(10px);
        color: #fff;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 500;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        pointer-events: none;
    }
    .filter-tahun .btn-tahun:hover .tooltip {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) scale(1);
    }
    .filter-tahun .btn-tahun .tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: rgba(15,59,94,0.9);
    }
    .filter-tahun .tahun-items {
        display: flex;
        align-items: center;
        gap: 3px;
        flex: 1;
        justify-content: center;
        padding: 0 8px;
    }
    .filter-tahun .tahun-items .tahun-item {
        padding: 7px 16px;
        border: none;
        background: transparent;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.34,1.56,0.64,1);
        font-family: 'Inter', sans-serif;
        position: relative;
        min-width: 52px;
        text-align: center;
        text-decoration: none;
        letter-spacing: 0.3px;
    }
    .filter-tahun .tahun-items .tahun-item .year-label { display: block; font-weight: 500; }
    .filter-tahun .tahun-items .tahun-item .year-count {
        display: block;
        font-size: 9px;
        opacity: 0.4;
        font-weight: 400;
        margin-top: 1px;
        transition: all 0.3s;
    }
    .filter-tahun .tahun-items .tahun-item:hover {
        color: #0f3b5e;
        background: rgba(15,59,94,0.05);
        transform: translateY(-2px);
    }
    .filter-tahun .tahun-items .tahun-item:hover .year-count { opacity: 0.7; }
    .filter-tahun .tahun-items .tahun-item.active {
        background: linear-gradient(135deg, #0f3b5e, #1a5a7a);
        color: #ffffff;
        box-shadow: 0 4px 16px rgba(15,59,94,0.25), inset 0 1px 0 rgba(255,255,255,0.1);
        transform: translateY(-2px);
    }
    .filter-tahun .tahun-items .tahun-item.active .year-count {
        opacity: 0.7;
        color: rgba(255,255,255,0.7);
    }
    .filter-tahun .tahun-items .tahun-item.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 3px;
        background: #eab308;
        border-radius: 2px;
        animation: activeLine 0.4s ease-out;
    }
    @keyframes activeLine {
        from { width: 0; opacity: 0; }
        to { width: 20px; opacity: 1; }
    }
    .filter-tahun .tahun-range-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: #94a3b8;
        padding: 0 16px;
        font-weight: 400;
        letter-spacing: 0.3px;
        flex-shrink: 0;
        border-left: 1px solid rgba(0,0,0,0.06);
        margin-left: 4px;
        padding-left: 16px;
        font-variant-numeric: tabular-nums;
    }
    .filter-tahun .tahun-range-label i { font-size: 12px; opacity: 0.4; color: #eab308; }
    .filter-tahun .tahun-range-label .range-arrow { opacity: 0.3; margin: 0 4px; }
    .filter-tahun .active-year-badge {
        display: none;
        align-items: center;
        gap: 8px;
        padding: 4px 14px 4px 18px;
        background: rgba(234,179,8,0.1);
        border: 1px solid rgba(234,179,8,0.15);
        border-radius: 20px;
        font-size: 11px;
        color: #eab308;
        font-weight: 500;
        flex-shrink: 0;
        margin-left: 8px;
    }
    .filter-tahun .active-year-badge .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #eab308;
        animation: blinkDot 1.5s ease-in-out infinite;
    }
    @keyframes blinkDot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.2; }
    }
    @media (min-width: 769px) {
        .filter-tahun .active-year-badge { display: flex; }
    }
    @media (max-width: 992px) {
        .filter-tahun .tahun-items .tahun-item { padding: 5px 12px; font-size: 13px; min-width: 44px; }
        .filter-tahun .tahun-range-label { font-size: 10px; padding: 0 10px; padding-left: 12px; }
        .filter-tahun .active-year-badge { display: none; }
    }
    @media (max-width: 768px) {
        .filter-tahun { flex-wrap: wrap; padding: 4px; gap: 2px; border-radius: 12px; }
        .filter-tahun .btn-tahun { width: 34px; height: 34px; font-size: 13px; margin: 0 1px; }
        .filter-tahun .tahun-items { flex-wrap: wrap; gap: 2px; padding: 2px; }
        .filter-tahun .tahun-items .tahun-item { padding: 4px 10px; font-size: 12px; min-width: 36px; border-radius: 8px; }
        .filter-tahun .tahun-items .tahun-item .year-count { font-size: 8px; }
        .filter-tahun .tahun-range-label {
            font-size: 10px;
            padding: 0 8px;
            padding-left: 10px;
            border-left: none;
            margin-left: 0;
            padding-left: 0;
            border-top: 1px solid rgba(0,0,0,0.04);
            padding-top: 6px;
            margin-top: 2px;
            width: 100%;
            justify-content: center;
        }
        .filter-tahun .active-year-badge { display: none; }
        .filter-tahun .tahun-items .tahun-item.active::after { width: 14px; bottom: -1px; }
        @keyframes activeLine {
            from { width: 0; opacity: 0; }
            to { width: 14px; opacity: 1; }
        }
    }
    @media (max-width: 480px) {
        .filter-tahun .btn-tahun { width: 30px; height: 30px; font-size: 11px; }
        .filter-tahun .tahun-items .tahun-item { padding: 3px 7px; font-size: 11px; min-width: 30px; border-radius: 6px; }
        .filter-tahun .tahun-items .tahun-item .year-count { font-size: 7px; }
        .filter-tahun .tahun-range-label { font-size: 9px; padding: 4px 0 0; }
    }

    /* Upload Form */
    .upload-form {
        background: #f8fafc;
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        border: 1px solid #e8ecf1;
    }
    .upload-form .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .upload-form .form-grid .full-width { grid-column: 1 / -1; }
    .upload-form .form-group { margin-bottom: 0; }
    .upload-form .form-group label {
        font-weight: 600;
        font-size: 13px;
        display: block;
        margin-bottom: 4px;
        color: #1e293b;
    }
    .upload-form .form-group label .required { color: #ef4444; }
    .upload-form .form-group label .optional { color: #94a3b8; font-weight: 400; font-size: 11px; }
    .upload-form .form-group input[type="text"],
    .upload-form .form-group textarea,
    .upload-form .form-group select {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        background: #fff;
        transition: border-color 0.3s;
    }
    .upload-form .form-group input[type="text"]:focus,
    .upload-form .form-group textarea:focus,
    .upload-form .form-group select:focus {
        outline: none;
        border-color: #0f3b5e;
    }
    .upload-form .form-group textarea { min-height: 38px; resize: vertical; }

    .tipe-konten-toggle {
        display: flex;
        gap: 0;
        background: #e8ecf1;
        border-radius: 8px;
        padding: 3px;
        margin-bottom: 4px;
    }
    .tipe-konten-toggle .toggle-btn {
        flex: 1;
        padding: 7px 16px;
        border: none;
        background: transparent;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .tipe-konten-toggle .toggle-btn i { font-size: 13px; }
    .tipe-konten-toggle .toggle-btn.active {
        background: #fff;
        color: #0f3b5e;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .tipe-konten-toggle .toggle-btn:hover:not(.active) { color: #1e293b; }

    .file-upload-wrapper {
        position: relative;
        width: 100%;
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
        display: block;
        padding: 8px 12px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        color: #475569;
        font-size: 13px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        min-height: 38px;
        line-height: 20px;
    }
    .file-upload-wrapper:hover .file-label {
        background: #f8fafc;
        border-color: #0f3b5e;
    }
    .file-upload-wrapper .file-label i { margin-right: 6px; color: #0f3b5e; }

    .file-preview-wrapper {
        display: none;
        align-items: center;
        gap: 10px;
        background: #f1f5f9;
        padding: 6px 12px 6px 16px;
        border-radius: 8px;
        margin-top: 6px;
        border: 1px solid #e2e8f0;
    }
    .file-preview-wrapper.show { display: flex; }
    .file-preview-wrapper .file-icon { font-size: 18px; color: #0f3b5e; }
    .file-preview-wrapper .file-name { flex: 1; font-size: 13px; color: #1e293b; word-break: break-all; }
    .file-preview-wrapper .file-size { font-size: 11px; color: #94a3b8; }
    .file-preview-wrapper .btn-remove-file {
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 2px 10px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s;
        line-height: 24px;
    }
    .file-preview-wrapper .btn-remove-file:hover { background: #b91c1c; }

    .link-input-wrapper { display: none; }
    .link-input-wrapper.show { display: block; }
    .link-input-wrapper input {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        background: #fff;
        transition: border-color 0.3s;
    }
    .link-input-wrapper input:focus {
        outline: none;
        border-color: #0f3b5e;
    }
    .link-input-wrapper .link-hint {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
        display: block;
    }

    .form-actions {
        margin-top: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-upload {
        padding: 8px 28px;
        background: #0f3b5e;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        height: 40px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-upload:hover {
        background: #0a2a44;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15,59,94,0.25);
    }

    .table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        background: #fff;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 700px;
    }
    table th {
        text-align: left;
        padding: 12px 16px;
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 2px solid #e2e8f0;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    table tr:hover td { background: #f8fafc; }
    table tr:last-child td { border-bottom: none; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-badge.aktif { background: #d1fae5; color: #065f46; }
    .status-badge.nonaktif { background: #fef2f2; color: #991b1b; }

    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
    }
    .type-badge.file { background: #dbeafe; color: #1d4ed8; }
    .type-badge.link { background: #fef3c7; color: #b45309; }

    .action-group {
        display: flex;
        gap: 4px;
        align-items: center;
        flex-wrap: wrap;
    }
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 13px;
        text-decoration: none;
    }
    .btn-action:hover { transform: scale(1.08); }
    .btn-action.btn-edit { background: #dbeafe; color: #1d4ed8; }
    .btn-action.btn-edit:hover { background: #93c5fd; }
    .btn-action.btn-view { background: #d1fae5; color: #065f46; }
    .btn-action.btn-view:hover { background: #a7f3d0; }
    .btn-action.btn-delete { background: #fef2f2; color: #991b1b; }
    .btn-action.btn-delete:hover { background: #fecaca; }
    .btn-action.btn-toggle { background: #f1f5f9; color: #64748b; }
    .btn-action.btn-toggle:hover { background: #e2e8f0; }
    .btn-action.btn-toggle.aktif { background: #d1fae5; color: #065f46; }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }
    .empty-state i { font-size: 40px; opacity: 0.3; display: block; margin-bottom: 12px; }
    .empty-state h3 { font-size: 17px; color: #1e293b; margin-bottom: 4px; }

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
        border-radius: 20px;
        max-width: 720px;
        width: 100%;
        max-height: 92vh;
        overflow-y: auto;
        padding: 32px;
        box-shadow: 0 30px 80px rgba(0,0,0,0.35);
        animation: modalIn 0.3s ease-out;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-box .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e8ecf1;
    }
    .modal-box .modal-header h3 {
        font-size: 20px;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-box .modal-header h3 i { color: #eab308; }
    .modal-box .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.3s;
        padding: 0 8px;
        border-radius: 6px;
        line-height: 1;
    }
    .modal-box .modal-close:hover { color: #dc2626; background: #fef2f2; }

    .modal-box .edit-form .form-group { margin-bottom: 14px; }
    .modal-box .edit-form .form-group label {
        font-weight: 600;
        font-size: 13px;
        display: block;
        margin-bottom: 4px;
        color: #1e293b;
    }
    .modal-box .edit-form .form-group input,
    .modal-box .edit-form .form-group textarea,
    .modal-box .edit-form .form-group select {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
    }
    .modal-box .edit-form .form-group input:focus,
    .modal-box .edit-form .form-group textarea:focus {
        outline: none;
        border-color: #0f3b5e;
    }
    .modal-box .edit-form .form-group textarea { min-height: 60px; resize: vertical; }
    .modal-box .edit-form .form-group .file-info {
        font-size: 13px;
        color: #64748b;
        background: #f8fafc;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    .modal-box .edit-form .form-group .file-info i { margin-right: 6px; }

    .modal-box .edit-form .file-upload-wrapper {
        position: relative;
        width: 100%;
    }
    .modal-box .edit-form .file-upload-wrapper input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
        top: 0;
        left: 0;
    }
    .modal-box .edit-form .file-upload-wrapper .file-label {
        display: block;
        padding: 8px 12px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        color: #475569;
        font-size: 13px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        min-height: 38px;
        line-height: 20px;
    }
    .modal-box .edit-form .file-upload-wrapper:hover .file-label {
        background: #f8fafc;
        border-color: #0f3b5e;
    }
    .modal-box .edit-form .file-upload-wrapper .file-label i { margin-right: 6px; color: #0f3b5e; }
    .modal-box .edit-form .file-preview-wrapper {
        display: none;
        align-items: center;
        gap: 10px;
        background: #f1f5f9;
        padding: 6px 12px 6px 16px;
        border-radius: 8px;
        margin-top: 6px;
        border: 1px solid #e2e8f0;
    }
    .modal-box .edit-form .file-preview-wrapper.show { display: flex; }
    .modal-box .edit-form .file-preview-wrapper .file-icon { font-size: 18px; color: #0f3b5e; }
    .modal-box .edit-form .file-preview-wrapper .file-name { flex: 1; font-size: 13px; color: #1e293b; word-break: break-all; }
    .modal-box .edit-form .file-preview-wrapper .file-size { font-size: 11px; color: #94a3b8; }
    .modal-box .edit-form .file-preview-wrapper .btn-remove-file {
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 2px 10px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s;
        line-height: 24px;
    }
    .modal-box .edit-form .file-preview-wrapper .btn-remove-file:hover { background: #b91c1c; }

    .modal-box .edit-form .link-input-wrapper { display: none; }
    .modal-box .edit-form .link-input-wrapper.show { display: block; }
    .modal-box .edit-form .link-input-wrapper input {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        background: #fff;
    }
    .modal-box .edit-form .link-input-wrapper input:focus {
        outline: none;
        border-color: #0f3b5e;
    }

    .modal-box .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e8ecf1;
    }
    .modal-box .modal-actions .btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .modal-box .modal-actions .btn-primary { background: #0f3b5e; color: #fff; }
    .modal-box .modal-actions .btn-primary:hover {
        background: #0a2a44;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15,59,94,0.3);
    }
    .modal-box .modal-actions .btn-secondary { background: #f1f5f9; color: #1e293b; }
    .modal-box .modal-actions .btn-secondary:hover { background: #e2e8f0; }
    .modal-box .modal-actions .btn-danger { background: #dc2626; color: #fff; }
    .modal-box .modal-actions .btn-danger:hover { background: #b91c1c; }

    .modal-box .view-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 24px;
        background: #f8fafc;
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 16px;
    }
    .modal-box .view-info .item {
        display: flex;
        flex-direction: column;
    }
    .modal-box .view-info .item .label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    .modal-box .view-info .item .value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
        word-break: break-all;
    }
    .modal-box .view-preview {
        background: #f1f5f9;
        border-radius: 10px;
        margin-bottom: 16px;
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .modal-box .view-preview iframe {
        width: 100%;
        height: 350px;
        border: none;
        border-radius: 10px;
    }
    .modal-box .view-preview .no-preview {
        text-align: center;
        padding: 30px 20px;
        color: #94a3b8;
    }
    .modal-box .view-preview .no-preview i {
        font-size: 48px;
        display: block;
        margin-bottom: 12px;
        opacity: 0.3;
    }
    .modal-box .view-preview .no-preview .ext {
        font-size: 16px;
        font-weight: 500;
        color: #1e293b;
    }
    .modal-box .security-warning {
        background: #fef3c7;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border: 1px solid #fde68a;
    }
    .modal-box .security-warning i {
        color: #b45309;
        font-size: 18px;
        margin-top: 2px;
    }
    .modal-box .security-warning div {
        font-size: 13px;
        color: #92400e;
    }
    .modal-box .security-warning div strong { display: block; }

    .modal-box.confirm-box {
        max-width: 420px;
        text-align: center;
    }
    .modal-box.confirm-box .confirm-icon {
        font-size: 56px;
        color: #dc2626;
        margin-bottom: 12px;
    }
    .modal-box.confirm-box h3 {
        font-size: 20px;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .modal-box.confirm-box p {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 20px;
    }
    .modal-box.confirm-box .modal-actions {
        justify-content: center;
        border-top: none;
        padding-top: 0;
        margin-top: 0;
    }

    @media (max-width: 992px) {
        .upload-form .form-grid { grid-template-columns: 1fr; }
        .upload-form .form-grid .full-width { grid-column: 1; }
        .modal-box .view-info { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .header { flex-direction: column; align-items: flex-start; }
        .modal-box { padding: 20px; }
        .modal-box .view-preview iframe { height: 200px; }
        table { font-size: 12px; min-width: 500px; }
        table th, table td { padding: 8px 10px; }
        .action-group { gap: 3px; }
        .btn-action { width: 28px; height: 28px; font-size: 12px; }
        .upload-form { padding: 14px 16px; }
        .tipe-konten-toggle .toggle-btn { font-size: 11px; padding: 6px 10px; }
    }
    @media (max-width: 480px) {
        .header h1 { font-size: 20px; }
        table { font-size: 12px; min-width: 500px; }
        .filter-tahun { padding: 8px 14px; }
        .filter-tahun .btn-tahun { width: 32px; height: 32px; font-size: 14px; }
    }
</style>
@endsection

@section('content')
@php
    $total_baru = App\Models\SuratMasuk::where('status', 'baru')->count();
    $admin_nama = auth()->user()->nama_admin ?? 'Admin';
@endphp

<div class="header">
    <div>
        <h1><i class="fas fa-user-check"></i> Kelola Dokumen IKI</h1>
        <span class="info">Total: {{ count($dokumen) }} dokumen untuk tahun {{ $tahun_aktif }}</span>
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

<!-- Filter Tahun -->
<div class="filter-tahun">
    <button class="btn-tahun" onclick="changeYear({{ $tahun_aktif - 1 }})" {{ $tahun_aktif <= 2025 ? 'disabled' : '' }}>
        <i class="fas fa-chevron-left"></i>
        <span class="tooltip">Tahun Sebelumnya</span>
    </button>

    <div class="tahun-items" id="tahunItems">
        @foreach(range(2025, 2030) as $t)
            @php
                $count = App\Models\DokumenIki::where('tahun', $t)->where('status', 'aktif')->count();
                $is_active = $t == $tahun_aktif;
            @endphp
            <a href="{{ route('admin.iki.index', ['tahun' => $t]) }}" class="tahun-item {{ $is_active ? 'active' : '' }}" data-year="{{ $t }}">
                <span class="year-label">{{ $t }}</span>
                <span class="year-count">{{ $count }} dokumen</span>
            </a>
        @endforeach
    </div>

    <span class="tahun-range-label">
        <i class="fas fa-calendar-alt"></i>
        2025 <span class="range-arrow">—</span> 2030
    </span>

    <div class="active-year-badge">
        <span class="badge-dot"></span>
        {{ $tahun_aktif }}
    </div>

    <button class="btn-tahun" onclick="changeYear({{ $tahun_aktif + 1 }})" {{ $tahun_aktif >= 2030 ? 'disabled' : '' }}>
        <i class="fas fa-chevron-right"></i>
        <span class="tooltip">Tahun Berikutnya</span>
    </button>
</div>

<!-- Upload Form -->
<div class="upload-form">
    <form method="post" enctype="multipart/form-data" id="uploadForm" action="{{ route('admin.iki.store') }}">
        @csrf
        <input type="hidden" name="action" value="upload">
        
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Judul Dokumen <span class="required">*</span></label>
                <input type="text" name="judul" placeholder="Masukkan judul dokumen" required>
            </div>
            
            <div class="form-group">
                <label>Tahun <span class="required">*</span></label>
                <select name="tahun" required>
                    @foreach(range(2025, 2030) as $t)
                    <option value="{{ $t }}" {{ $tahun_aktif == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Tipe Konten <span class="required">*</span></label>
                <div class="tipe-konten-toggle" id="tipeKontenToggle">
                    <button type="button" class="toggle-btn active" data-value="file">
                        <i class="fas fa-upload"></i> Upload File
                    </button>
                    <button type="button" class="toggle-btn" data-value="link">
                        <i class="fas fa-link"></i> URL/Link
                    </button>
                </div>
                <input type="hidden" name="tipe_konten" id="tipeKontenInput" value="file">
            </div>
            
            <div class="form-group full-width" id="fileUploadSection">
                <label>Upload File <span class="required">*</span> <span class="optional">(Maks 50MB)</span></label>
                <div class="file-upload-wrapper">
                    <input type="file" name="file_dokumen" id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.jpg,.jpeg,.png">
                    <span class="file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih File (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, 7z, JPG, JPEG, PNG)</span>
                </div>
                <span class="format-hint"><i class="fas fa-info-circle"></i> Format didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, 7z, JPG, JPEG, PNG | Maks 50MB</span>
                <div class="file-preview-wrapper" id="filePreview">
                    <span class="file-icon"><i class="fas fa-file"></i></span>
                    <span class="file-name" id="fileName">nama-file.pdf</span>
                    <span class="file-size" id="fileSize">(2.4 MB)</span>
                    <button type="button" class="btn-remove-file" id="btnRemoveFile">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div>
            </div>
            
            <div class="form-group full-width link-input-wrapper" id="linkInputSection">
                <label>URL/Link Dokumen <span class="required">*</span></label>
                <input type="text" name="link_url" id="linkUrlInput" placeholder="https://drive.google.com/... atau https://...">
                <span class="link-hint"><i class="fas fa-external-link-alt"></i> Masukkan URL lengkap dokumen (Google Drive, OneDrive, Dropbox, atau URL lainnya)</span>
            </div>
            
            <div class="form-group full-width">
                <label>Deskripsi <span class="optional">(Opsional)</span></label>
                <textarea name="deskripsi" placeholder="Deskripsi singkat dokumen..." rows="2"></textarea>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-upload" id="btnUpload">
                <i class="fas fa-upload"></i> Tambah Dokumen
            </button>
            <span style="font-size:12px; color:#94a3b8;">
                <i class="fas fa-info-circle"></i> Isi minimal: Judul + File atau Link
            </span>
        </div>
    </form>
</div>

<!-- Table -->
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width:35px;">#</th>
                <th>Judul</th>
                <th style="width:80px;">Tahun</th>
                <th style="width:90px;">Tipe</th>
                <th style="width:80px;">Status</th>
                <th style="width:130px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if($dokumen->isEmpty())
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <h3>Belum Ada Dokumen</h3>
                        <p style="font-size:14px;">Upload dokumen pertama atau tambahkan link untuk tahun {{ $tahun_aktif }}</p>
                    </div>
                </td>
            </tr>
            @else
            @php $no=1; @endphp
            @foreach($dokumen as $d)
            <tr>
                <td>{{ $no++ }}</td>
                <td>
                    <div style="font-weight:500;">{{ $d->judul }}</div>
                    @if($d->deskripsi)
                    <div style="font-size:12px; color:#64748b;">{{ substr($d->deskripsi, 0, 50) }}{{ strlen($d->deskripsi) > 50 ? '...' : '' }}</div>
                    @endif
                </td>
                <td><span style="background:#f1f5f9; padding:2px 12px; border-radius:12px; font-size:13px;">{{ $d->tahun }}</span></td>
                <td>
                    <span class="type-badge {{ $d->tipe_konten ?? 'file' }}">
                        <i class="fas {{ ($d->tipe_konten ?? 'file') == 'file' ? 'fa-upload' : 'fa-link' }}"></i>
                        {{ ($d->tipe_konten ?? 'file') == 'file' ? 'File' : 'Link' }}
                    </span>
                    @if(($d->tipe_konten ?? 'file') == 'file' && !empty($d->file_type))
                    <span style="font-size:10px; color:#94a3b8; display:block;">.{{ strtoupper($d->file_type) }}</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge {{ $d->status ?? 'aktif' }}">
                        <i class="fas {{ ($d->status ?? 'aktif') == 'aktif' ? 'fa-check-circle' : 'fa-circle' }}"></i>
                        {{ $d->status ?? 'aktif' }}
                    </span>
                </td>
                <td>
                    <div class="action-group">
                        <button class="btn-action btn-edit" onclick="openEditModal(
                            '{{ $d->id }}',
                            '{{ addslashes($d->judul) }}',
                            '{{ addslashes($d->deskripsi ?? '') }}',
                            '{{ $d->tahun }}',
                            '{{ $d->tipe_konten ?? 'file' }}',
                            '{{ addslashes($d->file_dokumen ?? '') }}',
                            '{{ addslashes($d->link_url ?? '') }}'
                        )" title="Edit Dokumen">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn-action btn-view" onclick="openViewModal(
                            '{{ $d->id }}',
                            '{{ addslashes($d->judul) }}',
                            '{{ addslashes($d->deskripsi ?? '') }}',
                            '{{ $d->tahun }}',
                            '{{ $d->tipe_konten ?? 'file' }}',
                            '{{ addslashes($d->file_dokumen ?? '') }}',
                            '{{ addslashes($d->link_url ?? '') }}',
                            '{{ $d->status ?? 'aktif' }}'
                        )" title="Lihat Dokumen">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.iki.toggle', $d->id) }}?tahun={{ $tahun_aktif }}" class="btn-action btn-toggle {{ $d->status ?? 'aktif' }}" title="{{ ($d->status ?? 'aktif') == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                            <i class="fas {{ ($d->status ?? 'aktif') == 'aktif' ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                        </a>
                        <button class="btn-action btn-delete" onclick="openDeleteModal({{ $d->id }}, '{{ addslashes($d->judul) }}')" title="Hapus Dokumen">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-pen"></i> Edit Dokumen</h3>
            <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form method="post" enctype="multipart/form-data" class="edit-form" id="editForm" action="{{ route('admin.iki.update') }}">
            @csrf
            <input type="hidden" name="edit_id" id="edit_id">
            <input type="hidden" name="action" value="edit">
            
            <div class="form-group">
                <label>Judul Dokumen <span class="required">*</span></label>
                <input type="text" name="edit_judul" id="edit_judul" required>
            </div>
            
            <div class="form-group">
                <label>Tahun <span class="required">*</span></label>
                <select name="edit_tahun" id="edit_tahun" required>
                    @foreach(range(2025, 2030) as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Tipe Konten <span class="required">*</span></label>
                <div class="tipe-konten-toggle" id="editTipeKontenToggle">
                    <button type="button" class="toggle-btn" data-value="file">
                        <i class="fas fa-upload"></i> Upload File
                    </button>
                    <button type="button" class="toggle-btn" data-value="link">
                        <i class="fas fa-link"></i> URL/Link
                    </button>
                </div>
                <input type="hidden" name="edit_tipe_konten" id="editTipeKontenInput" value="file">
            </div>
            
            <div class="form-group" id="editFileSection">
                <label>File Saat Ini</label>
                <div class="file-info" id="edit_file_info">
                    <i class="fas fa-file"></i> <span id="edit_file_name">-</span>
                </div>
                <label style="margin-top:8px;">Ganti File <span class="optional">(Kosongkan jika tidak ingin mengganti)</span></label>
                <div class="file-upload-wrapper">
                    <input type="file" name="edit_file" id="editFileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.jpg,.jpeg,.png">
                    <span class="file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih File Baru</span>
                </div>
                <div class="file-preview-wrapper" id="editFilePreview">
                    <span class="file-icon"><i class="fas fa-file"></i></span>
                    <span class="file-name" id="editFileName">nama-file.pdf</span>
                    <span class="file-size" id="editFileSize">(2.4 MB)</span>
                    <button type="button" class="btn-remove-file" id="editBtnRemoveFile">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div>
                <span class="format-hint"><i class="fas fa-info-circle"></i> Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, 7z, JPG, JPEG, PNG | Maks 50MB</span>
            </div>
            
            <div class="form-group link-input-wrapper" id="editLinkSection">
                <label>URL/Link Dokumen <span class="required">*</span></label>
                <input type="text" name="edit_link_url" id="edit_link_url" placeholder="https://drive.google.com/... atau https://...">
                <span class="link-hint"><i class="fas fa-external-link-alt"></i> Masukkan URL lengkap dokumen</span>
            </div>
            
            <div class="form-group">
                <label>Deskripsi <span class="optional">(Opsional)</span></label>
                <textarea name="edit_deskripsi" id="edit_deskripsi" rows="2"></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal View -->
<div class="modal-overlay" id="viewModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-eye"></i> <span id="viewTitle">Detail Dokumen</span></h3>
            <button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
        </div>
        
        <div class="view-info" id="viewInfo">
            <div class="item">
                <span class="label">Judul</span>
                <span class="value" id="viewJudul">-</span>
            </div>
            <div class="item">
                <span class="label">Tahun</span>
                <span class="value" id="viewTahun">-</span>
            </div>
            <div class="item">
                <span class="label">Tipe</span>
                <span class="value" id="viewTipe">-</span>
            </div>
            <div class="item">
                <span class="label">Status</span>
                <span class="value" id="viewStatus">-</span>
            </div>
            <div class="item" style="grid-column: 1 / -1;">
                <span class="label">Deskripsi</span>
                <span class="value" id="viewDeskripsi">-</span>
            </div>
            <div class="item" style="grid-column: 1 / -1;">
                <span class="label">Nama File / Link</span>
                <span class="value" id="viewFileName">-</span>
            </div>
        </div>
        
        <div class="view-preview" id="viewPreview">
            <div class="no-preview">
                <i class="fas fa-file"></i>
                <span class="ext">Memuat file...</span>
            </div>
        </div>
        
        <div class="security-warning">
            <i class="fas fa-shield-alt"></i>
            <div>
                <strong>⚠️ Peringatan Keamanan</strong>
                Pastikan file/link aman sebelum diakses. Scan file terlebih dahulu jika ragu.
            </div>
        </div>
        
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal('viewModal')"><i class="fas fa-times"></i> Tutup</button>
            <a href="#" class="btn btn-primary" id="viewDownloadBtn" target="_blank"><i class="fas fa-external-link-alt"></i> Buka / Download</a>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box confirm-box">
        <div class="confirm-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Hapus Dokumen?</h3>
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus dokumen ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Batal</button>
            <a href="#" class="btn btn-danger" id="deleteConfirmBtn"><i class="fas fa-trash"></i> Hapus</a>
        </div>
    </div>
</div>

<script>
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow = 'auto';
}

function openModal(id) {
    document.getElementById(id).classList.add('show');
    document.body.style.overflow = 'hidden';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.show').forEach(function(el) {
            el.classList.remove('show');
            document.body.style.overflow = 'auto';
        });
    }
});

document.querySelectorAll('.modal-overlay').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    });
});

function getFileIcon(ext) {
    var iconMap = {
        'pdf': 'fa-file-pdf', 'doc': 'fa-file-word', 'docx': 'fa-file-word',
        'xls': 'fa-file-excel', 'xlsx': 'fa-file-excel',
        'ppt': 'fa-file-powerpoint', 'pptx': 'fa-file-powerpoint',
        'zip': 'fa-file-archive', 'rar': 'fa-file-archive', '7z': 'fa-file-archive',
        'jpg': 'fa-file-image', 'jpeg': 'fa-file-image', 'png': 'fa-file-image'
    };
    return iconMap[ext] || 'fa-file';
}

// Tipe Konten Toggle - Upload Form
document.addEventListener('DOMContentLoaded', function() {
    var toggleButtons = document.querySelectorAll('#tipeKontenToggle .toggle-btn');
    var tipeKontenInput = document.getElementById('tipeKontenInput');
    var fileSection = document.getElementById('fileUploadSection');
    var linkSection = document.getElementById('linkInputSection');
    var fileInput = document.getElementById('fileInput');
    var linkInput = document.getElementById('linkUrlInput');
    
    function setTipeKonten(value) {
        toggleButtons.forEach(function(btn) {
            btn.classList.toggle('active', btn.dataset.value === value);
        });
        tipeKontenInput.value = value;
        
        if (value === 'file') {
            fileSection.style.display = 'block';
            linkSection.classList.remove('show');
            fileInput.required = true;
            linkInput.required = false;
            linkInput.value = '';
        } else {
            fileSection.style.display = 'none';
            linkSection.classList.add('show');
            fileInput.required = false;
            linkInput.required = true;
            fileInput.value = '';
            document.getElementById('filePreview').classList.remove('show');
        }
    }
    
    toggleButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            setTipeKonten(this.dataset.value);
        });
    });
    
    setTipeKonten('file');
});

// File Upload Preview
document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('fileInput');
    var filePreview = document.getElementById('filePreview');
    var fileName = document.getElementById('fileName');
    var fileSize = document.getElementById('fileSize');
    var btnRemoveFile = document.getElementById('btnRemoveFile');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var file = this.files[0];
                var size = (file.size / 1024 / 1024).toFixed(2);
                var ext = file.name.split('.').pop().toLowerCase();
                var icon = getFileIcon(ext);
                
                document.querySelector('#filePreview .file-icon i').className = 'fas ' + icon;
                fileName.textContent = file.name;
                fileSize.textContent = '(' + size + ' MB)';
                filePreview.classList.add('show');
            }
        });
    }

    if (btnRemoveFile) {
        btnRemoveFile.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.classList.remove('show');
            fileName.textContent = '';
            fileSize.textContent = '';
        });
    }
});

// Edit Modal Functions
function openEditModal(id, judul, deskripsi, tahun, tipe_konten, file_dokumen, link_url) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_judul').value = judul;
    document.getElementById('edit_deskripsi').value = deskripsi || '';
    document.getElementById('edit_tahun').value = tahun;
    document.getElementById('edit_file_name').textContent = file_dokumen || 'Tidak ada file';
    document.getElementById('edit_link_url').value = link_url || '';
    
    var tipe = tipe_konten || 'file';
    var editToggle = document.querySelectorAll('#editTipeKontenToggle .toggle-btn');
    editToggle.forEach(function(btn) {
        btn.classList.toggle('active', btn.dataset.value === tipe);
    });
    document.getElementById('editTipeKontenInput').value = tipe;
    
    var editFileSection = document.getElementById('editFileSection');
    var editLinkSection = document.getElementById('editLinkSection');
    var editFileInput = document.getElementById('editFileInput');
    var editLinkInput = document.getElementById('edit_link_url');
    
    if (tipe === 'file') {
        editFileSection.style.display = 'block';
        editLinkSection.classList.remove('show');
        editFileInput.required = false;
        editLinkInput.required = false;
    } else {
        editFileSection.style.display = 'none';
        editLinkSection.classList.add('show');
        editFileInput.required = false;
        editLinkInput.required = true;
    }
    
    document.getElementById('editFilePreview').classList.remove('show');
    document.getElementById('editFileInput').value = '';
    
    openModal('editModal');
}

// View Modal Function
function openViewModal(id, judul, deskripsi, tahun, tipe_konten, file_dokumen, link_url, status) {
    var tipe = tipe_konten || 'file';
    
    document.getElementById('viewTitle').textContent = judul;
    document.getElementById('viewJudul').textContent = judul;
    document.getElementById('viewTahun').textContent = tahun;
    document.getElementById('viewTipe').innerHTML = 
        '<span class="type-badge ' + tipe + '"><i class="fas ' + (tipe === 'file' ? 'fa-upload' : 'fa-link') + '"></i> ' + 
        (tipe === 'file' ? 'File' : 'Link') + '</span>';
    document.getElementById('viewStatus').innerHTML = 
        '<span class="status-badge ' + (status || 'aktif') + '">' + (status || 'aktif') + '</span>';
    document.getElementById('viewDeskripsi').textContent = deskripsi || '-';
    document.getElementById('viewFileName').textContent = file_dokumen || link_url || '-';
    
    var preview = document.getElementById('viewPreview');
    var downloadBtn = document.getElementById('viewDownloadBtn');
    
    if (tipe === 'file' && file_dokumen) {
        var filePath = '{{ asset('uploads/iki') }}/' + file_dokumen;
        var ext = (file_dokumen || '').split('.').pop().toLowerCase();
        var isImage = ['jpg','jpeg','png','gif','webp','bmp'].includes(ext);
        var isPDF = ext === 'pdf';
        var isArchive = ['zip','rar','7z','tar','gz','tgz','bz2','xz'].includes(ext);
        var isOffice = ['doc','docx','xls','xlsx','ppt','pptx'].includes(ext);
        
        if (isImage) {
            preview.innerHTML = '<img src="' + filePath + '" style="width:100%; max-height:350px; object-fit:contain; border-radius:8px;" alt="' + file_dokumen + '" onerror="this.parentElement.innerHTML=\'<div class=\\\'no-preview\\\'><i class=\\\'fas fa-file-image\\\' style=\\\'font-size:48px; color:#0f3b5e;\\\'></i><span class=\\\'ext\\\'>File tidak ditemukan</span></div>\'">';
        } else if (isPDF) {
            preview.innerHTML = '<iframe src="' + filePath + '#toolbar=1" style="width:100%; height:350px; border:none; border-radius:8px;"></iframe>';
        } else {
            var icon = getFileIcon(ext);
            preview.innerHTML = `
                <div class="no-preview">
                    <i class="fas ${icon}" style="font-size:48px; color:#0f3b5e;"></i>
                    <span class="ext">File ${ext.toUpperCase()}</span>
                    <p style="font-size:13px; color:#94a3b8; margin-top:8px;">File tidak dapat ditampilkan. Silakan download.</p>
                </div>
            `;
        }
        
        downloadBtn.href = filePath;
        downloadBtn.setAttribute('download', file_dokumen);
        downloadBtn.removeAttribute('target');
        downloadBtn.innerHTML = '<i class="fas fa-download"></i> Download';
        downloadBtn.style.opacity = '1';
        downloadBtn.style.cursor = 'pointer';
    } else if (tipe === 'link' && link_url) {
        preview.innerHTML = `
            <div class="no-preview">
                <i class="fas fa-external-link-alt" style="font-size:48px; color:#0f3b5e;"></i>
                <span class="ext">Dokumen via Link</span>
                <p style="font-size:13px; color:#94a3b8; margin-top:8px;">Klik tombol "Buka Link" untuk membuka dokumen.</p>
                <p style="font-size:12px; color:#94a3b8; margin-top:4px; word-break:break-all;">${link_url}</p>
            </div>
        `;
        downloadBtn.href = link_url;
        downloadBtn.removeAttribute('download');
        downloadBtn.innerHTML = '<i class="fas fa-external-link-alt"></i> Buka Link';
        downloadBtn.target = '_blank';
        downloadBtn.style.opacity = '1';
        downloadBtn.style.cursor = 'pointer';
    } else {
        preview.innerHTML = `
            <div class="no-preview">
                <i class="fas fa-file" style="font-size:48px; color:#94a3b8;"></i>
                <span class="ext">Tidak ada file</span>
                <p style="font-size:13px; color:#94a3b8; margin-top:8px;">Dokumen ini tidak memiliki file atau link.</p>
            </div>
        `;
        downloadBtn.href = '#';
        downloadBtn.removeAttribute('download');
        downloadBtn.innerHTML = '<i class="fas fa-times"></i> Tidak tersedia';
        downloadBtn.style.opacity = '0.5';
        downloadBtn.style.cursor = 'not-allowed';
    }
    
    openModal('viewModal');
}

// Delete Modal
function openDeleteModal(id, judul) {
    document.getElementById('deleteMessage').textContent = 'Apakah Anda yakin ingin menghapus dokumen "' + judul + '"? Tindakan ini tidak dapat dibatalkan.';
    document.getElementById('deleteConfirmBtn').href = '{{ url('admin/iki/delete') }}/' + id + '?tahun={{ $tahun_aktif }}';
    openModal('deleteModal');
}

// Filter Tahun
function changeYear(year) {
    var minYear = 2025;
    var maxYear = 2030;
    if (year < minYear || year > maxYear) return;
    
    var items = document.querySelectorAll('.tahun-item');
    items.forEach(function(item) {
        item.style.transition = 'all 0.3s ease';
        if (parseInt(item.dataset.year) !== year) {
            item.style.opacity = '0.4';
            item.style.transform = 'scale(0.95)';
        }
    });
    
    setTimeout(function() {
        window.location.href = '{{ route('admin.iki.index') }}?tahun=' + year;
    }, 250);
}

// Highlight active year
document.addEventListener('DOMContentLoaded', function() {
    var activeItem = document.querySelector('.tahun-item.active');
    if (activeItem) {
        var container = document.getElementById('tahunItems');
        if (container) {
            var itemRect = activeItem.getBoundingClientRect();
            var containerRect = container.getBoundingClientRect();
            if (itemRect.left < containerRect.left || itemRect.right > containerRect.right) {
                activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }
    }
});
</script>
@endsection