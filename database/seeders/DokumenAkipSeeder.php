<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DokumenAkipSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dokumen_akip')->truncate();
        DB::table('dokumen_akip')->insert([
            ['judul'=>'RENSTRA 2025-2029','file_dokumen'=>'1784601893_1784528533_RENSTRARevisiDISPAR2025-2029.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>7437028,'tahun'=>2026,'status'=>'aktif','urutan'=>1,'created_at'=>'2026-07-21 10:44:53','updated_at'=>'2026-07-21 10:44:53'],
            ['judul'=>'RENJA 2026 (AWAL)','file_dokumen'=>'1784601986_1784528635_DISPARRENJA2026v3.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>6185433,'tahun'=>2026,'status'=>'aktif','urutan'=>2,'created_at'=>'2026-07-21 10:46:26','updated_at'=>'2026-07-21 10:46:26'],
            ['judul'=>'SK INDIKATOR KINERJA UTAMA 2026','file_dokumen'=>'1784602037_1784529825_SKIKU.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>494113,'tahun'=>2026,'status'=>'aktif','urutan'=>3,'created_at'=>'2026-07-21 10:47:17','updated_at'=>'2026-07-21 10:47:17'],
            ['judul'=>'DPA 2026 (AWAL)','file_dokumen'=>'1784602073_1784546347_01DPAPenetapan-09Januari.rar','tipe_konten'=>'file','file_type'=>'rar','file_size'=>3913918,'tahun'=>2026,'status'=>'aktif','urutan'=>4,'created_at'=>'2026-07-21 10:47:53','updated_at'=>'2026-07-21 10:47:53'],
            ['judul'=>'SK DEFINISI OPERASIONAL 2026','file_dokumen'=>'1784602105_1784595011_SKDOIKUPROGAMDANKEGIATANDISPAR2026v2.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>2400684,'tahun'=>2026,'status'=>'aktif','urutan'=>5,'created_at'=>'2026-07-21 10:48:25','updated_at'=>'2026-07-21 10:48:25'],
            ['judul'=>'STRUKTUR ORGANISASI DAN TUGAS POKOK','file_dokumen'=>'1784602136_1784547324_StrukturOrganisasidanTugasFungsiDinasPariwisata.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>3507468,'tahun'=>2026,'status'=>'aktif','urutan'=>6,'created_at'=>'2026-07-21 10:48:56','updated_at'=>'2026-07-21 10:48:56'],
            ['judul'=>'RENCANA AKSI 2026','file_dokumen'=>'1784602192_1784547441_DISPARRENCANAAKSITAHUN2026v3.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>114317,'tahun'=>2026,'status'=>'aktif','urutan'=>7,'created_at'=>'2026-07-21 10:49:52','updated_at'=>'2026-07-21 10:49:52'],
            ['judul'=>'POHON KINERJA','file_dokumen'=>'1784602217_1784548019_Pohon_Kinerja.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>225851,'tahun'=>2026,'status'=>'aktif','urutan'=>8,'created_at'=>'2026-07-21 10:50:17','updated_at'=>'2026-07-21 10:50:17'],
            ['judul'=>'CASCADING','file_dokumen'=>'1784602239_1784548272_CASCADING.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>2603292,'tahun'=>2026,'status'=>'aktif','urutan'=>9,'created_at'=>'2026-07-21 10:50:39','updated_at'=>'2026-07-21 10:50:39'],
            ['judul'=>'CROSSCUTTING','file_dokumen'=>'1784602268_1784552400_Cross-Cutting-Dinas-Pariwisata.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>213701,'tahun'=>2026,'status'=>'aktif','urutan'=>10,'created_at'=>'2026-07-21 10:51:08','updated_at'=>'2026-07-21 10:51:08'],
            ['judul'=>'PERJANJIAN KINERJA 2026 (AWAL)','file_dokumen'=>'1784604355_PK2026DISPARv3.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>14489074,'tahun'=>2026,'status'=>'aktif','urutan'=>11,'created_at'=>'2026-07-21 11:25:55','updated_at'=>'2026-07-21 11:25:55'],
        ]);
    }
}
