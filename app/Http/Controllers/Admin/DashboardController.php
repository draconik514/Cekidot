<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Models\Slider;
use App\Models\DokumenAkip;
use App\Models\DokumenIki;
use App\Models\MonevBulanan;
use App\Models\MonevAkumulasi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total_surat = SuratMasuk::count();
        $total_surat_baru = SuratMasuk::where('status', 'baru')->count();
        $total_slider = Slider::where('status', 'aktif')->count();
        $total_akip = DokumenAkip::count();
        $total_iki = DokumenIki::count();
        
        $total_monev_bulanan = MonevBulanan::count();
        $total_monev_akumulasi = MonevAkumulasi::count();
        
        $surat_terbaru = SuratMasuk::orderBy('id', 'desc')->limit(5)->get();
        
        $aktivitas = [];
        
        $surat_aktivitas = SuratMasuk::select(
            DB::raw("'surat' as type"),
            'id',
            DB::raw("CONCAT('Surat baru dari ', asal_instansi) as deskripsi"),
            'tanggal_masuk as waktu'
        )->orderBy('id', 'desc')->limit(3)->get();
        $aktivitas = array_merge($aktivitas, $surat_aktivitas->toArray());
        
        $akip_aktivitas = DokumenAkip::select(
            DB::raw("'akip' as type"),
            'id',
            DB::raw("CONCAT('Dokumen AKIP: ', judul) as deskripsi"),
            'created_at as waktu'
        )->orderBy('id', 'desc')->limit(2)->get();
        $aktivitas = array_merge($aktivitas, $akip_aktivitas->toArray());
        
        $iki_aktivitas = DokumenIki::select(
            DB::raw("'iki' as type"),
            'id',
            DB::raw("CONCAT('Dokumen IKI: ', judul) as deskripsi"),
            'created_at as waktu'
        )->orderBy('id', 'desc')->limit(2)->get();
        $aktivitas = array_merge($aktivitas, $iki_aktivitas->toArray());
        
        $slider_aktivitas = Slider::select(
            DB::raw("'slider' as type"),
            'id',
            DB::raw("CONCAT('Slide: ', judul) as deskripsi"),
            'created_at as waktu'
        )->orderBy('id', 'desc')->limit(2)->get();
        $aktivitas = array_merge($aktivitas, $slider_aktivitas->toArray());
        
        usort($aktivitas, function($a, $b) {
            return strtotime($b['waktu']) - strtotime($a['waktu']);
        });
        $aktivitas = array_slice($aktivitas, 0, 10);
        
        return view('admin.dashboard', compact(
            'total_surat', 'total_surat_baru', 'total_slider',
            'total_akip', 'total_iki',
            'total_monev_bulanan', 'total_monev_akumulasi',
            'surat_terbaru', 'aktivitas'
        ));
    }
}