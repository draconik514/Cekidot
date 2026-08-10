@extends('layouts.admin')

@section('title', 'Kelola Slider - CEKIDOT')

@section('styles')
<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
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
    .header .info { color: #64748b; font-size: 14px; }
    .header .admin-welcome { font-size: 14px; color: #64748b; }
    .header .admin-welcome i { color: #eab308; margin-right: 4px; }

    .alert {
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        animation: slideDown 0.4s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .alert-warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .alert i { font-size: 20px; }

    .upload-form {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        padding: 0;
        margin-bottom: 30px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .upload-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 8px;
    }
    .upload-form-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .upload-form-title i { color: #eab308; }
    .upload-form-badge {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        background: #ffffff;
        padding: 4px 16px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .upload-form-badge i { color: #eab308; }
    .upload-form-body { padding: 24px; }
    .upload-form-body .form-group { margin-bottom: 18px; }
    .upload-form-body .form-group:last-child { margin-bottom: 0; }
    .upload-form-body .form-group label {
        font-weight: 600;
        font-size: 13px;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }
    .upload-form-body .form-group label i { color: #64748b; font-size: 14px; }
    .upload-form-body .form-group label .required { color: #ef4444; }
    .upload-form-body .form-group input[type="text"] {
        width: 100%;
        max-width: 450px;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
        background: #ffffff;
    }
    .upload-form-body .form-group input[type="text"]:focus {
        outline: none;
        border-color: #0f3b5e;
        box-shadow: 0 0 0 4px rgba(15,59,94,0.06);
    }

    .file-drop-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfc;
        position: relative;
    }
    .file-drop-zone:hover { border-color: #0f3b5e; background: #f8fafc; }
    .file-drop-zone.dragover { border-color: #0f3b5e; background: #eff6ff; }
    .file-drop-zone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    .file-drop-content i {
        font-size: 40px;
        color: #94a3b8;
        display: block;
        margin-bottom: 8px;
    }
    .file-drop-content p { font-size: 15px; font-weight: 500; color: #1e293b; margin: 0; }
    .file-drop-content span { font-size: 13px; color: #94a3b8; display: block; margin-top: 2px; }
    .file-hint { font-size: 12px; color: #94a3b8; margin-top: 8px; }

    .file-preview {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        background: #f1f5f9;
        border-radius: 10px;
        margin-top: 12px;
        border: 1px solid #e2e8f0;
    }
    .file-preview img { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; }
    .file-preview .file-name { flex: 1; font-size: 13px; font-weight: 500; color: #1e293b; }
    .btn-remove-file {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 18px;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.3s;
    }
    .btn-remove-file:hover { color: #dc2626; background: #fef2f2; }

    .form-actions { margin-top: 20px; }
    .btn-upload {
        padding: 10px 32px;
        background: #0f3b5e;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-upload:hover { background: #0a2a44; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(15,59,94,0.25); }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f3b5e;
        margin: 24px 0 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title i { color: #eab308; }
    .section-title .count { font-size: 13px; font-weight: 400; color: #94a3b8; margin-left: auto; background: #f1f5f9; padding: 2px 14px; border-radius: 20px; }

    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-top: 12px; }
    .card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e8ecf1;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
    }
    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.08);
        border-color: #0f3b5e;
    }
    .card img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        background: #f1f5f9;
        border-bottom: 1px solid #e8ecf1;
    }
    .card .info {
        padding: 14px 16px 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .card .info .judul {
        font-weight: 600;
        font-size: 14px;
        color: #1e293b;
        margin-bottom: 2px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .card .info .urut {
        font-size: 12px;
        color: #94a3b8;
        margin-bottom: 10px;
    }
    .card .info .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 0;
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        cursor: pointer;
        margin-top: auto;
        width: 100%;
    }
    .card .info .btn-delete:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
        transform: scale(1.02);
    }
    .empty {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .empty i { font-size: 48px; opacity: 0.2; display: block; margin-bottom: 12px; }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.25s ease;
    }
    .modal-overlay.active { display: flex; }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .modal-box {
        background: #fff;
        border-radius: 20px;
        max-width: 420px;
        width: 100%;
        padding: 32px 28px 28px;
        text-align: center;
        box-shadow: 0 40px 80px rgba(0,0,0,0.3);
        animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-box .modal-icon {
        font-size: 48px;
        color: #dc2626;
        margin-bottom: 8px;
    }
    .modal-box h3 {
        font-size: 20px;
        font-weight: 700;
        color: #0f3b5e;
        margin-bottom: 6px;
    }
    .modal-box p {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 22px;
    }
    .modal-box .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    .modal-box .modal-actions .btn {
        padding: 10px 28px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 110px;
    }
    .modal-box .modal-actions .btn-cancel {
        background: #f1f5f9;
        color: #1e293b;
    }
    .modal-box .modal-actions .btn-cancel:hover {
        background: #e2e8f0;
    }
    .modal-box .modal-actions .btn-danger {
        background: #dc2626;
        color: #fff;
    }
    .modal-box .modal-actions .btn-danger:hover {
        background: #b91c1c;
        transform: scale(1.02);
    }

    @media (max-width: 768px) {
        .header { flex-direction: column; align-items: flex-start; }
        .grid { grid-template-columns: 1fr 1fr; }
        .modal-box { padding: 24px 20px; }
        .modal-box .modal-actions { flex-direction: column; }
        .modal-box .modal-actions .btn { width: 100%; }
    }
    @media (max-width: 480px) {
        .grid { grid-template-columns: 1fr; }
        .header h1 { font-size: 20px; }
        .card img { height: 140px; }
    }
</style>
@endsection

@section('content')
<div class="header">
    <div>
        <h1><i class="fas fa-images"></i> Kelola Slider</h1>
        <span class="info">Maksimal 6 foto, ukuran 1920x600 px, max 15MB</span>
    </div>
    <div class="admin-welcome">
        <i class="fas fa-user-circle"></i> {{ auth()->user()->nama_admin ?? 'Admin' }}
    </div>
</div>

@if(session('success'))
<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif
@if($errors->any())
<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
@endif

@if($total_slider < 6)
<div class="upload-form">
    <div class="upload-form-header">
        <div class="upload-form-title"><i class="fas fa-cloud-upload-alt"></i> Upload Gambar Baru</div>
        <div class="upload-form-badge"><i class="fas fa-images"></i> {{ $total_slider }}/6</div>
    </div>
    <form method="post" enctype="multipart/form-data" class="upload-form-body">
        @csrf
        <div class="form-group">
            <label for="judul_slide"><i class="fas fa-tag"></i> Judul Slide</label>
            <input type="text" id="judul_slide" name="judul" placeholder="Masukkan judul slide" value="Slide">
        </div>
        <div class="form-group file-upload-wrapper">
            <label for="gambar_slide"><i class="fas fa-image"></i> Pilih Gambar <span class="required">*</span></label>
            <div class="file-drop-zone" id="fileDropZone">
                <input type="file" id="gambar_slide" name="gambar" accept="image/*" required>
                <div class="file-drop-content">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Seret &amp; Lepas gambar di sini</p>
                    <span>atau klik untuk memilih file</span>
                    <div class="file-hint">Format: JPG, PNG, GIF, WEBP | Max: 15MB</div>
                </div>
            </div>
            <div id="filePreview" class="file-preview" style="display:none;">
                <img id="previewImage" src="#" alt="Preview">
                <span id="fileName" class="file-name"></span>
                <button type="button" id="removeFile" class="btn-remove-file"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-upload"><i class="fas fa-upload"></i> Upload Gambar</button>
        </div>
    </form>
</div>
@else
<div class="alert alert-warning">
    <i class="fas fa-info-circle"></i> Maksimal 6 slide sudah tercapai. <strong>Hapus salah satu slide di bawah</strong> untuk menambah yang baru.
</div>
@endif

<div class="section-title">
    <i class="fas fa-list"></i> Daftar Slide
    <span class="count">Total: {{ $total_slider }}/6</span>
</div>

@if($slides->isEmpty())
<div class="empty"><i class="fas fa-images"></i> Belum ada slide. Upload gambar pertama Anda!</div>
@else
<div class="grid">
    @foreach($slides as $slide)
    <div class="card">
        <img src="{{ asset('assets/img/slider/' . $slide->gambar) }}" alt="{{ $slide->judul }}">
        <div class="info">
            <div class="judul">{{ $slide->judul }}</div>
            <div class="urut">Urutan #{{ $slide->urutan }}</div>
            <a href="#" class="btn-delete" onclick="confirmDelete({{ $slide->id }}, '{{ $slide->judul }}'); return false;">
                <i class="fas fa-trash"></i> Hapus
            </a>
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <h3>Hapus Slide?</h3>
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus slide ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-actions">
            <button class="btn btn-cancel" onclick="closeModal()">Batal</button>
            <form id="deleteForm" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var fileInput = document.getElementById('gambar_slide');
var fileDropZone = document.getElementById('fileDropZone');
var filePreview = document.getElementById('filePreview');
var previewImage = document.getElementById('previewImage');
var fileName = document.getElementById('fileName');
var removeFileBtn = document.getElementById('removeFile');

if (fileInput) {
    fileInput.addEventListener('change', function(e) {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                fileName.textContent = file.name;
                filePreview.style.display = 'flex';
                fileDropZone.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
}
if (removeFileBtn) {
    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.style.display = 'none';
        fileDropZone.style.display = 'block';
    });
}
if (fileDropZone) {
    ['dragenter','dragover','dragleave','drop'].forEach(function(eventName) {
        fileDropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });
    ['dragenter','dragover'].forEach(function(eventName) {
        fileDropZone.addEventListener(eventName, function() {
            this.classList.add('dragover');
        }, false);
    });
    ['dragleave','drop'].forEach(function(eventName) {
        fileDropZone.addEventListener(eventName, function() {
            this.classList.remove('dragover');
        }, false);
    });
    fileDropZone.addEventListener('drop', function(e) {
        var dt = e.dataTransfer;
        var files = dt.files;
        if (files.length) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    }, false);
}

function confirmDelete(id, judul) {
    var modal = document.getElementById('deleteModal');
    var msg = document.getElementById('deleteMessage');
    var form = document.getElementById('deleteForm');
    
    msg.textContent = 'Apakah Anda yakin ingin menghapus slide "' + judul + '"? Tindakan ini tidak dapat dibatalkan.';
    form.action = '{{ url('admin/slider') }}/' + id;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>
@endsection