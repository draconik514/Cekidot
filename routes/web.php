<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IkuPublicController;
use App\Http\Controllers\AkipPublicController;
use App\Http\Controllers\IkiPublicController;
use App\Http\Controllers\CapaianPublicController;
use App\Http\Controllers\MonevPublicController;
use App\Http\Controllers\SuratPublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SuratMasukController;
use App\Http\Controllers\Admin\DokumenAkipController;
use App\Http\Controllers\Admin\DokumenIkiController;
use App\Http\Controllers\Admin\IkuController;
use App\Http\Controllers\Admin\CapaianController;
use App\Http\Controllers\Admin\MonevController;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

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
// ADMIN ROUTES (Protected)
// ============================================================
Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Slider
    Route::resource('slider', SliderController::class)->except(['show'])->names([
        'index'   => 'admin.slider.index',
        'create'  => 'admin.slider.create',
        'store'   => 'admin.slider.store',
        'edit'    => 'admin.slider.edit',
        'update'  => 'admin.slider.update',
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

    // Capaian Program
    Route::get('/capaian', [CapaianController::class, 'index'])->name('admin.capaian.index');
    Route::post('/capaian', [CapaianController::class, 'update'])->name('admin.capaian.update');
    Route::get('/capaian/reset', [CapaianController::class, 'reset'])->name('admin.capaian.reset');
    Route::get('/capaian/delete-file', [CapaianController::class, 'deleteFile'])->name('admin.capaian.delete.file');

    // Monev
    Route::get('/monev', [MonevController::class, 'index'])->name('admin.monev.index');
    Route::post('/monev', [MonevController::class, 'update'])->name('admin.monev.update');
    Route::post('/monev/delete', [MonevController::class, 'destroy'])->name('admin.monev.delete');
});