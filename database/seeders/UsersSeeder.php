<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'id' => 2,
            'username' => 'admin',
            'password' => '$2y$10$1S53bFmDLtwICcP9ZfGu6uS6xv6lpt2MCU3dZJPwep0RbW.kKtxiC',
            'nama_admin' => 'Administrator',
            'email' => 'admin@si-pari.go.id',
            'created_at' => '2026-07-05 19:55:59',
        ]);
    }
}
