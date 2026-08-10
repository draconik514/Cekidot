<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sliders')->truncate();
        DB::table('sliders')->insert([
            ['id' => 32, 'gambar' => '1785256060_Cekidot.png', 'judul' => 'Slide', 'urutan' => 1, 'status' => 'aktif', 'created_at' => '2026-07-29 00:27:40'],
        ]);
    }
}
