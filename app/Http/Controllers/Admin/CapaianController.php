<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CapaianProgram;
use Illuminate\Support\Facades\Validator;

class CapaianController extends Controller
{
    public static function getPredikatStatic($capaian)
    {
        $capaian = (float) $capaian;
        if ($capaian > 100) return ['label' => 'ISTIMEWA', 'class' => 'istimewa', 'icon' => 'fa-star'];
        if ($capaian >= 80) return ['label' => 'BAIK', 'class' => 'baik', 'icon' => 'fa-check-circle'];
        if ($capaian >= 60) return ['label' => 'BUTUH PERBAIKAN', 'class' => 'butuh-perbaikan', 'icon' => 'fa-exclamation-triangle'];
        if ($capaian >= 20) return ['label' => 'KURANG', 'class' => 'kurang', 'icon' => 'fa-times-circle'];
        if ($capaian > 0)  return ['label' => 'SANGAT KURANG', 'class' => 'sangat-kurang', 'icon' => 'fa-exclamation-circle'];
        return ['label' => 'BELUM ADA', 'class' => 'belum-ada', 'icon' => 'fa-minus-circle'];
    }

    public function index()
    {
        $tahun_list = ['2025', '2026', '2027', '2028', '2029', '2030'];
        $tahun_aktif = request('tahun', '2025');
        
        if (!in_array($tahun_aktif, $tahun_list)) {
            $tahun_aktif = $tahun_list[0];
        }
        
        $total_baru = \App\Models\SuratMasuk::where('status', 'baru')->count();
        
        // Pastikan tabel dan kolom ada
        try {
            \Illuminate\Support\Facades\Schema::table('capaian_program', function ($table) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('capaian_program', 'file_sumber')) {
                    $table->string('file_sumber')->nullable()->after('sumber_data');
                }
            });
        } catch (\Exception $e) {
            // Column sudah ada atau error
        }
        
        // Ambil data
        $capaian_data = CapaianProgram::where('tahun', $tahun_aktif)->orderBy('id')->get();
        
        // Jika tidak ada data, buat default
        if ($capaian_data->isEmpty()) {
            $default_data = [
                [
                    'program' => 'Program Pengembangan Destinasi Pariwisata',
                    'sasaran' => 'Meningkatnya Rasio PDRB Penyediaan Akomodasi Makan Minum',
                    'indikator' => 'Rata-Rata Lama Kunjungan Wisatawan Mancanegara (Hari)',
                    'target' => 3,
                    'realisasi' => 0,
                    'capaian' => 0,
                    'frekwensi' => 'Tahunan',
                    'sumber_data' => 'BPS',
                    'penanggung_jawab' => 'BIDANG Pengembangan Destinasi Pariwisata'
                ],
                [
                    'program' => 'Program Pengembangan Destinasi Pariwisata',
                    'sasaran' => 'Meningkatnya Rasio PDRB Penyediaan Akomodasi Makan Minum',
                    'indikator' => 'Rata-rata pengeluaran wisatawan mancanegara ($)',
                    'target' => 600,
                    'realisasi' => 0,
                    'capaian' => 0,
                    'frekwensi' => 'Tahunan',
                    'sumber_data' => 'BPS',
                    'penanggung_jawab' => 'BIDANG Pengembangan Destinasi Pariwisata'
                ],
                [
                    'program' => 'Program Pemasaran Pariwisata',
                    'sasaran' => 'Meningkatnya Jumlah Kunjungan Wisatawan Mancanegara',
                    'indikator' => 'Jumlah pergerakan wisatawan mancanegara (ribu perhari)',
                    'target' => 28750,
                    'realisasi' => 3847,
                    'capaian' => 13.38,
                    'frekwensi' => 'Bulanan / Tahunan',
                    'sumber_data' => 'BPS, Dinas Pariwisata Kab./Kota',
                    'penanggung_jawab' => 'BIDANG Pemasaran Pariwisata'
                ],
                [
                    'program' => 'Program Pemasaran Pariwisata',
                    'sasaran' => 'Meningkatnya Jumlah Kunjungan Wisatawan Mancanegara',
                    'indikator' => 'Jumlah pergerakan wisatawan mancanegara (juta orang)',
                    'target' => 9925000,
                    'realisasi' => 4988167,
                    'capaian' => 50.28,
                    'frekwensi' => 'Bulanan / Tahunan',
                    'sumber_data' => 'BPS, Dinas Pariwisata Kab./Kota',
                    'penanggung_jawab' => 'BIDANG Pemasaran Pariwisata'
                ],
                [
                    'program' => 'Program Ekonomi Kreatif Melalui Pemanfaatan Dan Perlindungan Hak Kekayaan Intelektual',
                    'sasaran' => 'Meningkatnya Proporsi PDRB Ekonomi Kreatif Terhadap ADHB',
                    'indikator' => 'Nilai Tambah Ekonomi Kreatif (Rp)',
                    'target' => 143750000000,
                    'realisasi' => 0,
                    'capaian' => 0,
                    'frekwensi' => 'Tahunan',
                    'sumber_data' => 'BPS',
                    'penanggung_jawab' => 'BIDANG Pengembangan Ekonomi Kreatif'
                ],
                [
                    'program' => 'Program Pengembangan Sumber Daya Pariwisata dan Ekraf',
                    'sasaran' => 'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi',
                    'indikator' => 'Jumlah tenaga Kerja Pariwisata (orang)',
                    'target' => 9259,
                    'realisasi' => 0,
                    'capaian' => 0,
                    'frekwensi' => 'Tahunan',
                    'sumber_data' => 'BPS',
                    'penanggung_jawab' => 'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'
                ],
                [
                    'program' => 'Program Pengembangan Sumber Daya Pariwisata dan Ekraf',
                    'sasaran' => 'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi',
                    'indikator' => 'Jumlah Tenaga Kerja Ekonomi Kreatif (orang)',
                    'target' => 2571,
                    'realisasi' => 0,
                    'capaian' => 0,
                    'frekwensi' => 'Tahunan',
                    'sumber_data' => 'BPS',
                    'penanggung_jawab' => 'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'
                ],
                [
                    'program' => 'Program Pengembangan Sumber Daya Pariwisata dan Ekraf',
                    'sasaran' => 'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi',
                    'indikator' => 'Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata tersertifikasi (orang)',
                    'target' => 200,
                    'realisasi' => 0,
                    'capaian' => 0,
                    'frekwensi' => 'Tahunan',
                    'sumber_data' => 'BPS',
                    'penanggung_jawab' => 'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'
                ],
                [
                    'program' => 'Program Pengembangan Sumber Daya Pariwisata dan Ekraf',
                    'sasaran' => 'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi',
                    'indikator' => 'Jumlah Tenaga Kerja/Pelaku Usaha Ekonomi Kreatif tersertifikasi (orang)',
                    'target' => 200,
                    'realisasi' => 0,
                    'capaian' => 0,
                    'frekwensi' => 'Tahunan',
                    'sumber_data' => 'BPS',
                    'penanggung_jawab' => 'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'
                ]
            ];
            
            foreach ($default_data as $data) {
                CapaianProgram::create([
                    'program' => $data['program'],
                    'sasaran' => $data['sasaran'],
                    'indikator' => $data['indikator'],
                    'target' => $data['target'],
                    'realisasi' => $data['realisasi'],
                    'capaian' => $data['capaian'],
                    'frekwensi' => $data['frekwensi'],
                    'sumber_data' => $data['sumber_data'],
                    'penanggung_jawab' => $data['penanggung_jawab'],
                    'tahun' => $tahun_aktif,
                ]);
            }
            
            $capaian_data = CapaianProgram::where('tahun', $tahun_aktif)->orderBy('id')->get();
        }
        
        // Hitung stats
        $total_data = $capaian_data->count();
        $rata_capaian = 0;
        $total_capaian = 0;
        foreach ($capaian_data as $d) {
            $total_capaian += (float) $d->capaian;
        }
        if ($total_data > 0) {
            $rata_capaian = $total_capaian / $total_data;
        }
        
        return view('admin.capaian', compact(
            'capaian_data',
            'tahun_aktif',
            'tahun_list',
            'total_data',
            'rata_capaian',
            'total_baru'
        ));
    }

    public function update(Request $request)
    {
        $tahun_aktif = $request->tahun ?? '2025';
        
        if (isset($request->data)) {
            foreach ($request->data as $id => $row) {
                $target = str_replace('.', '', $row['target'] ?? '0');
                $target = str_replace(',', '.', $target);
                $target = (float) $target;
                
                $realisasi = str_replace('.', '', $row['realisasi'] ?? '0');
                $realisasi = str_replace(',', '.', $realisasi);
                $realisasi = (float) $realisasi;
                
                $capaian = 0;
                if ($target > 0) {
                    $capaian = ($realisasi / $target) * 100;
                }
                
                $data = [
                    'program' => $row['program'] ?? '',
                    'sasaran' => $row['sasaran'] ?? '',
                    'indikator' => $row['indikator'] ?? '',
                    'target' => $target,
                    'realisasi' => $realisasi,
                    'capaian' => $capaian,
                    'sumber_data' => $row['sumber_data'] ?? '',
                    'frekwensi' => $row['frekwensi'] ?? 'Tahunan',
                    'penanggung_jawab' => $row['penanggung_jawab'] ?? '',
                ];
                
                CapaianProgram::where('id', $id)->update($data);
            }
        }
        
        // Upload file
        if ($request->hasFile('file_sumber')) {
            $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
            $max_size = 10 * 1024 * 1024;
            
            foreach ($request->file('file_sumber') as $id => $file) {
                if ($file->isValid()) {
                    $ext = $file->getClientOriginalExtension();
                    if (in_array($ext, $allowed) && $file->getSize() <= $max_size) {
                        $dir = public_path('uploads/capaian');
                        if (!is_dir($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        
                        $existing = CapaianProgram::where('id', $id)->first();
                        if ($existing && $existing->file_sumber) {
                            $old_path = public_path('uploads/capaian/' . $existing->file_sumber);
                            if (file_exists($old_path)) {
                                unlink($old_path);
                            }
                        }
                        
                        $file_name = 'sumber_' . $id . '_' . time() . '.' . $ext;
                        $file->move($dir, $file_name);
                        CapaianProgram::where('id', $id)->update(['file_sumber' => $file_name]);
                    }
                }
            }
        }
        
        return redirect()->route('admin.capaian.index', ['tahun' => $tahun_aktif])
            ->with('success', 'Data capaian program berhasil diperbarui!');
    }

    public function reset(Request $request)
    {
        $tahun_aktif = $request->tahun ?? '2025';
        
        $data = CapaianProgram::where('tahun', $tahun_aktif)->get();
        foreach ($data as $item) {
            if ($item->file_sumber) {
                $file_path = public_path('uploads/capaian/' . $item->file_sumber);
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            $item->update([
                'target' => 0,
                'realisasi' => 0,
                'capaian' => 0,
                'sumber_data' => null,
                'file_sumber' => null,
            ]);
        }
        
        return redirect()->route('admin.capaian.index', ['tahun' => $tahun_aktif])
            ->with('success', 'Data berhasil direset!');
    }

    public function deleteFile(Request $request)
    {
        $id = $request->id;
        $tahun_aktif = $request->tahun ?? '2025';
        
        $item = CapaianProgram::where('id', $id)->where('tahun', $tahun_aktif)->first();
        if ($item && $item->file_sumber) {
            $file_path = public_path('uploads/capaian/' . $item->file_sumber);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            $item->update(['file_sumber' => null]);
        }
        
        return redirect()->route('admin.capaian.index', ['tahun' => $tahun_aktif])
            ->with('success', 'File sumber berhasil dihapus!');
    }
}