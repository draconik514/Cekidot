<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IkuPdrbSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('iku_pdrb')->truncate();
        DB::table('iku_pdrb')->insert([
            ['id'=>1,'kategori'=>'Makan Minum','target'=>0.34,'realitas'=>0.31,'capaian'=>91.84,'created_at'=>'2026-07-07 19:24:02','updated_at'=>'2026-07-16 15:34:55','tahun'=>'2025'],
            ['id'=>2,'kategori'=>'Ekraf','target'=>3.76,'realitas'=>0.00,'capaian'=>0.00,'created_at'=>'2026-07-08 15:19:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>3,'kategori'=>'Wisatawan','target'=>25000.00,'realitas'=>28165.00,'capaian'=>112.66,'created_at'=>'2026-07-13 03:10:56','updated_at'=>'2026-07-27 12:41:37','tahun'=>'2025'],
            ['id'=>4,'kategori'=>'Makan Minum','target'=>0.00,'realitas'=>0.00,'capaian'=>0.00,'created_at'=>'2026-07-13 15:55:54','updated_at'=>'2026-07-13 16:03:04','tahun'=>'2026'],
            ['id'=>6,'kategori'=>'Wisatawan','target'=>0.00,'realitas'=>0.00,'capaian'=>0.00,'created_at'=>'2026-07-13 15:56:13','updated_at'=>'2026-07-13 15:56:13','tahun'=>'2026'],
            ['id'=>7,'kategori'=>'Makan Minum','target'=>0.00,'realitas'=>0.00,'capaian'=>0.00,'created_at'=>'2026-07-13 16:02:56','updated_at'=>'2026-07-13 16:02:56','tahun'=>'2027'],
            ['id'=>8,'kategori'=>'Makan Minum','target'=>0.00,'realitas'=>0.00,'capaian'=>0.00,'created_at'=>'2026-07-13 16:03:09','updated_at'=>'2026-07-13 16:03:09','tahun'=>'2028'],
            ['id'=>9,'kategori'=>'Wisatawan','target'=>0.00,'realitas'=>0.00,'capaian'=>0.00,'created_at'=>'2026-07-13 16:03:12','updated_at'=>'2026-07-13 16:03:12','tahun'=>'2028'],
        ]);
    }
}
