<?php

namespace App\Http\Controllers;

use App\Models\DokumenAkip;
use App\Models\DokumenIki;
use App\Models\IkuPdrb;
use App\Models\MonevBulanan;
use App\Models\Slider;
use App\Models\SuratMasuk;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slider::where('status', 'aktif')
            ->orderBy('urutan')
            ->limit(6)
            ->get();

        $total_surat = SuratMasuk::count();
        $total_akip = DokumenAkip::count();
        $total_iki = DokumenIki::count();
        $total_monev = MonevBulanan::count();

        $pdrb_terbaru = IkuPdrb::orderBy('id', 'desc')->first();
        $capaian_pdrb = $pdrb_terbaru ? (float) $pdrb_terbaru->capaian : 0;

        $surat_terbaru = SuratMasuk::orderBy('id', 'desc')->limit(5)->get();

        return view('public.home', compact(
            'slides',
            'total_surat',
            'total_akip',
            'total_iki',
            'total_monev',
            'capaian_pdrb',
            'surat_terbaru'
        ));
    }
}
