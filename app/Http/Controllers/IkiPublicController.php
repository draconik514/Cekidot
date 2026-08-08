<?php

namespace App\Http\Controllers;

use App\Models\DokumenIki;

class IkiPublicController extends Controller
{
    public function index()
    {
        $tahun_list = range(2025, 2030);
        $tahun_aktif = request('tahun', date('Y'));
        
        if (!in_array((int)$tahun_aktif, $tahun_list)) {
            $tahun_aktif = $tahun_list[0];
        }
        
        $dokumen = DokumenIki::where('tahun', $tahun_aktif)
            ->where('status', 'aktif')
            ->orderBy('urutan')
            ->get();
            
        $total_dokumen = DokumenIki::where('status', 'aktif')->count();
        
        return view('public.iki', compact('dokumen', 'tahun_aktif', 'tahun_list', 'total_dokumen'));
    }
}