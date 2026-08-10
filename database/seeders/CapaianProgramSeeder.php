<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CapaianProgramSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('capaian_program')->truncate();

        $base = [
            ['program'=>'Program Pengembangan Destinasi Pariwisata','sasaran'=>'Meningkatnya Rasio PDRB Penyediaan Akomodasi Makan Minum','indikator'=>'Rata-Rata Lama Kunjungan Wisatawan Mancanegara (Hari)','target'=>3,'realisasi'=>0,'capaian'=>0,'frekwensi'=>'Tahunan','sumber_data'=>'BPS','penanggung_jawab'=>'BIDANG Pengembangan Destinasi Pariwisata'],
            ['program'=>'Program Pengembangan Destinasi Pariwisata','sasaran'=>'Meningkatnya Rasio PDRB Penyediaan Akomodasi Makan Minum','indikator'=>'Rata-rata pengeluaran wisatawan mancanegara ($)','target'=>600,'realisasi'=>0,'capaian'=>0,'frekwensi'=>'Tahunan','sumber_data'=>'BPS','penanggung_jawab'=>'BIDANG Pengembangan Destinasi Pariwisata'],
            ['program'=>'Program Pemasaran Pariwisata','sasaran'=>'Meningkatnya Jumlah Kunjungan Wisatawan Mancanegara','indikator'=>'Jumlah pergerakan wisatawan mancanegara (ribu perhari)','target'=>28750,'realisasi'=>3847,'capaian'=>13.38,'frekwensi'=>'Bulanan / Tahunan','sumber_data'=>'BPS, Dinas Pariwisata Kab./Kota','penanggung_jawab'=>'BIDANG Pemasaran Pariwisata'],
            ['program'=>'Program Pemasaran Pariwisata','sasaran'=>'Meningkatnya Jumlah Kunjungan Wisatawan Mancanegara','indikator'=>'Jumlah pergerakan wisatawan mancanegara (juta orang)','target'=>9925000,'realisasi'=>4988167,'capaian'=>50.28,'frekwensi'=>'Bulanan / Tahunan','sumber_data'=>'BPS, Dinas Pariwisata Kab./Kota','penanggung_jawab'=>'BIDANG Pemasaran Pariwisata'],
            ['program'=>'Program Ekonomi Kreatif Melalui Pemanfaatan Dan Perlindungan Hak Kekayaan Intelektual','sasaran'=>'Meningkatnya Proporsi PDRB Ekonomi Kreatif Terhadap ADHB','indikator'=>'Nilai Tambah Ekonomi Kreatif (Rp)','target'=>143750,'realisasi'=>0,'capaian'=>0,'frekwensi'=>'Tahunan','sumber_data'=>'BPS','penanggung_jawab'=>'BIDANG Pengembangan Ekonomi Kreatif'],
            ['program'=>'Program Pengembangan Sumber Daya Pariwisata dan Ekraf','sasaran'=>'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi','indikator'=>'Jumlah tenaga Kerja Pariwisata (orang)','target'=>9259,'realisasi'=>0,'capaian'=>0,'frekwensi'=>'Tahunan','sumber_data'=>'BPS','penanggung_jawab'=>'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'],
            ['program'=>'Program Pengembangan Sumber Daya Pariwisata dan Ekraf','sasaran'=>'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi','indikator'=>'Jumlah Tenaga Kerja Ekonomi Kreatif (orang)','target'=>2571,'realisasi'=>0,'capaian'=>0,'frekwensi'=>'Tahunan','sumber_data'=>'BPS','penanggung_jawab'=>'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'],
            ['program'=>'Program Pengembangan Sumber Daya Pariwisata dan Ekraf','sasaran'=>'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi','indikator'=>'Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata tersertifikasi (orang)','target'=>200,'realisasi'=>0,'capaian'=>0,'frekwensi'=>'Tahunan','sumber_data'=>'BPS','penanggung_jawab'=>'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'],
            ['program'=>'Program Pengembangan Sumber Daya Pariwisata dan Ekraf','sasaran'=>'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi','indikator'=>'Jumlah Tenaga Kerja/Pelaku Usaha Ekonomi Kreatif tersertifikasi (orang)','target'=>200,'realisasi'=>0,'capaian'=>0,'frekwensi'=>'Tahunan','sumber_data'=>'BPS','penanggung_jawab'=>'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'],
        ];

        $tahun_list = ['2025','2026','2027','2028','2029','2030'];
        $now = now()->toDateTimeString();

        foreach ($tahun_list as $tahun) {
            foreach ($base as $row) {
                DB::table('capaian_program')->insert(array_merge($row, [
                    'file_sumber' => null,
                    'tahun' => $tahun,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        // Update data 2026 yang sudah ada realisasinya
        DB::table('capaian_program')
            ->where('tahun', '2026')
            ->where('indikator', 'Rata-Rata Lama Kunjungan Wisatawan Mancanegara (Hari)')
            ->update(['target' => 1.5, 'realisasi' => 1.57, 'capaian' => 104.6667]);
    }
}
