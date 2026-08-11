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
            ['id'=>1,'username'=>'superadmin','password'=>'$2y$10$1S53bFmDLtwICcP9ZfGu6uS6xv6lpt2MCU3dZJPwep0RbW.kKtxiC','nama_admin'=>'Super Administrator','email'=>'superadmin@cekidot.go.id','role'=>'super_admin','divisi'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'username'=>'admin','password'=>'$2y$10$1S53bFmDLtwICcP9ZfGu6uS6xv6lpt2MCU3dZJPwep0RbW.kKtxiC','nama_admin'=>'Administrator','email'=>'admin@si-pari.go.id','role'=>'admin_divisi','divisi'=>'Program','created_at'=>'2026-07-05 19:55:59','updated_at'=>'2026-07-05 19:55:59'],
        ]);
    }
}
