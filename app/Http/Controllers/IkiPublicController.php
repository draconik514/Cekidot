<?php

namespace App\Http\Controllers;

use App\Models\DokumenIki;
use App\Models\UploadAnggota;
use App\Models\FolderDokumen;

class IkiPublicController extends Controller
{
    public function index()
    {
        $tahun_list = range(2025, 2030);
        $tahun_aktif = (int) request('tahun', date('Y'));
        if (!in_array($tahun_aktif, $tahun_list)) $tahun_aktif = 2025;

        $search = request('search');
        $bulan  = request('bulan');
        $tgl    = request('tanggal');

        $dokumen = DokumenIki::where('tahun', $tahun_aktif)->where('status', 'aktif')->orderBy('urutan')->get();
        $total_dokumen = DokumenIki::where('status', 'aktif')->count();

        // Upload anggota — folder yang relevan dengan IKI
        $query = UploadAnggota::with(['user', 'folder'])
            ->where('status', 'aktif')
            ->where('tahun', $tahun_aktif)
            ->whereHas('folder', fn($q) => $q->where('nama', 'like', '%IKI%')->orWhere('nama', 'like', '%Kinerja Individu%'));

        if ($search) $query->where(fn($q) => $q->where('judul', 'like', "%$search%")->orWhereHas('user', fn($q2) => $q2->where('nama_admin', 'like', "%$search%")));
        if ($bulan)  $query->where('bulan', $bulan);
        if ($tgl)    $query->whereDate('tanggal_upload', $tgl);

        $uploads_anggota = $query->orderByDesc('tanggal_upload')->get();
        $folders_iki = FolderDokumen::where(fn($q) => $q->where('nama', 'like', '%IKI%')->orWhere('nama', 'like', '%Kinerja Individu%'))->get();

        return view('public.iki', compact('dokumen', 'tahun_aktif', 'tahun_list', 'total_dokumen', 'uploads_anggota', 'search', 'bulan', 'tgl', 'folders_iki'));
    }
}