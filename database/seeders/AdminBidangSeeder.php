<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminBidangSeeder extends Seeder
{
    public function run(): void
    {
        $bidang = DB::table('bidang')->orderBy('id')->get(['id', 'kode_bidang', 'nama_bidang']);

        $users = $bidang->map(function ($b) {
            $username = 'admin_'.strtolower($b->kode_bidang);

            return [
                'username' => $username,
                'password' => Hash::make('password'),
                'nama_admin' => 'Admin '.$b->nama_bidang,
                'email' => $username.'@cekidot.go.id',
                'role' => 'admin_bidang',
                'divisi' => null,
                'bidang_id' => $b->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        DB::table('users')->insertOrIgnore([
            ['username' => 'superadmin', 'password' => Hash::make('password'), 'nama_admin' => 'Super Administrator', 'email' => 'superadmin@cekidot.go.id', 'role' => 'super_admin', 'divisi' => null, 'bidang_id' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('users')->insertOrIgnore($users);
    }
}
