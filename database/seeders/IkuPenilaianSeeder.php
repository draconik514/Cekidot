<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IkuPenilaianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('iku_penilaian')->truncate();
        DB::table('iku_penilaian')->insert([
            ['id'=>14,'kategori'=>'Makan Minum','nama_kriteria'=>'Penyediaan Akomodasi dan Makan Minum','bobot'=>0,'target'=>0,'nilai'=>1296.64,'link_sumber'=>null,'file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-07 16:35:19','tahun'=>'2025'],
            ['id'=>15,'kategori'=>'Makan Minum','nama_kriteria'=>'PDRB ADHB Sulawesi Tengah','bobot'=>0,'target'=>0,'nilai'=>415477.22,'link_sumber'=>null,'file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-07 16:35:19','tahun'=>'2025'],
            ['id'=>16,'kategori'=>'Makan Minum','nama_kriteria'=>'Sumber Data','bobot'=>0,'target'=>0,'nilai'=>0,'link_sumber'=>'https://sulteng.bps.go.id/id/publication/2026/02/27/5b520056cb0f26ef3736bc74/provinsi-sulawesi-tengah-dalam-angka-2026.html','file_sumber'=>'','realisasi'=>0,'created_at'=>'2026-07-07 16:35:19','tahun'=>'2025'],
            ['id'=>19,'kategori'=>'Ekraf','nama_kriteria'=>'Sumber Data','bobot'=>0,'target'=>0,'nilai'=>0,'link_sumber'=>'https://sulteng.bps.go.id/id/publication/2026/02/27/5b520056cb0f26ef3736bc74/provinsi-sulawesi-tengah-dalam-angka-2026.html','file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-07 16:36:09','tahun'=>'2025'],
            ['id'=>28,'kategori'=>'Ekraf','nama_kriteria'=>'PDRB ADHB Sulawesi Tengah','bobot'=>0,'target'=>0,'nilai'=>415477.22,'link_sumber'=>null,'file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-08 15:43:26','tahun'=>'2025'],
            ['id'=>29,'kategori'=>'Wisatawan','nama_kriteria'=>'Sumber Data','bobot'=>0,'target'=>0,'nilai'=>0,'link_sumber'=>'https://sulteng.bps.go.id/id/publication/2026/02/27/5b520056cb0f26ef3736bc74/provinsi-sulawesi-tengah-dalam-angka-2026.html','file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-12 23:35:37','tahun'=>'2025'],
            ['id'=>30,'kategori'=>'Makan Minum','nama_kriteria'=>'Penyediaan Akomodasi dan Makan Minum','bobot'=>0,'target'=>0,'nilai'=>0,'link_sumber'=>null,'file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-13 15:55:54','tahun'=>'2026'],
            ['id'=>31,'kategori'=>'Makan Minum','nama_kriteria'=>'PDRB ADHB Sulawesi Tengah','bobot'=>0,'target'=>0,'nilai'=>0,'link_sumber'=>null,'file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-13 15:55:54','tahun'=>'2026'],
            ['id'=>33,'kategori'=>'Makan Minum','nama_kriteria'=>'Penyediaan Akomodasi dan Makan Minum','bobot'=>0,'target'=>0,'nilai'=>0,'link_sumber'=>null,'file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-13 16:02:56','tahun'=>'2027'],
            ['id'=>34,'kategori'=>'Makan Minum','nama_kriteria'=>'PDRB ADHB Sulawesi Tengah','bobot'=>0,'target'=>0,'nilai'=>0,'link_sumber'=>null,'file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-13 16:02:56','tahun'=>'2027'],
            ['id'=>35,'kategori'=>'Makan Minum','nama_kriteria'=>'Penyediaan Akomodasi dan Makan Minum','bobot'=>0,'target'=>0,'nilai'=>0,'link_sumber'=>null,'file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-13 16:03:09','tahun'=>'2028'],
            ['id'=>36,'kategori'=>'Makan Minum','nama_kriteria'=>'PDRB ADHB Sulawesi Tengah','bobot'=>0,'target'=>0,'nilai'=>0,'link_sumber'=>null,'file_sumber'=>null,'realisasi'=>0,'created_at'=>'2026-07-13 16:03:09','tahun'=>'2028'],
        ]);
    }
}
