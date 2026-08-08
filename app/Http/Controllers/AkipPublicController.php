<?php

namespace App\Http\Controllers;

use App\Models\DokumenAkip;

class AkipPublicController extends Controller
{
    public function index()
    {
        $tahun_list = range(2025, 2030);
        $tahun_aktif = request('tahun', date('Y'));
        
        if (!in_array((int)$tahun_aktif, $tahun_list)) {
            $tahun_aktif = $tahun_list[0];
        }
        
        $dokumen = DokumenAkip::where('tahun', $tahun_aktif)
            ->where('status', 'aktif')
            ->orderBy('urutan')
            ->get();
            
        $total_dokumen = DokumenAkip::where('status', 'aktif')->count();
        
        return view('public.akip', compact('dokumen', 'tahun_aktif', 'tahun_list', 'total_dokumen'));
    }
}