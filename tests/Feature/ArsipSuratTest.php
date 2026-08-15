<?php

use App\Models\ArsipSurat;
use App\Models\Bidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->bidang = Bidang::create(['nama_bidang' => 'Kepegawaian', 'kode_bidang' => 'KEP']);
    $this->bidang2 = Bidang::create(['nama_bidang' => 'Keuangan', 'kode_bidang' => 'KEU']);

    $this->superAdmin = User::factory()->superAdmin()->create();
    $this->adminKep = User::factory()->adminBidang($this->bidang->id)->create();
    $this->adminKeu = User::factory()->adminBidang($this->bidang2->id)->create();
});

it('mengarahkan admin bidang ke halaman arsip setelah login', function () {
    $this->post('/login', [
        'username' => $this->adminKep->username,
        'password' => 'password',
    ])->assertRedirect(route('admin.arsip.index'));
});

it('mengarahkan super admin ke dashboard admin setelah login', function () {
    $this->post('/login', [
        'username' => $this->superAdmin->username,
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));
});

it('halaman cetak laporan dapat diakses super admin', function () {
    ArsipSurat::create([
        'bidang_id' => $this->bidang->id,
        'nomor_surat' => '011/KEP/2026',
        'tanggal_surat' => '2026-08-01',
        'perihal' => 'Surat Kepegawaian',
        'jenis_surat' => 'masuk',
        'file_path' => 'KEP/2026/08/surat.pdf',
        'file_name' => 'surat.pdf',
        'uploaded_by' => $this->adminKep->id,
    ]);

    $response = $this->actingAs($this->superAdmin)->get(route('admin.arsip.cetak'));
    $response->assertOk();
    $response->assertSee('011/KEP/2026');
});

it('menolak akses halaman arsip oleh role anggota', function () {
    $anggota = User::factory()->create();

    $this->actingAs($anggota)->get(route('admin.arsip.index'))->assertForbidden();
});

it('halaman manajemen user tetap berfungsi untuk super admin', function () {
    $this->actingAs($this->superAdmin)->get(route('admin.users.index'))->assertOk();
});

it('menolak login user yang dinonaktifkan', function () {
    $user = User::factory()->adminBidang($this->bidang->id)->inactive()->create();

    $this->post('/login', [
        'username' => $user->username,
        'password' => 'password',
    ])->assertSessionHas('error');

    $this->assertGuest();
});

it('hanya menampilkan arsip bidang sendiri untuk admin bidang', function () {
    $arsipLain = ArsipSurat::create([
        'bidang_id' => $this->bidang2->id,
        'nomor_surat' => '001/KEU/2026',
        'tanggal_surat' => '2026-08-01',
        'perihal' => 'Surat Keuangan',
        'jenis_surat' => 'masuk',
        'file_path' => 'KEU/2026/08/surat.pdf',
        'file_name' => 'surat.pdf',
        'uploaded_by' => $this->adminKeu->id,
    ]);

    $response = $this->actingAs($this->adminKep)->get(route('admin.arsip.index'));
    $response->assertOk();
    $response->assertDontSee('001/KEU/2026');
});

it('menampilkan semua arsip untuk super admin', function () {
    ArsipSurat::create([
        'bidang_id' => $this->bidang->id,
        'nomor_surat' => '001/KEP/2026',
        'tanggal_surat' => '2026-08-01',
        'perihal' => 'Surat Kepegawaian',
        'jenis_surat' => 'masuk',
        'file_path' => 'KEP/2026/08/surat.pdf',
        'file_name' => 'surat.pdf',
        'uploaded_by' => $this->adminKep->id,
    ]);
    ArsipSurat::create([
        'bidang_id' => $this->bidang2->id,
        'nomor_surat' => '001/KEU/2026',
        'tanggal_surat' => '2026-08-01',
        'perihal' => 'Surat Keuangan',
        'jenis_surat' => 'keluar',
        'file_path' => 'KEU/2026/08/surat.pdf',
        'file_name' => 'surat.pdf',
        'uploaded_by' => $this->adminKeu->id,
    ]);

    $response = $this->actingAs($this->superAdmin)->get(route('admin.arsip.index'));
    $response->assertOk();
    $response->assertSee('001/KEP/2026');
    $response->assertSee('001/KEU/2026');
});

it('admin bidang dapat mengunggah arsip untuk bidangnya sendiri', function () {
    Storage::fake('arsip');

    $response = $this->actingAs($this->adminKep)->post(route('admin.arsip.store'), [
        'nomor_surat' => '002/KEP/2026',
        'tanggal_surat' => '2026-08-05',
        'perihal' => 'Undangan Rapat',
        'jenis_surat' => 'masuk',
        'file_surat' => UploadedFile::fake()->create('undangan.pdf', 100),
    ]);

    $response->assertRedirect(route('admin.arsip.index'));
    $this->assertDatabaseHas('arsip_surat', [
        'bidang_id' => $this->bidang->id,
        'nomor_surat' => '002/KEP/2026',
        'uploaded_by' => $this->adminKep->id,
    ]);
});

