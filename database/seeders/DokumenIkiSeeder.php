<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DokumenIkiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dokumen_iki')->truncate();
        DB::table('dokumen_iki')->insert([
            ['judul'=>'MPH KADIS','file_dokumen'=>'1784554204_MPHKadis.pdf','tipe_konten'=>'file','file_type'=>'pdf','file_size'=>0,'tahun'=>2026,'status'=>'aktif','urutan'=>1,'created_at'=>'2026-07-20 21:30:05','updated_at'=>'2026-07-20 21:30:05'],
        ]);
    }
}
