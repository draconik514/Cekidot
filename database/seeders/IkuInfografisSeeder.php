<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IkuInfografisSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('iku_infografis')->truncate();
        DB::table('iku_infografis')->insert([
            ['id'=>1,'kategori'=>'Makan Minum','file_name'=>'infografis_Makan Minum_1784136568.png','created_at'=>'2026-07-07 23:20:24','updated_at'=>'2026-07-16 01:29:28'],
            ['id'=>2,'kategori'=>'Mancanegara','file_name'=>'','created_at'=>'2026-07-07 23:20:24','updated_at'=>'2026-07-07 23:20:24'],
            ['id'=>3,'kategori'=>'Ekraf','file_name'=>'infografis_Ekraf_1784035509.png','created_at'=>'2026-07-07 23:20:24','updated_at'=>'2026-07-14 21:25:09'],
            ['id'=>4,'kategori'=>'Wisatawan','file_name'=>'infografis_Wisatawan_1784035349.png','created_at'=>'2026-07-12 15:34:34','updated_at'=>'2026-07-14 21:22:29'],
        ]);
    }
}
