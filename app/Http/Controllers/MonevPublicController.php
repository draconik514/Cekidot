<?php

namespace App\Http\Controllers;

use App\Models\MonevBulanan;
use App\Models\MonevAkumulasi;
use Illuminate\Support\Facades\DB;

class MonevPublicController extends Controller
{
    protected function getPredikat($capaian)
    {
        if ($capaian === null || $capaian === '') {
            return ['label' => 'BELUM ADA', 'class' => 'predikat-belum'];
        }
        $capaian = (float) str_replace(',', '.', str_replace('.', '', $capaian));
        if ($capaian > 100) {
            return ['label' => 'ISTIMEWA', 'class' => 'predikat-istimewa'];
        } elseif ($capaian >= 80) {
            return ['label' => 'BAIK', 'class' => 'predikat-baik'];
        } elseif ($capaian >= 60) {
            return ['label' => 'BUTUH PERBAIKAN', 'class' => 'predikat-butuh'];
        } elseif ($capaian >= 20) {
            return ['label' => 'KURANG', 'class' => 'predikat-kurang'];
        } elseif ($capaian > 0) {
            return ['label' => 'SANGAT KURANG', 'class' => 'predikat-sangat'];
        } else {
            return ['label' => 'BELUM ADA', 'class' => 'predikat-belum'];
        }
    }

    public function index()
    {
        $tahun_list = ['2025', '2026', '2027', '2028', '2029', '2030'];
        $tahun_aktif = request('tahun', '2025');
        
        if (!in_array($tahun_aktif, $tahun_list)) {
            $tahun_aktif = $tahun_list[0];
        }
        
        $bulan_list = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan_singkat = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $bulan_aktif = request('bulan', 'Januari');
        
        if (!in_array($bulan_aktif, $bulan_list)) {
            $bulan_aktif = $bulan_list[0];
        }
        
        $tab_aktif = request('tab', 'bulanan');
        
        $data_bulanan = MonevBulanan::where('tahun', $tahun_aktif)
            ->where('bulan', $bulan_aktif)
            ->orderBy('id')
            ->get();
            
        $data_akumulasi = MonevAkumulasi::where('tahun', $tahun_aktif)
            ->orderBy('id')
            ->get();
            
        $total_bulanan = $data_bulanan->count();
        $total_akumulasi = $data_akumulasi->count();
        
        return view('public.monev', compact(
            'tahun_list', 'tahun_aktif',
            'bulan_list', 'bulan_singkat', 'bulan_aktif',
            'tab_aktif',
            'data_bulanan', 'data_akumulasi',
            'total_bulanan', 'total_akumulasi'
        ));
    }
}