<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonevBulanan;
use App\Models\MonevAkumulasi;
use Illuminate\Support\Facades\DB;

class MonevController extends Controller
{
    public static function getPredikatStatic($capaian)
    {
        return self::getPredikatLogic($capaian);
    }

    protected function getPredikat($capaian)
    {
        return self::getPredikatLogic($capaian);
    }

    private static function getPredikatLogic($capaian)
    {
        if ($capaian === null || $capaian === '') {
            return ['label' => 'BELUM ADA', 'class' => 'belum-ada'];
        }
        $capaian = (float) str_replace(',', '.', str_replace('.', '', $capaian));
        if ($capaian > 100) {
            return ['label' => 'ISTIMEWA', 'class' => 'istimewa'];
        } elseif ($capaian >= 80) {
            return ['label' => 'BAIK', 'class' => 'baik'];
        } elseif ($capaian >= 60) {
            return ['label' => 'BUTUH PERBAIKAN', 'class' => 'butuh-perbaikan'];
        } elseif ($capaian >= 20) {
            return ['label' => 'KURANG', 'class' => 'kurang'];
        } elseif ($capaian > 0) {
            return ['label' => 'SANGAT KURANG', 'class' => 'sangat-kurang'];
        } else {
            return ['label' => 'BELUM ADA', 'class' => 'belum-ada'];
        }
    }

    protected function updateAkumulasiOtomatis($tahun)
    {
        // Hapus data akumulasi lama
        MonevAkumulasi::where('tahun', $tahun)->delete();
        
        $data_bulanan = MonevBulanan::where('tahun', $tahun)->get();
        
        foreach ($data_bulanan as $row) {
            $capaian_ik = 0;
            if ($row->target_ik > 0) {
                $capaian_ik = ($row->realisasi_ik / $row->target_ik) * 100;
            }
            
            $capaian_keu = 0;
            if ($row->target_keu > 0) {
                $capaian_keu = ($row->realisasi_keu / $row->target_keu) * 100;
            }
            
            $predikat_ik = $this->getPredikat($capaian_ik)['label'];
            $predikat_keu = $this->getPredikat($capaian_keu)['label'];
            
            $status = 'Tidak Efisien';
            if ($capaian_ik >= $capaian_keu) {
                $status = 'Efisien';
            }
            
            MonevAkumulasi::create([
                'tahun' => $tahun,
                'sub_kegiatan' => $row->sub_kegiatan,
                'indikator' => $row->indikator,
                'target_ik' => $row->target_ik,
                'target_keu' => $row->target_keu,
                'realisasi_ik' => $row->realisasi_ik,
                'realisasi_keu' => $row->realisasi_keu,
                'capaian_ik' => $capaian_ik,
                'capaian_keu' => $capaian_keu,
                'predikat_ik' => $predikat_ik,
                'predikat_keu' => $predikat_keu,
                'status' => $status,
            ]);
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
        $total_baru = \App\Models\SuratMasuk::where('status', 'baru')->count();
        
        $data_bulanan = MonevBulanan::where('tahun', $tahun_aktif)
            ->where('bulan', $bulan_aktif)
            ->orderBy('id')
            ->get();
            
        $data_akumulasi = MonevAkumulasi::where('tahun', $tahun_aktif)
            ->orderBy('id')
            ->get();
            
        return view('admin.monev', compact(
            'tahun_list', 'tahun_aktif',
            'bulan_list', 'bulan_singkat', 'bulan_aktif',
            'tab_aktif',
            'data_bulanan', 'data_akumulasi',
            'total_baru'
        ));
    }

    public function update(Request $request)
    {
        $tahun_aktif = $request->tahun ?? '2025';
        $bulan_aktif = $request->bulan ?? 'Januari';
        $tab_aktif = $request->tab ?? 'bulanan';
        
        if (isset($request->data)) {
            // Hapus data bulanan lama
            MonevBulanan::where('tahun', $tahun_aktif)
                ->where('bulan', $bulan_aktif)
                ->delete();
            
            foreach ($request->data as $row) {
                $target_ik = str_replace('.', '', $row['target_ik'] ?? '0');
                $target_ik = str_replace(',', '.', $target_ik);
                $target_ik = (float) $target_ik;
                
                $target_keu = str_replace('.', '', $row['target_keu'] ?? '0');
                $target_keu = str_replace(',', '.', $target_keu);
                $target_keu = (float) $target_keu;
                
                $realisasi_ik = str_replace('.', '', $row['realisasi_ik'] ?? '0');
                $realisasi_ik = str_replace(',', '.', $realisasi_ik);
                $realisasi_ik = (float) $realisasi_ik;
                
                $realisasi_keu = str_replace('.', '', $row['realisasi_keu'] ?? '0');
                $realisasi_keu = str_replace(',', '.', $realisasi_keu);
                $realisasi_keu = (float) $realisasi_keu;
                
                $sub_kegiatan = $row['sub_kegiatan'] ?? '';
                $indikator = $row['indikator'] ?? '';
                
                if ($target_ik == 0 && $target_keu == 0 && $realisasi_ik == 0 && $realisasi_keu == 0 && empty($sub_kegiatan) && empty($indikator)) {
                    continue;
                }
                
                if (empty($sub_kegiatan)) {
                    $sub_kegiatan = '-';
                }
                if (empty($indikator)) {
                    $indikator = '-';
                }
                
                $capaian_ik = 0;
                if ($target_ik > 0) {
                    $capaian_ik = ($realisasi_ik / $target_ik) * 100;
                }
                
                $capaian_keu = 0;
                if ($target_keu > 0) {
                    $capaian_keu = ($realisasi_keu / $target_keu) * 100;
                }
                
                MonevBulanan::create([
                    'tahun' => $tahun_aktif,
                    'bulan' => $bulan_aktif,
                    'sub_kegiatan' => $sub_kegiatan,
                    'indikator' => $indikator,
                    'target_ik' => $target_ik,
                    'target_keu' => $target_keu,
                    'realisasi_ik' => $realisasi_ik,
                    'realisasi_keu' => $realisasi_keu,
                    'capaian_ik' => $capaian_ik,
                    'capaian_keu' => $capaian_keu,
                    'sumber_data' => $row['sumber_data'] ?? '',
                    'faktor_penghambat' => $row['faktor_penghambat'] ?? '',
                    'faktor_pendukung' => $row['faktor_pendukung'] ?? '',
                ]);
            }
            
            // Update akumulasi otomatis
            $this->updateAkumulasiOtomatis($tahun_aktif);
            
            return redirect()->route('admin.monev.index', [
                'tahun' => $tahun_aktif,
                'bulan' => $bulan_aktif,
                'tab' => $tab_aktif,
            ])->with('success', 'Data berhasil disimpan! - Akumulasi otomatis diperbarui');
        }
        
        return redirect()->route('admin.monev.index', [
            'tahun' => $tahun_aktif,
            'bulan' => $bulan_aktif,
            'tab' => $tab_aktif,
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->delete_id;
        $tahun_aktif = $request->tahun ?? '2025';
        $bulan_aktif = $request->bulan ?? 'Januari';
        $tab_aktif = $request->tab ?? 'bulanan';
        
        MonevBulanan::where('id', $id)->delete();
        $this->updateAkumulasiOtomatis($tahun_aktif);
        
        return redirect()->route('admin.monev.index', [
            'tahun' => $tahun_aktif,
            'bulan' => $bulan_aktif,
            'tab' => $tab_aktif,
        ])->with('success', 'Data berhasil dihapus! - Akumulasi otomatis diperbarui');
    }
}