<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IkuEkraf;
use App\Models\IkuInfografis;
use App\Models\IkuPdrb;
use App\Models\IkuPenilaian;
use App\Models\IkuWisatawan;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IkuController extends Controller
{
    protected function forbidAdminBidangEdit(): void
    {
        if (Auth::user()->isAdminBidang()) {
            abort(403, 'IKU bersifat view-only untuk Admin Bidang.');
        }
    }

    protected function getPredikat($capaian)
    {
        if ($capaian === null || $capaian === '') {
            return ['label' => 'BELUM ADA', 'class' => 'belum-ada', 'icon' => 'fa-minus-circle'];
        }
        $capaian = (float) str_replace(',', '.', str_replace('.', '', $capaian));
        if ($capaian > 100) {
            return ['label' => 'ISTIMEWA', 'class' => 'istimewa', 'icon' => 'fa-star'];
        } elseif ($capaian >= 80) {
            return ['label' => 'BAIK', 'class' => 'baik', 'icon' => 'fa-check-circle'];
        } elseif ($capaian >= 60) {
            return ['label' => 'BUTUH PERBAIKAN', 'class' => 'butuh-perbaikan', 'icon' => 'fa-exclamation-triangle'];
        } elseif ($capaian >= 20) {
            return ['label' => 'KURANG', 'class' => 'kurang', 'icon' => 'fa-times-circle'];
        } elseif ($capaian > 0) {
            return ['label' => 'SANGAT KURANG', 'class' => 'sangat-kurang', 'icon' => 'fa-exclamation-circle'];
        } else {
            return ['label' => 'BELUM ADA', 'class' => 'belum-ada', 'icon' => 'fa-minus-circle'];
        }
    }

    public function index()
    {
        $kategori_list = ['Makan Minum', 'Wisatawan', 'Ekraf'];
        $kategori_aktif = request('kategori', 'Makan Minum');

        $tahun_list = ['2025', '2026', '2027', '2028', '2029', '2030'];
        $tahun_aktif = request('tahun', '2025');

        $subkategori_wisata = request('sub', 'Nusantara');
        $subkategori_list = ['Nusantara', 'Mancanegara', 'Akumulasi'];

        $user = Auth::user();
        $can_edit = ! $user->isAdminBidang();
        $total_baru = SuratMasuk::where('status', 'baru')->count();

        // Data
        $kriteria = [];
        $ekraf_data = [];
        $wisatawan_data = [];
        $wisatawan_kabkota = [
            'BANGGAI KEPULAUAN', 'BANGGAI', 'MOROWALI', 'POSO', 'DONGGALA',
            'TOLI-TOLI', 'BUOL', 'PARIGI MOUTONG', 'TOJO UNA-UNA', 'SIGI',
            'BANGGAI LAUT', 'MOROWALI UTARA', 'KOTA PALU',
        ];

        if ($kategori_aktif == 'Makan Minum') {
            $kriteria = IkuPenilaian::where('kategori', $kategori_aktif)
                ->where('tahun', $tahun_aktif)
                ->whereNotIn('nama_kriteria', ['Sumber Data', 'Infografis'])
                ->whereNotNull('nilai')
                ->orderBy('id')
                ->get()
                ->toArray();

            if (empty($kriteria)) {
                IkuPenilaian::where('kategori', $kategori_aktif)
                    ->where('tahun', $tahun_aktif)
                    ->whereNotIn('nama_kriteria', ['Sumber Data', 'Infografis'])
                    ->delete();

                $default_data = [
                    ['nama_kriteria' => 'Penyediaan Akomodasi dan Makan Minum', 'nilai' => 0],
                    ['nama_kriteria' => 'PDRB ADHB Sulawesi Tengah', 'nilai' => 0],
                ];
                foreach ($default_data as $d) {
                    IkuPenilaian::create([
                        'kategori' => $kategori_aktif,
                        'tahun' => $tahun_aktif,
                        'nama_kriteria' => $d['nama_kriteria'],
                        'nilai' => $d['nilai'],
                        'bobot' => 0,
                        'target' => 0,
                        'realisasi' => 0,
                    ]);
                }
                $kriteria = IkuPenilaian::where('kategori', $kategori_aktif)
                    ->where('tahun', $tahun_aktif)
                    ->whereNotIn('nama_kriteria', ['Sumber Data', 'Infografis'])
                    ->orderBy('id')
                    ->get()
                    ->toArray();
            }
        } elseif ($kategori_aktif == 'Ekraf') {
            $kriteria = IkuPenilaian::where('kategori', $kategori_aktif)
                ->where('tahun', $tahun_aktif)
                ->whereNotIn('nama_kriteria', ['Sumber Data', 'Infografis'])
                ->orderBy('id')
                ->get()
                ->toArray();

            if (empty($kriteria)) {
                $tahun_int = (int) $tahun_aktif;
                $nilai = 0;
                if ($tahun_int == 2025) {
                    $existing = IkuPenilaian::where('kategori', $kategori_aktif)
                        ->where('tahun', '2025')
                        ->where('nama_kriteria', 'PDRB ADHB Sulawesi Tengah')
                        ->first();
                    if ($existing) {
                        $nilai = (float) $existing->nilai;
                    }
                }
                $kriteria = [
                    [
                        'id' => 0,
                        'kategori' => $kategori_aktif,
                        'tahun' => $tahun_aktif,
                        'nama_kriteria' => 'PDRB ADHB Sulawesi Tengah',
                        'nilai' => $nilai,
                        'bobot' => 0,
                        'target' => 0,
                        'realisasi' => 0,
                    ],
                ];
            }
        }

        // Ekraf data
        if ($kategori_aktif == 'Ekraf') {
            $ekraf_data = IkuEkraf::where('kategori', $kategori_aktif)
                ->where('tahun', $tahun_aktif)
                ->orderBy('id')
                ->limit(10)
                ->get()
                ->toArray();

            if (empty($ekraf_data)) {
                $sektor_list = IkuEkraf::where('kategori', $kategori_aktif)
                    ->where('tahun', '2025')
                    ->orderBy('id')
                    ->limit(10)
                    ->pluck('sektor')
                    ->toArray();

                if (empty($sektor_list)) {
                    $sektor_list = [
                        'Industri Makanan dan Minuman (C.2)',
                        'Industri Tekstil dan Pakaian Jadi (C.4)',
                        'Industri Kulit, Barang dari Kulit, dan Alas Kaki (C.5)',
                        'Industri Kayu, Barang dari Kayu dan Gabus; dan Barang Anyaman dari Bambu, Rotan, dan Sejenisnya (C.6)',
                        'Industri Kertas dan Barang dari Kertas; Percetakan dan Reproduksi Media Rekaman (C.7)',
                        'Industri Furnitur (C.15)',
                        'Penyediaan Makan Minum (I.2)',
                        'Informasi dan Komunikasi (J)',
                        'Jasa Perusahaan (M,N)',
                        'Jasa Lainnya (R,S,T,U)',
                    ];
                }
                foreach ($sektor_list as $sektor) {
                    $ekraf_data[] = [
                        'id' => 0,
                        'kategori' => $kategori_aktif,
                        'tahun' => $tahun_aktif,
                        'sektor' => $sektor,
                        'koofisien' => 0,
                        'nilai_bps' => 0,
                        'jumlah_rp' => 0,
                        'hasil_penjumlahan' => 0,
                    ];
                }
            }
        }

        // Wisatawan data
        if ($kategori_aktif == 'Wisatawan') {
            $wisatawan_data = IkuWisatawan::where('kategori', 'Wisatawan')
                ->where('subkategori', $subkategori_wisata)
                ->where('tahun', $tahun_aktif)
                ->orderBy('id')
                ->get()
                ->toArray();

            if (empty($wisatawan_data)) {
                foreach ($wisatawan_kabkota as $kab) {
                    IkuWisatawan::create([
                        'kategori' => 'Wisatawan',
                        'subkategori' => $subkategori_wisata,
                        'tahun' => $tahun_aktif,
                        'kabkota' => $kab,
                    ]);
                }
                $wisatawan_data = IkuWisatawan::where('kategori', 'Wisatawan')
                    ->where('subkategori', $subkategori_wisata)
                    ->where('tahun', $tahun_aktif)
                    ->orderBy('id')
                    ->get()
                    ->toArray();
            }
        }

        // Infografis
        $infografis = IkuInfografis::where('kategori', $kategori_aktif)->first();
        $infografis_file = $infografis ? $infografis->file_name : '';
        $infografis_exists = $infografis && ! empty($infografis->file_name) && Storage::disk('public')->exists('uploads/iku/'.$kategori_aktif.'/'.$infografis->file_name);
        $infografis_path = $infografis_exists ? storage_path('app/public/uploads/iku/'.$kategori_aktif.'/'.$infografis_file) : '';

        // Sumber data
        $sumber = IkuPenilaian::where('kategori', $kategori_aktif)
            ->where('nama_kriteria', 'Sumber Data')
            ->first();
        $sumber_data = $sumber ? $sumber->toArray() : ['link_sumber' => '', 'file_sumber' => ''];

        // PDRB data
        $pdrb_data = IkuPdrb::where('kategori', $kategori_aktif)
            ->where('tahun', $tahun_aktif)
            ->first();

        if (! $pdrb_data) {
            $pdrb_data = (object) ['target' => 0, 'realitas' => 0, 'capaian' => 0];
        }

        // Perhitungan
        $hasil = 0;
        $nilai1 = 0;
        $nilai2 = 0;
        $total_ekraf = 0;
        $pdrb_adhb_ekraf = 0;
        $proporsi_ekraf = 0;
        $total_nusantara = 0;
        $total_mancanegara = 0;

        if ($kategori_aktif == 'Ekraf') {
            foreach ($ekraf_data as $e) {
                $total_ekraf += (float) $e['hasil_penjumlahan'];
            }
            foreach ($kriteria as $k) {
                if ($k['nama_kriteria'] == 'PDRB ADHB Sulawesi Tengah') {
                    $pdrb_adhb_ekraf = (float) $k['nilai'];
                }
            }
            if ($pdrb_adhb_ekraf > 0) {
                $proporsi_ekraf = ($total_ekraf / ($pdrb_adhb_ekraf * 1000000000)) * 100;
                $proporsi_ekraf = round($proporsi_ekraf, 4);
            }
            $nilai1 = $total_ekraf;
            $nilai2 = $pdrb_adhb_ekraf;
            $hasil = $proporsi_ekraf;
        } elseif ($kategori_aktif == 'Wisatawan') {
            $total_nusantara = IkuWisatawan::where('kategori', 'Wisatawan')
                ->where('subkategori', 'Nusantara')
                ->where('tahun', $tahun_aktif)
                ->sum('total');
            $total_mancanegara = IkuWisatawan::where('kategori', 'Wisatawan')
                ->where('subkategori', 'Mancanegara')
                ->where('tahun', $tahun_aktif)
                ->sum('total');
            $nilai1 = $total_nusantara;
            $nilai2 = $total_mancanegara;
            $hasil = $total_nusantara + $total_mancanegara;
        } else {
            if (count($kriteria) >= 2) {
                $nilai1 = (float) $kriteria[0]['nilai'];
                $nilai2 = (float) $kriteria[1]['nilai'];
            }
            if ($nilai2 > 0) {
                $hasil = ($nilai1 / $nilai2) * 100;
                $hasil = round($hasil, 4);
            }
        }

        // Format angka
        if ($kategori_aktif == 'Makan Minum') {
            $nilai1_formatted = number_format($nilai1, 2, ',', '.');
            $nilai2_formatted = number_format($nilai2, 2, ',', '.');
        } elseif ($kategori_aktif == 'Ekraf') {
            $nilai1_formatted = number_format($nilai1, 2, ',', '.');
            $nilai2_formatted = number_format($nilai2, 2, ',', '.');
        } else {
            $nilai1_formatted = number_format($nilai1, 0, ',', '.');
            $nilai2_formatted = number_format($nilai2, 0, ',', '.');
        }

        $hasil_formatted = number_format($hasil, 4, ',', '.');

        $target_db = (float) $pdrb_data->target;
        $realitas = $hasil;
        $capaian = 0;
        if ($target_db > 0) {
            $capaian = ($realitas / $target_db) * 100;
            $capaian = round($capaian, 4);
        }
        $capaian_formatted = number_format($capaian, 2, ',', '.');

        if ($kategori_aktif == 'Wisatawan') {
            $target_formatted = number_format($target_db, 0, ',', '.');
        } else {
            $target_formatted = number_format($target_db, 2, ',', '.');
        }

        $total_ekraf_formatted = number_format($total_ekraf / 1000000000, 2, ',', '.');
        $pdrb_adhb_ekraf_rp = $pdrb_adhb_ekraf;
        $pdrb_adhb_ekraf_rp_formatted = number_format($pdrb_adhb_ekraf_rp, 2, ',', '.');
        $proporsi_ekraf_formatted = number_format($proporsi_ekraf, 3, ',', '.');

        $predikat = $this->getPredikat($capaian_formatted);

        // Total per bulan untuk wisatawan
        $total_bulan = [];
        $total_keseluruhan = 0;
        if ($kategori_aktif == 'Wisatawan') {
            $bulan_keys = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
            foreach ($bulan_keys as $key) {
                $total_bulan[$key] = 0;
            }
            foreach ($wisatawan_data as $w) {
                foreach ($bulan_keys as $key) {
                    $total_bulan[$key] += (float) $w[$key];
                }
                $total_keseluruhan += (float) $w['total'];
            }
        }

        // Akumulasi data
        $akumulasi_data = [];
        if ($kategori_aktif == 'Wisatawan') {
            if ($subkategori_wisata == 'Akumulasi') {
                $data_nusantara = IkuWisatawan::where('kategori', 'Wisatawan')
                    ->where('subkategori', 'Nusantara')
                    ->where('tahun', $tahun_aktif)
                    ->orderBy('id')
                    ->get();
                $data_mancanegara = IkuWisatawan::where('kategori', 'Wisatawan')
                    ->where('subkategori', 'Mancanegara')
                    ->where('tahun', $tahun_aktif)
                    ->orderBy('id')
                    ->get();

                foreach ($wisatawan_kabkota as $index => $kab) {
                    $akumulasi_data[] = [
                        'kabkota' => $kab,
                        'nusantara' => $data_nusantara[$index] ?? null,
                        'mancanegara' => $data_mancanegara[$index] ?? null,
                    ];
                }
            }
        }

        return view('admin.iku', compact(
            'kategori_list', 'kategori_aktif',
            'tahun_list', 'tahun_aktif',
            'subkategori_wisata', 'subkategori_list',
            'kriteria', 'ekraf_data', 'wisatawan_data',
            'wisatawan_kabkota',
            'infografis_file', 'infografis_exists', 'infografis_path',
            'sumber_data',
            'nilai1_formatted', 'nilai2_formatted',
            'hasil_formatted', 'capaian_formatted',
            'target_formatted',
            'total_ekraf_formatted', 'pdrb_adhb_ekraf_rp_formatted',
            'proporsi_ekraf_formatted',
            'predikat',
            'total_bulan', 'total_keseluruhan',
            'total_nusantara', 'total_mancanegara',
            'akumulasi_data',
            'total_baru',
            'can_edit',
            'pdrb_data'
        ));
    }

    public function update(Request $request)
    {
        $this->forbidAdminBidangEdit();

        $kategori_aktif = $request->kategori ?? 'Makan Minum';
        $tahun_aktif = $request->tahun ?? '2025';
        $subkategori_wisata = $request->sub ?? 'Nusantara';

        // Update nilai kriteria
        if (isset($request->nilai) && is_array($request->nilai)) {
            foreach ($request->nilai as $id => $value) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
                $value_clean = (float) $value;

                IkuPenilaian::where('id', $id)
                    ->where('kategori', $kategori_aktif)
                    ->update(['nilai' => $value_clean]);
            }
        }

        // Update target & realisasi
        if (isset($request->target) && isset($request->realitas)) {
            $target = str_replace('.', '', $request->target);
            $target = str_replace(',', '.', $target);
            $target = (float) $target;

            $realitas = str_replace('.', '', $request->realitas);
            $realitas = str_replace(',', '.', $realitas);
            $realitas = (float) $realitas;

            $capaian = 0;
            if ($target > 0) {
                $capaian = ($realitas / $target) * 100;
                $capaian = round($capaian, 4);
            }

            IkuPdrb::updateOrCreate(
                ['kategori' => $kategori_aktif, 'tahun' => $tahun_aktif],
                ['target' => $target, 'realitas' => $realitas, 'capaian' => $capaian]
            );
        }

        // Update Ekraf
        if ($kategori_aktif == 'Ekraf' && isset($request->ekraf)) {
            IkuEkraf::where('kategori', $kategori_aktif)
                ->where('tahun', $tahun_aktif)
                ->delete();

            foreach ($request->ekraf as $data) {
                if (! isset($data['sektor'])) {
                    continue;
                }

                $koofisien = str_replace(',', '.', str_replace('.', '', $data['koofisien'] ?? '0'));
                $nilai_bps = str_replace(',', '.', str_replace('.', '', $data['nilai_bps'] ?? '0'));
                $koofisien = (float) $koofisien;
                $nilai_bps = (float) $nilai_bps;

                $jumlah_rp = $nilai_bps * 1000000000;
                $hasil_penjumlahan = $jumlah_rp * $koofisien;

                IkuEkraf::create([
                    'kategori' => $kategori_aktif,
                    'tahun' => $tahun_aktif,
                    'sektor' => $data['sektor'],
                    'koofisien' => $koofisien,
                    'nilai_bps' => $nilai_bps,
                    'jumlah_rp' => $jumlah_rp,
                    'hasil_penjumlahan' => $hasil_penjumlahan,
                ]);
            }
        }

        // Update PDRB ADHB Ekraf
        if (isset($request->pdrb_adhb_ekraf) && $kategori_aktif == 'Ekraf') {
            $pdrb_adhb = str_replace(',', '.', str_replace('.', '', $request->pdrb_adhb_ekraf));
            $pdrb_adhb = (float) $pdrb_adhb;

            IkuPenilaian::updateOrCreate(
                [
                    'kategori' => $kategori_aktif,
                    'tahun' => $tahun_aktif,
                    'nama_kriteria' => 'PDRB ADHB Sulawesi Tengah',
                ],
                ['nilai' => $pdrb_adhb, 'bobot' => 0, 'target' => 0, 'realisasi' => 0]
            );
        }

        // Update Wisatawan
        if ($kategori_aktif == 'Wisatawan' && isset($request->wisatawan)) {
            foreach ($request->wisatawan as $id => $data) {
                $values = [];
                foreach (['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'] as $month) {
                    $values[$month] = (float) str_replace('.', '', trim($data[$month] ?? '0'));
                }
                $total = array_sum($values);

                IkuWisatawan::where('id', $id)->update(array_merge($values, ['total' => $total]));
            }
        }

        // Update sumber data
        if (isset($request->link_sumber)) {
            IkuPenilaian::updateOrCreate(
                ['kategori' => $kategori_aktif, 'nama_kriteria' => 'Sumber Data'],
                ['link_sumber' => $request->link_sumber]
            );
        }

        // Upload file sumber
        if ($request->hasFile('file_sumber')) {
            $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
            $max_size = 10 * 1024 * 1024;

            $existing = IkuPenilaian::where('kategori', $kategori_aktif)
                ->where('nama_kriteria', 'Sumber Data')
                ->first();
            $old_files = $existing && $existing->file_sumber ? explode('|', $existing->file_sumber) : [];

            $uploaded_files = [];
            foreach ($request->file('file_sumber') as $file) {
                if ($file->isValid()) {
                    $ext = $file->getClientOriginalExtension();
                    if (in_array($ext, $allowed) && $file->getSize() <= $max_size) {
                        $file_name = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                        $file->storeAs('uploads/iku/'.$kategori_aktif, $file_name, 'public');
                        $uploaded_files[] = $file_name;
                    }
                }
            }

            $all_files = array_merge($old_files, $uploaded_files);
            $all_files = array_slice($all_files, 0, 15);
            $file_names = implode('|', $all_files);

            IkuPenilaian::updateOrCreate(
                ['kategori' => $kategori_aktif, 'nama_kriteria' => 'Sumber Data'],
                ['file_sumber' => $file_names]
            );
        }

        return redirect()->route('admin.iku.index', [
            'kategori' => $kategori_aktif,
            'tahun' => $tahun_aktif,
            'sub' => $subkategori_wisata,
        ])->with('success', 'Data berhasil diperbarui!');
    }

    public function uploadInfografis(Request $request)
    {
        $this->forbidAdminBidangEdit();

        $kategori = $request->kategori ?? 'Makan Minum';

        if ($request->hasFile('infografis')) {
            $file = $request->file('infografis');
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = $file->getClientOriginalExtension();

            if (in_array($ext, $allowed) && $file->getSize() <= 5 * 1024 * 1024) {
                Storage::disk('public')->delete('uploads/iku/'.$kategori.'/'.$existing->file_name);

                $file_name = 'infografis_'.$kategori.'_'.time().'.'.$ext;
                $file->storeAs('uploads/iku/'.$kategori, $file_name, 'public');

                IkuInfografis::updateOrCreate(
                    ['kategori' => $kategori],
                    ['file_name' => $file_name]
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Infografis berhasil diupload!',
                    'file_path' => Storage::disk('public')->url('uploads/iku/'.$kategori.'/'.$file_name),
                    'file_name' => $file_name,
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Upload gagal!']);
    }

    public function deleteInfografis(Request $request)
    {
        $this->forbidAdminBidangEdit();

        $kategori = $request->kategori ?? 'Makan Minum';

        $infografis = IkuInfografis::where('kategori', $kategori)->first();
        if ($infografis && $infografis->file_name) {
            Storage::disk('public')->delete('uploads/iku/'.$kategori.'/'.$infografis->file_name);
            $infografis->delete();
        }

        return redirect()->route('admin.iku.index', [
            'kategori' => $kategori,
            'tahun' => $request->tahun ?? '2025',
            'sub' => $request->sub ?? 'Nusantara',
        ])->with('success', 'Infografis berhasil dihapus!');
    }

    public function deleteFile(Request $request)
    {
        $this->forbidAdminBidangEdit();

        $kategori = $request->kategori ?? 'Makan Minum';
        $filename = $request->filename;

        $sumber = IkuPenilaian::where('kategori', $kategori)
            ->where('nama_kriteria', 'Sumber Data')
            ->first();

        if ($sumber && $sumber->file_sumber) {
            $files = explode('|', $sumber->file_sumber);
            $new_files = array_filter($files, function ($f) use ($filename) {
                return $f !== $filename;
            });

            Storage::disk('public')->delete('uploads/iku/'.$kategori.'/'.$filename);

            $sumber->update(['file_sumber' => implode('|', $new_files)]);
        }

        return redirect()->route('admin.iku.index', [
            'kategori' => $kategori,
            'tahun' => $request->tahun ?? '2025',
            'sub' => $request->sub ?? 'Nusantara',
        ])->with('success', 'File berhasil dihapus!');
    }
}
