<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key check sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            UsersSeeder::class,
            SliderSeeder::class,
            IkuPenilaianSeeder::class,
            IkuEkrafSeeder::class,
            IkuPdrbSeeder::class,
            IkuWisatawanSeeder::class,
            IkuInfografisSeeder::class,
            DokumenAkipSeeder::class,
            DokumenIkiSeeder::class,
            CapaianProgramSeeder::class,
            MonevSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
