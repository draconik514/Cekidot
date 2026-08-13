<?php

use App\Http\Controllers\Admin\Anggota\KelolaAnggotaController;
use App\Http\Controllers\Admin\ArsipSuratController;
use App\Http\Controllers\Admin\CapaianController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokumenAkipController;
use App\Http\Controllers\Admin\DokumenIkiController;
use App\Http\Controllers\Admin\FolderDokumenController;
use App\Http\Controllers\Admin\IkuController;
use App\Http\Controllers\Admin\ManajemenUserController;
use App\Http\Controllers\Admin\MonevController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SuratMasukController;
use App\Http\Controllers\Admin\UploadAnggotaController;
use App\Http\Controllers\AkipPublicController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CapaianPublicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IkiPublicController;
use App\Http\Controllers\IkuPublicController;
use App\Http\Controllers\MonevPublicController;
use App\Http\Controllers\SuratPublicController;
use Illuminate\Support\Facades\Route;

// ============================================================
// HOME — beranda publik (lihat perkembangan; aksi perlu login)
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ============================================================
// PUBLIC ROUTES
// ============================================================

Route::get('/iku', [IkuPublicController::class, 'index'])->name('iku.public');
Route::get('/akip', [AkipPublicController::class, 'index'])->name('akip.public');
Route::get('/iki', [IkiPublicController::class, 'index'])->name('iki.public');
Route::get('/capaian', [CapaianPublicController::class, 'index'])->name('capaian.public');
Route::get('/monev', [MonevPublicController::class, 'index'])->name('monev.public');
Route::get('/kirim-surat', [SuratPublicController::class, 'create'])->name('surat.create');
Route::post('/kirim-surat', [SuratPublicController::class, 'store'])->name('surat.store');

// ============================================================
// AUTH ROUTES
// ============================================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');

