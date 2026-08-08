@extends('layouts.app')

@section('title', 'Kirim Surat - CEKIDOT')

@section('styles')
<style>
    .kirim-surat-page {
        background-image: url('{{ asset('assets/img/background.jpg') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        padding: 40px 0 60px;
    }

    .kirim-surat-hero {
        text-align: center;
        margin-bottom: 36px;
        position: relative;
    }
    .kirim-surat-hero .hero-badge {
        display: inline-block;
        padding: 6px 20px;
        background: rgba(234, 179, 8, 0.15);
        border: 1px solid rgba(234, 179, 8, 0.2);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #eab308;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 12px;
    }
    .kirim-surat-hero h1 {
        font-size: 38px;
        font-weight: 800;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        margin-bottom: 8px;
    }
    .kirim-surat-hero h1 i {
        color: #eab308;
        filter: drop-shadow(0 2px 8px rgba(234, 179, 8, 0.2));
    }
    .kirim-surat-hero .subtitle {
        font-size: 16px;
        color: #64748b;
        max-width: 500px;
        margin: 0 auto;
        line-height: 1.7;
    }

    .kirim-surat-page .form-wrapper {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 40px 44px;
        border-radius: 20px;
        max-width: 820px;
        margin: 0 auto;
        box-shadow: 0 8px 40px rgba(0,0,0,0.06);
        border: 1px solid rgba(255,255,255,0.3);
        position: relative;
        overflow: hidden;
    }
    .kirim-surat-page .form-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(234, 179, 8, 0.03);
        border-radius: 50%;
        pointer-events: none;
    }
    .kirim-surat-page .form-wrapper::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(15, 59, 94, 0.03);
        border-radius: 50%;
        pointer-events: none;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px 24px;
        position: relative;
        z-index: 1;
    }
    .form-grid .full-width { grid-column: 1 / -1; }

    .form-group {
        margin-bottom: 0;
        position: relative;
    }
    .form-group label {
        font-weight: 600;
        font-size: 13px;
        color: #1e293b;
        display: block;
        margin-bottom: 5px;
    }
    .form-group label .required {
        color: #ef4444;
        margin-left: 2px;
    }
    .form-group label .optional {
        color: #94a3b8;
        font-weight: 400;
        font-size: 11px;
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        background: #ffffff;
        transition: all 0.3s ease;
        color: #1e293b;
    }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #0f3b5e;
        box-shadow: 0 0 0 4px rgba(15, 59, 94, 0.06);
    }
    .form-group input.error,
    .form-group textarea.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.06);
    }
    .form-group input.success,
    .form-group textarea.success {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.06);
    }
    .form-group textarea {
        min-height: 80px;
        resize: vertical;
    }
    .form-group .hint {
        font-size: 11px;
        color: #94a3b8;
        display: block;
        margin-top: 4px;
    }
    .form-group .hint i { margin-right: 4px; }
    .form-group .error-text {
        font-size: 11px;
        color: #ef4444;
        display: none;
        margin-top: 4px;
    }
    .form-group .error-text.show { display: block; }

    .file-upload-wrapper {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
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
        gap: 10px;
        padding: 14px 18px;
        background: #f8fafc;
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 52px;
    }
    .file-upload-wrapper:hover .file-label {
        background: #f1f5f9;
        border-color: #0f3b5e;
        color: #0f3b5e;
    }
    .file-upload-wrapper .file-label i {
        font-size: 20px;
        color: #0f3b5e;
        opacity: 0.5;
    }
    .file-upload-wrapper.dragover .file-label {
        background: #eff6ff;
        border-color: #0f3b5e;
        border-style: solid;
    }

    .file-preview-wrapper {
        display: none;
        align-items: center;
        gap: 14px;
        background: #f1f5f9;
        padding: 10px 16px 10px 18px;
        border-radius: 10px;
        margin-top: 10px;
        border: 1px solid #e2e8f0;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .file-preview-wrapper.show { display: flex; }
    .file-preview-wrapper .file-icon {
        font-size: 28px;
        color: #0f3b5e;
        flex-shrink: 0;
        width: 40px;
        text-align: center;
    }
    .file-preview-wrapper .file-detail { flex: 1; min-width: 0; }
    .file-preview-wrapper .file-detail .name {
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .file-preview-wrapper .file-detail .info {
        font-size: 12px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .file-preview-wrapper .file-detail .info .size {
        color: #64748b;
    }
    .file-preview-wrapper .file-detail .info .progress-bar {
        flex: 1;
        height: 3px;
        background: #e2e8f0;
        border-radius: 2px;
        overflow: hidden;
        max-width: 120px;
    }
    .file-preview-wrapper .file-detail .info .progress-bar .fill {
        height: 100%;
        width: 100%;
        background: linear-gradient(90deg, #0f3b5e, #eab308);
        border-radius: 2px;
        animation: progressAnim 1.5s ease;
    }
    @keyframes progressAnim {
        from { width: 0%; }
        to { width: 100%; }
    }
    .file-preview-wrapper .btn-remove-file {
        background: #fef2f2;
        color: #dc2626;
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s;
        flex-shrink: 0;
        font-weight: 500;
    }
    .file-preview-wrapper .btn-remove-file:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    .form-actions {
        display: flex;
        gap: 14px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    .form-actions .btn {
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-family: 'Inter', sans-serif;
    }
    .form-actions .btn-submit {
        background: linear-gradient(135deg, #0f3b5e, #1a5a7a);
        color: #fff;
        flex: 1;
        justify-content: center;
    }
    .form-actions .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(15,59,94,0.25);
    }
    .form-actions .btn-submit:active { transform: translateY(0); }
    .form-actions .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .form-actions .btn-submit .spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255,255,255,0.2);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .form-actions .btn-reset {
        background: #f1f5f9;
        color: #64748b;
    }
    .form-actions .btn-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        position: relative;
        z-index: 1;
    }
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .alert-success i {
        font-size: 24px;
        color: #16a34a;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .alert-success .btn-kirim-lagi {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        padding: 8px 24px;
        background: #16a34a;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s;
    }
    .alert-success .btn-kirim-lagi:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(22,163,74,0.3);
    }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .alert-danger i {
        font-size: 20px;
        color: #dc2626;
        flex-shrink: 0;
        margin-top: 2px;
    }

    @media (max-width: 992px) {
        .kirim-surat-page .form-wrapper { padding: 32px 28px; }
        .form-grid { grid-template-columns: 1fr 1fr; gap: 16px; }
    }
    @media (max-width: 768px) {
        .kirim-surat-page .form-wrapper { padding: 24px 18px; margin: 0 12px; border-radius: 16px; }
        .kirim-surat-hero h1 { font-size: 28px; }
        .kirim-surat-hero .subtitle { font-size: 14px; }
        .form-grid { grid-template-columns: 1fr; gap: 14px; }
        .form-grid .full-width { grid-column: 1; }
        .form-actions { flex-direction: column; }
        .form-actions .btn { justify-content: center; }
        .file-preview-wrapper { flex-wrap: wrap; }
        .file-preview-wrapper .btn-remove-file { width: 100%; text-align: center; padding: 8px; }
        .file-preview-wrapper .file-detail .info .progress-bar { max-width: 80px; }
        .file-upload-wrapper .file-label { font-size: 13px; padding: 12px 14px; }
    }
    @media (max-width: 480px) {
        .kirim-surat-page .form-wrapper { padding: 18px 12px; margin: 0 8px; }
        .kirim-surat-hero h1 { font-size: 24px; flex-wrap: wrap; }
        .kirim-surat-hero .hero-badge { font-size: 10px; padding: 4px 14px; }
        .form-group input,
        .form-group textarea { font-size: 13px; padding: 8px 12px; }
        .form-actions .btn { font-size: 14px; padding: 10px 20px; }
    }
</style>
@endsection

@section('content')
<div class="kirim-surat-page">
    <div class="container">
        <div class="kirim-surat-hero">
            <div class="hero-badge">
                <i class="fas fa-shield-alt"></i> Layanan Publik
            </div>
            <h1>
                <i class="fas fa-envelope"></i> Kirim Surat
            </h1>
            <p class="subtitle">
                Kirim surat resmi, masukan, atau pengaduan kepada Dinas Pariwisata 
                Provinsi Sulawesi Tengah dengan mudah dan aman.
            </p>
        </div>

        <div class="form-wrapper">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong style="font-size:18px;">Surat Berhasil Dikirim!</strong>
                    <p style="margin-top:4px; margin-bottom:4px;">
                        Terima kasih, surat Anda telah kami terima dan akan segera diproses oleh admin.
                    </p>
                    <p style="font-size:13px; color:#047857;">
                        <i class="fas fa-info-circle"></i> Nomor Surat: <strong>{{ session('nomor_surat') ?? '-' }}</strong>
                    </p>
                    <a href="{{ route('surat.create') }}" class="btn-kirim-lagi">
                        <i class="fas fa-paper-plane"></i> Kirim Surat Lain
                    </a>
                </div>
            </div>
            @else

            @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            <form method="post" enctype="multipart/form-data" id="formKirimSurat" novalidate>
                @csrf
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Asal Instansi / Perusahaan <span class="required">*</span></label>
                        <input type="text" name="asal" id="asal" placeholder="Masukkan nama instansi atau perusahaan" required value="{{ old('asal') }}">
                        <span class="error-text" id="asalError">Asal instansi wajib diisi</span>
                    </div>

                    <div class="form-group">
                        <label>Nama Pengirim <span class="required">*</span></label>
                        <input type="text" name="nama_pengirim" id="namaPengirim" placeholder="Masukkan nama lengkap" required value="{{ old('nama_pengirim') }}">
                        <span class="hint"><i class="fas fa-info-circle"></i> Nama lengkap pengirim surat</span>
                        <span class="error-text" id="namaPengirimError">Nama pengirim wajib diisi</span>
                    </div>

                    <div class="form-group">
                        <label>Nomor HP / WhatsApp <span class="required">*</span></label>
                        <input type="tel" name="no_hp" id="noHp" placeholder="Contoh: 081234567890" required value="{{ old('no_hp') }}">
                        <span class="hint"><i class="fas fa-info-circle"></i> Masukkan nomor HP/WA yang dapat dihubungi</span>
                        <span class="error-text" id="noHpError">Nomor HP/WA wajib diisi</span>
                    </div>

                    <div class="form-group">
                        <label>Nomor Surat <span class="required">*</span></label>
                        <input type="text" name="nomor_surat" id="nomorSurat" placeholder="Contoh: 001/DISPAR/07/2026" required value="{{ old('nomor_surat') }}">
                        <span class="hint"><i class="fas fa-info-circle"></i> Masukkan nomor surat sesuai dengan surat asli Anda</span>
                        <span class="error-text" id="nomorError">Nomor surat wajib diisi</span>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Surat <span class="required">*</span></label>
                        <input type="date" name="tanggal_surat" id="tanggalSurat" required value="{{ old('tanggal_surat', date('Y-m-d')) }}">
                        <span class="error-text" id="tanggalError">Tanggal surat wajib diisi</span>
                    </div>

                    <div class="form-group full-width">
                        <label>Perihal <span class="required">*</span></label>
                        <input type="text" name="perihal" id="perihal" placeholder="Masukkan perihal surat" required value="{{ old('perihal') }}">
                        <span class="error-text" id="perihalError">Perihal surat wajib diisi</span>
                    </div>

                    <div class="form-group full-width">
                        <label>Keterangan <span class="optional">(Opsional)</span></label>
                        <textarea name="keterangan" id="keterangan" rows="4" placeholder="Tuliskan keterangan tambahan atau catatan penting...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Upload File <span class="required">*</span> <span class="optional">(Max 5MB)</span></label>
                        <div class="file-upload-wrapper" id="fileDropZone">
                            <input type="file" name="file" id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.webp" required>
                            <span class="file-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Pilih atau Seret File</span>
                                <span style="font-size:12px; opacity:0.6; display:block; margin-top:2px;">
                                    PDF, DOC, XLS, JPG, PNG - Max 5MB
                                </span>
                            </span>
                        </div>
                        
                        <div class="file-preview-wrapper" id="filePreview">
                            <span class="file-icon"><i class="fas fa-file-pdf"></i></span>
                            <div class="file-detail">
                                <div class="name" id="fileName">nama-file.pdf</div>
                                <div class="info">
                                    <span class="size" id="fileSize">2.4 MB</span>
                                    <div class="progress-bar">
                                        <div class="fill"></div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-remove-file" id="btnRemoveFile">
                                <i class="fas fa-times"></i> Hapus
                            </button>
                        </div>
                        <span class="error-text" id="fileError">File wajib diupload</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn btn-reset" id="btnReset">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <button type="submit" class="btn btn-submit" id="btnSubmit">
                        <i class="fas fa-paper-plane"></i>
                        <span>Kirim Surat</span>
                        <span class="spinner"></span>
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('fileInput');
    var filePreview = document.getElementById('filePreview');
    var fileName = document.getElementById('fileName');
    var fileSize = document.getElementById('fileSize');
    var btnRemoveFile = document.getElementById('btnRemoveFile');
    var fileDropZone = document.getElementById('fileDropZone');

    function getFileIcon(ext) {
        var icons = {
            'pdf': 'fa-file-pdf', 'doc': 'fa-file-word', 'docx': 'fa-file-word',
            'xls': 'fa-file-excel', 'xlsx': 'fa-file-excel',
            'jpg': 'fa-file-image', 'jpeg': 'fa-file-image',
            'png': 'fa-file-image', 'gif': 'fa-file-image', 'webp': 'fa-file-image'
        };
        return icons[ext] || 'fa-file';
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(2) + ' MB';
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var file = this.files[0];
                var ext = file.name.split('.').pop().toLowerCase();
                var icon = getFileIcon(ext);
                
                document.querySelector('#filePreview .file-icon i').className = 'fas ' + icon;
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                filePreview.classList.add('show');
                
                document.getElementById('fileError').classList.remove('show');
                this.classList.remove('error');
                this.classList.add('success');
            }
        });
    }

    if (btnRemoveFile) {
        btnRemoveFile.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.classList.remove('show');
            fileName.textContent = '';
            fileSize.textContent = '';
            fileInput.classList.remove('success');
        });
    }

    if (fileDropZone) {
        ['dragenter', 'dragover'].forEach(function(eventName) {
            fileDropZone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.add('dragover');
            });
        });
        ['dragleave', 'drop'].forEach(function(eventName) {
            fileDropZone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('dragover');
            });
        });
        fileDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            var files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    }

    var resetBtn = document.getElementById('btnReset');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('formKirimSurat').reset();
            filePreview.classList.remove('show');
            fileName.textContent = '';
            fileSize.textContent = '';
            fileInput.classList.remove('success', 'error');
            document.querySelectorAll('.error-text').forEach(function(el) {
                el.classList.remove('show');
            });
            document.querySelectorAll('.form-group input, .form-group textarea').forEach(function(el) {
                el.classList.remove('error', 'success');
            });
        });
    }

    var form = document.getElementById('formKirimSurat');
    var submitBtn = document.getElementById('btnSubmit');

    function validateField(input, errorId, message) {
        var errorEl = document.getElementById(errorId);
        if (input.value.trim() === '') {
            input.classList.add('error');
            input.classList.remove('success');
            errorEl.textContent = message || 'Field ini wajib diisi';
            errorEl.classList.add('show');
            return false;
        } else {
            input.classList.remove('error');
            input.classList.add('success');
            errorEl.classList.remove('show');
            return true;
        }
    }

    document.querySelectorAll('#formKirimSurat input[required], #formKirimSurat textarea[required]').forEach(function(input) {
        var errorMap = {
            'asal': 'asalError',
            'nama_pengirim': 'namaPengirimError',
            'no_hp': 'noHpError',
            'nomor_surat': 'nomorError',
            'tanggal_surat': 'tanggalError',
            'perihal': 'perihalError'
        };
        var errorId = errorMap[input.name] || input.id + 'Error';
        var label = input.previousElementSibling ? input.previousElementSibling.textContent.trim() : input.name;
        
        input.addEventListener('blur', function() {
            validateField(this, errorId, label + ' wajib diisi');
        });
        input.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('error');
                this.classList.add('success');
                var el = document.getElementById(errorId);
                if (el) el.classList.remove('show');
            }
        });
    });

    if (form) {
        form.addEventListener('submit', function(e) {
            var isValid = true;
            
            document.querySelectorAll('#formKirimSurat input[required], #formKirimSurat textarea[required]').forEach(function(input) {
                var errorMap = {
                    'asal': 'asalError',
                    'nama_pengirim': 'namaPengirimError',
                    'no_hp': 'noHpError',
                    'nomor_surat': 'nomorError',
                    'tanggal_surat': 'tanggalError',
                    'perihal': 'perihalError'
                };
                var errorId = errorMap[input.name] || input.id + 'Error';
                var label = input.previousElementSibling ? input.previousElementSibling.textContent.trim().replace('*', '').trim() : input.name;
                
                if (!validateField(input, errorId, label + ' wajib diisi')) {
                    isValid = false;
                }
            });
            
            if (!fileInput.files || fileInput.files.length === 0) {
                document.getElementById('fileError').textContent = 'File wajib diupload';
                document.getElementById('fileError').classList.add('show');
                fileInput.classList.add('error');
                isValid = false;
            } else {
                document.getElementById('fileError').classList.remove('show');
                fileInput.classList.remove('error');
            }
            
            if (!isValid) {
                e.preventDefault();
                var firstError = document.querySelector('.form-group input.error, .form-group textarea.error');
                if (firstError) {
                    firstError.focus();
                }
                return false;
            }
            
            submitBtn.disabled = true;
            submitBtn.querySelector('.spinner').style.display = 'inline-block';
            submitBtn.querySelector('span:not(.spinner)').textContent = 'Mengirim...';
        });
    }

    var tanggalInput = document.getElementById('tanggalSurat');
    if (tanggalInput && !tanggalInput.value) {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        tanggalInput.value = yyyy + '-' + mm + '-' + dd;
    }
});
</script>
@endsection