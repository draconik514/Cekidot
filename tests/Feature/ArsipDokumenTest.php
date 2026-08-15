<?php

use App\Models\FolderDokumen;
use App\Models\LogAktivitas;
use App\Models\UploadAnggota;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::create([
        'username' => 'staf_kepegawaian',
        'nama_admin' => 'Staf Kepegawaian',
        'password' => 'secret123',
        'role' => 'anggota',
        'divisi' => 'Kepegawaian',
    ]);

    $this->parent = FolderDokumen::create([
        'nama' => 'Kepegawaian', 'divisi' => 'Kepegawaian', 'status' => 'aktif', 'created_by' => $this->user->id,
    ]);

    $this->child = FolderDokumen::create([
        'nama' => 'Surat Masuk', 'divisi' => 'Kepegawaian', 'status' => 'aktif',
        'parent_id' => $this->parent->id, 'created_by' => $this->user->id,
    ]);

    $this->folderLain = FolderDokumen::create([
        'nama' => 'Keuangan', 'divisi' => 'Keuangan', 'status' => 'aktif', 'created_by' => $this->user->id,
    ]);

    $this->actingAs($this->user);
});

test('anggota hanya melihat folder di divisinya', function () {
    $response = $this->get(route('anggota.dashboard'));

    $response->assertOk();
    $response->assertSee($this->parent->nama);
    $response->assertDontSee($this->folderLain->nama);
});

test('upload menolak file berbahaya dan memblokir folder di luar divisi', function () {
    Storage::fake('public');

    $php = UploadedFile::fake()->create('script.php', 10);
    $this->post(route('anggota.upload'), [
        'folder_id' => $this->child->id,
        'judul' => 'Dokumen PHP',
        'tanggal_upload' => now()->toDateString(),
        'file_dokumen' => $php,
    ])->assertSessionHasErrors('file_dokumen');
    expect(UploadAnggota::count())->toBe(0);

    $pdf = UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf');
    $this->post(route('anggota.upload'), [
        'folder_id' => $this->folderLain->id,
        'judul' => 'Upload ke divisi lain',
        'tanggal_upload' => now()->toDateString(),
        'file_dokumen' => $pdf,
    ])->assertStatus(403);
    expect(UploadAnggota::count())->toBe(0);
});

test('upload valid tercatat dan masuk log', function () {
    Storage::fake('public');

    $pdf = UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf');
    $this->post(route('anggota.upload'), [
        'folder_id' => $this->child->id,
        'judul' => 'Surat Edaran',
        'tanggal_upload' => now()->toDateString(),
        'file_dokumen' => $pdf,
    ])->assertSessionHas('success');

    expect(UploadAnggota::count())->toBe(1);
    expect(LogAktivitas::where('aksi', 'upload')->count())->toBe(1);
});

test('unduh mencatat log aktivitas', function () {
    Storage::fake('public');

    $pdf = UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf');
    $this->post(route('anggota.upload'), [
        'folder_id' => $this->child->id,
        'judul' => 'Surat Edaran',
        'tanggal_upload' => now()->toDateString(),
        'file_dokumen' => $pdf,
    ]);

    $upload = UploadAnggota::first();

    $this->get(route('anggota.download', $upload->id))->assertOk();
    expect(LogAktivitas::where('aksi', 'unduh')->count())->toBe(1);
});
