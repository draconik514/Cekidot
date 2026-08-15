<?php

namespace Database\Seeders;

use App\Models\FolderDokumen;
use App\Models\User;
use Illuminate\Database\Seeder;

class FolderDokumenSeeder extends Seeder
{
    private const KATEGORI = ['Surat Masuk', 'Surat Keluar', 'SK (Surat Keputusan)', 'Rencana Hasil Kerja (RHK)'];

    public function run(): void
    {
        $creator = User::where('role', 'super_admin')->first() ?? User::first();
        if (! $creator) {
            return;
        }

        $divisi_list = ['Kepegawaian', 'Program', 'Keuangan', 'Ekraf', 'Destinasi', 'Pemasaran', 'Sdm'];

        foreach ($divisi_list as $divisi) {
            $parent = FolderDokumen::where('nama', $divisi)->whereNull('parent_id')->first();
            if (! $parent) {
                $parent = FolderDokumen::create([
                    'nama' => $divisi,
                    'deskripsi' => "Arsip Bidang $divisi",
                    'divisi' => $divisi,
                    'status' => 'aktif',
                    'created_by' => $creator->id,
                ]);
            }

            foreach (self::KATEGORI as $kategori) {
                FolderDokumen::firstOrCreate([
                    'nama' => $kategori,
                    'parent_id' => $parent->id,
                ], [
                    'deskripsi' => $kategori,
                    'divisi' => $divisi,
                    'status' => 'aktif',
                    'created_by' => $creator->id,
                ]);
            }
        }
    }
}