it('menolak upload dengan format file tidak didukung', function () {
    Storage::fake('arsip');

    $response = $this->actingAs($this->adminKep)->post(route('admin.arsip.store'), [
        'nomor_surat' => '003/KEP/2026',
        'tanggal_surat' => '2026-08-05',
        'perihal' => 'File tidak valid',
        'jenis_surat' => 'masuk',
        'file_surat' => UploadedFile::fake()->create('dokumen.exe', 100),
    ]);

    $response->assertSessionHasErrors('file_surat');
});

it('menolak upload oleh super admin (hanya baca)', function () {
    $response = $this->actingAs($this->superAdmin)->post(route('admin.arsip.store'), [
        'nomor_surat' => '004/KEP/2026',
        'tanggal_surat' => '2026-08-05',
        'perihal' => 'Coba upload',
        'jenis_surat' => 'masuk',
        'file_surat' => UploadedFile::fake()->create('coba.pdf', 100),
    ]);

    $response->assertForbidden();
});

it('menolak admin bidang mengakses arsip bidang lain secara langsung', function () {
    $arsipKeu = ArsipSurat::create([
        'bidang_id' => $this->bidang2->id,
        'nomor_surat' => '005/KEU/2026',
        'tanggal_surat' => '2026-08-01',
        'perihal' => 'Surat Keuangan',
        'jenis_surat' => 'internal',
        'file_path' => 'KEU/2026/08/surat.pdf',
        'file_name' => 'surat.pdf',
        'uploaded_by' => $this->adminKeu->id,
    ]);

    $response = $this->actingAs($this->adminKep)->get(route('admin.arsip.download', $arsipKeu->id));
    $response->assertForbidden();
});

it('admin bidang hanya dapat menghapus arsip milik sendiri', function () {
    $arsipKep = ArsipSurat::create([
        'bidang_id' => $this->bidang->id,
        'nomor_surat' => '006/KEP/2026',
        'tanggal_surat' => '2026-08-01',
        'perihal' => 'Surat Kepegawaian',
        'jenis_surat' => 'masuk',
        'file_path' => 'KEP/2026/08/surat.pdf',
        'file_name' => 'surat.pdf',
        'uploaded_by' => $this->adminKep->id,
    ]);

    $arsipMilikOrangLain = ArsipSurat::create([
        'bidang_id' => $this->bidang->id,
        'nomor_surat' => '007/KEP/2026',
        'tanggal_surat' => '2026-08-02',
        'perihal' => 'Surat Kepegawaian Lain',
        'jenis_surat' => 'keluar',
        'file_path' => 'KEP/2026/08/surat2.pdf',
        'file_name' => 'surat2.pdf',
        'uploaded_by' => $this->superAdmin->id,
    ]);

    $this->actingAs($this->adminKep)->post(route('admin.arsip.destroy'), ['delete_id' => $arsipKep->id]);
    expect($arsipKep->fresh()->is_deleted)->toBeTrue();

    $response = $this->actingAs($this->adminKep)->post(route('admin.arsip.destroy'), ['delete_id' => $arsipMilikOrangLain->id]);
    $response->assertForbidden();
});

it('menolak super admin menghapus arsip', function () {
    $arsip = ArsipSurat::create([
        'bidang_id' => $this->bidang->id,
        'nomor_surat' => '008/KEP/2026',
        'tanggal_surat' => '2026-08-01',
        'perihal' => 'Surat Kepegawaian',
        'jenis_surat' => 'masuk',
        'file_path' => 'KEP/2026/08/surat.pdf',
        'file_name' => 'surat.pdf',
        'uploaded_by' => $this->adminKep->id,
    ]);

    $response = $this->actingAs($this->superAdmin)->post(route('admin.arsip.destroy'), ['delete_id' => $arsip->id]);
    $response->assertForbidden();
});

it('pencarian arsip sesuai keyword', function () {
    ArsipSurat::create([
        'bidang_id' => $this->bidang->id,
        'nomor_surat' => '009/KEP/2026',
        'tanggal_surat' => '2026-08-01',
        'perihal' => 'SPK Pembayaran',
        'jenis_surat' => 'masuk',
        'file_path' => 'KEP/2026/08/surat.pdf',
        'file_name' => 'surat.pdf',
        'uploaded_by' => $this->adminKep->id,
    ]);
    ArsipSurat::create([
        'bidang_id' => $this->bidang->id,
        'nomor_surat' => '010/KEP/2026',
        'tanggal_surat' => '2026-08-01',
        'perihal' => 'Undangan Sosialisasi',
        'jenis_surat' => 'keluar',
        'file_path' => 'KEP/2026/08/surat2.pdf',
        'file_name' => 'surat2.pdf',
        'uploaded_by' => $this->adminKep->id,
    ]);

    $response = $this->actingAs($this->adminKep)->get(route('admin.arsip.index', ['search' => 'SPK']));
    $response->assertOk();
    $response->assertSee('009/KEP/2026');
    $response->assertDontSee('010/KEP/2026');
});