// ============================================================
// ANGGOTA ROUTES (Protected)
// ============================================================
Route::prefix('anggota')->middleware(['role:anggota'])->group(function () {
    Route::get('/', [AnggotaController::class, 'dashboard'])->name('anggota.dashboard');
    Route::post('/upload', [AnggotaController::class, 'store'])->name('anggota.upload');
    Route::get('/delete/{upload}', [AnggotaController::class, 'destroy'])->name('anggota.delete');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['admin'])->name('admin.dashboard');

Route::prefix('admin')->middleware(['admin'])->group(function () {

    // Slider
    Route::resource('slider', SliderController::class)->except(['show'])->names([
        'index' => 'admin.slider.index',
        'create' => 'admin.slider.create',
        'store' => 'admin.slider.store',
        'edit' => 'admin.slider.edit',
        'update' => 'admin.slider.update',
        'destroy' => 'admin.slider.destroy',
    ]);

    // Surat Masuk
    Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('admin.surat.index');
    Route::post('/surat-masuk/delete', [SuratMasukController::class, 'destroy'])->name('admin.surat.destroy');
    Route::get('/surat-masuk/tandai/{id}', [SuratMasukController::class, 'tandaiDibaca'])->name('admin.surat.tandai');

    // Dokumen AKIP
    Route::get('/akip', [DokumenAkipController::class, 'index'])->name('admin.akip.index');
    Route::post('/akip/upload', [DokumenAkipController::class, 'store'])->name('admin.akip.store');
    Route::post('/akip/edit', [DokumenAkipController::class, 'update'])->name('admin.akip.update');
    Route::get('/akip/delete/{id}', [DokumenAkipController::class, 'destroy'])->name('admin.akip.destroy');
    Route::get('/akip/toggle/{id}', [DokumenAkipController::class, 'toggleStatus'])->name('admin.akip.toggle');

    // Capaian Program
    Route::get('/capaian', [CapaianController::class, 'index'])->name('admin.capaian.index');
    Route::post('/capaian', [CapaianController::class, 'update'])->name('admin.capaian.update');
    Route::get('/capaian/reset', [CapaianController::class, 'reset'])->name('admin.capaian.reset');
    Route::get('/capaian/delete-file', [CapaianController::class, 'deleteFile'])->name('admin.capaian.delete.file');

    // Monev
    Route::get('/monev', [MonevController::class, 'index'])->name('admin.monev.index');
    Route::post('/monev', [MonevController::class, 'update'])->name('admin.monev.update');
    Route::post('/monev/delete', [MonevController::class, 'destroy'])->name('admin.monev.delete');

    // Manajemen User
    Route::get('/users', [ManajemenUserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [ManajemenUserController::class, 'store'])->name('admin.users.store');
    Route::post('/users/{user}', [ManajemenUserController::class, 'update'])->name('admin.users.update');
    Route::get('/users/{user}/toggle', [ManajemenUserController::class, 'toggleActive'])->name('admin.users.toggle');
    Route::get('/users/{user}/delete', [ManajemenUserController::class, 'destroy'])->name('admin.users.destroy');

    // Upload Anggota (admin lihat)
    Route::get('/upload-anggota', [UploadAnggotaController::class, 'index'])->name('admin.upload.index');
    Route::get('/upload-anggota/{upload}/delete', [UploadAnggotaController::class, 'destroy'])->name('admin.upload.destroy');
});

// ============================================================
// IKI, IKU, & FOLDER DOKUMEN (Super Admin, Admin Divisi, Admin Bidang)
// ============================================================
Route::prefix('admin')->middleware(['role:super_admin,admin_divisi,admin_bidang'])->group(function () {
    // Dokumen IKI
    Route::get('/iki', [DokumenIkiController::class, 'index'])->name('admin.iki.index');
    Route::post('/iki/upload', [DokumenIkiController::class, 'store'])->name('admin.iki.store');
    Route::post('/iki/edit', [DokumenIkiController::class, 'update'])->name('admin.iki.update');
    Route::get('/iki/delete/{id}', [DokumenIkiController::class, 'destroy'])->name('admin.iki.destroy');
    Route::get('/iki/toggle/{id}', [DokumenIkiController::class, 'toggleStatus'])->name('admin.iki.toggle');

    // IKU
    Route::get('/iku', [IkuController::class, 'index'])->name('admin.iku.index');
    Route::post('/iku', [IkuController::class, 'update'])->name('admin.iku.update');
    Route::post('/iku/upload-infografis', [IkuController::class, 'uploadInfografis'])->name('admin.iku.upload.infografis');
    Route::get('/iku/delete-infografis', [IkuController::class, 'deleteInfografis'])->name('admin.iku.delete.infografis');
    Route::get('/iku/delete-file', [IkuController::class, 'deleteFile'])->name('admin.iku.delete.file');

    // Folder Dokumen
    Route::get('/folder-dokumen', [FolderDokumenController::class, 'index'])->name('admin.folder.index');
    Route::post('/folder-dokumen', [FolderDokumenController::class, 'store'])->name('admin.folder.store');
    Route::post('/folder-dokumen/{folder}', [FolderDokumenController::class, 'update'])->name('admin.folder.update');
    Route::get('/folder-dokumen/{folder}/delete', [FolderDokumenController::class, 'destroy'])->name('admin.folder.destroy');

    // Kelola Anggota (Admin Bidang)
    Route::get('/anggota', [KelolaAnggotaController::class, 'index'])->name('admin.anggota.index');
    Route::post('/anggota', [KelolaAnggotaController::class, 'store'])->name('admin.anggota.store');
    Route::post('/anggota/{user}', [KelolaAnggotaController::class, 'update'])->name('admin.anggota.update');
    Route::get('/anggota/{user}/toggle', [KelolaAnggotaController::class, 'toggleActive'])->name('admin.anggota.toggle');
    Route::get('/anggota/{user}/delete', [KelolaAnggotaController::class, 'destroy'])->name('admin.anggota.destroy');
});

// ============================================================
// ARSIP SURAT (Super Admin & Admin Bidang)
// ============================================================
Route::prefix('admin')->middleware(['role:super_admin,admin_divisi'])->group(function () {
    Route::get('/arsip', [ArsipSuratController::class, 'index'])->name('admin.arsip.index');
    Route::post('/arsip/upload', [ArsipSuratController::class, 'store'])->name('admin.arsip.store');
    Route::get('/arsip/{arsip}/download', [ArsipSuratController::class, 'download'])->name('admin.arsip.download');
    Route::post('/arsip/delete', [ArsipSuratController::class, 'destroy'])->name('admin.arsip.destroy');
    Route::get('/arsip/cetak', [ArsipSuratController::class, 'cetak'])->name('admin.arsip.cetak');
});
