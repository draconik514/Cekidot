<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonevSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('monev_bulanan')->truncate();
        DB::table('monev_bulanan')->insert([
            ['tahun'=>2025,'bulan'=>1,'program'=>'-','target'=>99.99,'realisasi'=>99.99,'keterangan'=>'Januari 2025','created_at'=>'2026-07-29 02:04:08','updated_at'=>'2026-07-29 02:04:08'],
            ['tahun'=>2025,'bulan'=>1,'program'=>'-','target'=>99.99,'realisasi'=>1.78,'keterangan'=>'Januari 2025','created_at'=>'2026-07-29 02:04:08','updated_at'=>'2026-07-29 02:04:08'],
        ]);

        DB::table('monev_akumulasi')->truncate();
        DB::table('monev_akumulasi')->insert([
            ['tahun'=>2025,'program'=>'-','target_akhir'=>99.99,'realisasi_akhir'=>99.99,'persentase'=>125.83,'predikat'=>'ISTIMEWA','keterangan'=>'Efisien','created_at'=>'2026-07-29 02:04:08','updated_at'=>'2026-07-29 02:04:08'],
            ['tahun'=>2025,'program'=>'-','target_akhir'=>99.99,'realisasi_akhir'=>1.78,'persentase'=>1.78,'predikat'=>'ISTIMEWA','keterangan'=>'Tidak Efisien','created_at'=>'2026-07-29 02:04:08','updated_at'=>'2026-07-29 02:04:08'],
        ]);
    }
}
