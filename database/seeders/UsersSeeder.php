<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $hash = '$2y$12$7M4.76qfUeT35jcpKCVXWewyYFUJyvELk9Zhhwk2wdLo9VzCnxIhW';
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('users')->insert([
            ['id'=>1,'username'=>'superadmin','password'=>$hash,'nama_admin'=>'Super Administrator','email'=>'superadmin@cekidot.go.id','role'=>'super_admin','divisi'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'username'=>'admin','password'=>$hash,'nama_admin'=>'Administrator','email'=>'admin@si-pari.go.id','role'=>'admin_divisi','divisi'=>'Program','created_at'=>now(),'updated_at'=>now()],
            ['id'=>3,'username'=>'admin_kepegawaian','password'=>$hash,'nama_admin'=>'Admin Kepegawaian','email'=>'admin.kepegawaian@cekidot.go.id','role'=>'admin_divisi','divisi'=>'Kepegawaian','created_at'=>now(),'updated_at'=>now()],
            ['id'=>4,'username'=>'admin_keuangan','password'=>$hash,'nama_admin'=>'Admin Keuangan','email'=>'admin.keuangan@cekidot.go.id','role'=>'admin_divisi','divisi'=>'Keuangan','created_at'=>now(),'updated_at'=>now()],
            ['id'=>5,'username'=>'admin_ekraf','password'=>$hash,'nama_admin'=>'Admin Ekraf','email'=>'admin.ekraf@cekidot.go.id','role'=>'admin_divisi','divisi'=>'Ekraf','created_at'=>now(),'updated_at'=>now()],
            ['id'=>6,'username'=>'admin_destinasi','password'=>$hash,'nama_admin'=>'Admin Destinasi','email'=>'admin.destinasi@cekidot.go.id','role'=>'admin_divisi','divisi'=>'Destinasi','created_at'=>now(),'updated_at'=>now()],
            ['id'=>7,'username'=>'admin_pemasaran','password'=>$hash,'nama_admin'=>'Admin Pemasaran','email'=>'admin.pemasaran@cekidot.go.id','role'=>'admin_divisi','divisi'=>'Pemasaran','created_at'=>now(),'updated_at'=>now()],
            ['id'=>8,'username'=>'admin_sdm','password'=>$hash,'nama_admin'=>'Admin SDM','email'=>'admin.sdm@cekidot.go.id','role'=>'admin_divisi','divisi'=>'Sdm','created_at'=>now(),'updated_at'=>now()],
            ['id'=>9,'username'=>'anggota_program','password'=>$hash,'nama_admin'=>'Anggota Program','email'=>'anggota.program@cekidot.go.id','role'=>'anggota','divisi'=>'Program','created_at'=>now(),'updated_at'=>now()],
            ['id'=>10,'username'=>'anggota_kepegawaian','password'=>$hash,'nama_admin'=>'Anggota Kepegawaian','email'=>'anggota.kepegawaian@cekidot.go.id','role'=>'anggota','divisi'=>'Kepegawaian','created_at'=>now(),'updated_at'=>now()],
            ['id'=>11,'username'=>'anggota_keuangan','password'=>$hash,'nama_admin'=>'Anggota Keuangan','email'=>'anggota.keuangan@cekidot.go.id','role'=>'anggota','divisi'=>'Keuangan','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
