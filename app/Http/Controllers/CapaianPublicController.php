<?php

namespace App\Http\Controllers;

use App\Models\CapaianProgram;
use Illuminate\Support\Facades\DB;

class CapaianPublicController extends Controller
{
    protected function getPredikat($capaian)
    {
        if ($capaian > 100) {
            return ['label' => 'ISTIMEWA', 'class' => 'predikat-istimewa'];
        } elseif ($capaian > 80) {
            return ['label' => 'BAIK', 'class' => 'predikat-baik'];
        } elseif ($capaian > 60) {
            return ['label' => 'BUTUH PERBAIKAN', 'class' => 'predikat-butuh'];
        } elseif ($capaian > 20) {
            return ['label' => 'KURANG', 'class' => 'predikat-kurang'];
        } elseif ($capaian > 0) {
            return ['label' => 'SANGAT KURANG', 'class' => 'predikat-sangat'];
        } else {
            return ['label' => 'BELUM ADA', 'class' => 'predikat-belum'];
        }
    }

    public function index()
    {
        $tahun_list = range(2025, 2030);
        $tahun_aktif = request('tahun', date('Y'));
        
        if (!in_array((int)$tahun_aktif, $tahun_list)) {
            $tahun_aktif = $tahun_list[0];
        }
        
        $capaian_data = CapaianProgram::where('tahun', $tahun_aktif)
            ->orderBy('id')
            ->get();
            
        $total_data = $capaian_data->count();
        $rata_capaian = 0;
        if ($total_data > 0) {
            $rata_capaian = $capaian_data->avg('capaian') ?? 0;
        }
        
        return view('public.capaian', compact('capaian_data', 'tahun_aktif', 'tahun_list', 'total_data', 'rata_capaian'));
    }
}