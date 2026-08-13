<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bidang')->insertOrIgnore([
            ['id' => 1, 'nama_bidang' => 'Kepegawaian', 'kode_bidang' => 'KEP', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_bidang' => 'Keuangan', 'kode_bidang' => 'KEU', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_bidang' => 'Program', 'kode_bidang' => 'PRO', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama_bidang' => 'Ekraf', 'kode_bidang' => 'EKR', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama_bidang' => 'Destinasi', 'kode_bidang' => 'DES', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama_bidang' => 'Pemasaran', 'kode_bidang' => 'PEM', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama_bidang' => 'SDM', 'kode_bidang' => 'SDM', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
