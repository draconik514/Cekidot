<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IkuEkrafSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('iku_ekraf')->truncate();
        DB::table('iku_ekraf')->insert([
            ['id'=>154,'kategori'=>'Ekraf','sektor'=>'Industri Makanan dan Minuman (C.2)','koofisien'=>0.75,'nilai_bps'=>7240.87,'jumlah_rp'=>7240870000000.00,'hasil_penjumlahan'=>5430652500000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>155,'kategori'=>'Ekraf','sektor'=>'Industri Tekstil dan Pakaian Jadi (C.4)','koofisien'=>0.85,'nilai_bps'=>39.96,'jumlah_rp'=>39960000000.00,'hasil_penjumlahan'=>33966000000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>156,'kategori'=>'Ekraf','sektor'=>'Industri Kulit, Barang dari Kulit, dan Alas Kaki (C.5)','koofisien'=>0.50,'nilai_bps'=>22.05,'jumlah_rp'=>22050000000.00,'hasil_penjumlahan'=>11025000000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>157,'kategori'=>'Ekraf','sektor'=>'Industri Kayu, Barang dari Kayu dan Gabus; dan Barang Anyaman dari Bambu, Rotan, dan Sejenisnya (C.6)','koofisien'=>0.90,'nilai_bps'=>1778.96,'jumlah_rp'=>1778960000000.00,'hasil_penjumlahan'=>1601064000000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>158,'kategori'=>'Ekraf','sektor'=>'Industri Kertas dan Barang dari Kertas; Percetakan dan Reproduksi Media Rekaman (C.7)','koofisien'=>0.70,'nilai_bps'=>134.98,'jumlah_rp'=>134980000000.00,'hasil_penjumlahan'=>94486000000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>159,'kategori'=>'Ekraf','sektor'=>'Industri Furnitur (C.15)','koofisien'=>0.90,'nilai_bps'=>250.83,'jumlah_rp'=>250830000000.00,'hasil_penjumlahan'=>225747000000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>160,'kategori'=>'Ekraf','sektor'=>'Penyediaan Makan Minum (I.2)','koofisien'=>0.80,'nilai_bps'=>960.48,'jumlah_rp'=>960480000000.00,'hasil_penjumlahan'=>768384000000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>161,'kategori'=>'Ekraf','sektor'=>'Informasi dan Komunikasi (J)','koofisien'=>0.45,'nilai_bps'=>8231.35,'jumlah_rp'=>8231350000000.00,'hasil_penjumlahan'=>3704107500000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>162,'kategori'=>'Ekraf','sektor'=>'Jasa Perusahaan (M,N)','koofisien'=>0.45,'nilai_bps'=>594.38,'jumlah_rp'=>594380000000.00,'hasil_penjumlahan'=>267471000000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
            ['id'=>163,'kategori'=>'Ekraf','sektor'=>'Jasa Lainnya (R,S,T,U)','koofisien'=>0.60,'nilai_bps'=>1908.08,'jumlah_rp'=>1908080000000.00,'hasil_penjumlahan'=>1144848000000.00,'created_at'=>'2026-07-19 00:49:25','updated_at'=>'2026-07-19 00:49:25','tahun'=>'2025'],
        ]);
    }
}
