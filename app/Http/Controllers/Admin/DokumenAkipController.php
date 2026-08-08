<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DokumenAkip;
use Illuminate\Support\Facades\Validator;

class DokumenAkipController extends Controller
{
    public function index()
    {
        $tahun_list = range(2025, 2030);
        $tahun_aktif = request('tahun', date('Y'));
        
        if (!in_array((int)$tahun_aktif, $tahun_list)) {
            $tahun_aktif = $tahun_list[0];
        }
        
        $dokumen = DokumenAkip::where('tahun', $tahun_aktif)->orderBy('urutan')->get();
        $total_baru = \App\Models\SuratMasuk::where('status', 'baru')->count();
        
        return view('admin.akip', compact('dokumen', 'tahun_aktif', 'tahun_list', 'total_baru'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string',
            'tahun' => 'required|integer',
            'tipe_konten' => 'required|in:file,link',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Judul wajib diisi!')->withInput();
        }

        if ($request->tipe_konten == 'file') {
            $validator = Validator::make($request->all(), [
                'file_dokumen' => 'required|file|max:51200',
            ]);
            if ($validator->fails()) {
                return back()->with('error', 'Silakan pilih file untuk diupload!')->withInput();
            }
        }

        if ($request->tipe_konten == 'link') {
            $validator = Validator::make($request->all(), [
                'link_url' => 'required|url',
            ]);
            if ($validator->fails()) {
                return back()->with('error', 'Silakan masukkan URL/Link dokumen yang valid!')->withInput();
            }
        }

        $file_name = '';
        $file_type = '';
        $file_size = 0;

        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/akip'), $file_name);
            $file_type = $file->getClientOriginalExtension();
            $file_size = $file->getSize();
        }

        $max_urutan = DokumenAkip::where('tahun', $request->tahun)->max('urutan') ?? 0;

        DokumenAkip::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_dokumen' => $file_name,
            'tipe_konten' => $request->tipe_konten,
            'link_url' => $request->tipe_konten == 'link' ? $request->link_url : null,
            'file_type' => $file_type,
            'file_size' => $file_size,
            'tahun' => $request->tahun,
            'urutan' => $max_urutan + 1,
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.akip.index', ['tahun' => $request->tahun])
            ->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        $id = $request->edit_id;
        $dokumen = DokumenAkip::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'edit_judul' => 'required|string',
            'edit_tahun' => 'required|integer',
            'edit_tipe_konten' => 'required|in:file,link',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Judul wajib diisi!')->withInput();
        }

        $data = [
            'judul' => $request->edit_judul,
            'deskripsi' => $request->edit_deskripsi,
            'tahun' => $request->edit_tahun,
            'tipe_konten' => $request->edit_tipe_konten,
        ];

        if ($request->edit_tipe_konten == 'link') {
            $validator = Validator::make($request->all(), [
                'edit_link_url' => 'required|url',
            ]);
            if ($validator->fails()) {
                return back()->with('error', 'Silakan masukkan URL/Link dokumen yang valid!')->withInput();
            }
            $data['link_url'] = $request->edit_link_url;
            $data['file_dokumen'] = null;
            $data['file_type'] = '';
            $data['file_size'] = 0;
            
            if ($dokumen->file_dokumen && $dokumen->tipe_konten == 'file') {
                $file_path = public_path('uploads/akip/' . $dokumen->file_dokumen);
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        } else {
            $data['link_url'] = null;
            
            if ($request->hasFile('edit_file')) {
                $file = $request->file('edit_file');
                $validator = Validator::make($request->all(), [
                    'edit_file' => 'file|max:51200',
                ]);
                if ($validator->fails()) {
                    return back()->with('error', 'Ukuran file maksimal 50MB!')->withInput();
                }
                
                if ($dokumen->file_dokumen) {
                    $old_path = public_path('uploads/akip/' . $dokumen->file_dokumen);
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
                
                $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $file->move(public_path('uploads/akip'), $file_name);
                $data['file_dokumen'] = $file_name;
                $data['file_type'] = $file->getClientOriginalExtension();
                $data['file_size'] = $file->getSize();
            } elseif ($dokumen->tipe_konten == 'file' && $dokumen->file_dokumen) {
                $data['file_dokumen'] = $dokumen->file_dokumen;
                $data['file_type'] = $dokumen->file_type;
                $data['file_size'] = $dokumen->file_size;
            } else {
                return back()->with('error', 'Silakan upload file! (Tidak ada file yang tersimpan)')->withInput();
            }
        }

        $dokumen->update($data);

        return redirect()->route('admin.akip.index', ['tahun' => $request->edit_tahun])
            ->with('success', 'Dokumen berhasil diupdate!');
    }

    public function destroy($id)
    {
        $dokumen = DokumenAkip::findOrFail($id);
        
        if ($dokumen->tipe_konten == 'file' && $dokumen->file_dokumen) {
            $file_path = public_path('uploads/akip/' . $dokumen->file_dokumen);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $dokumen->delete();
        return redirect()->route('admin.akip.index')->with('success', 'Dokumen berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $dokumen = DokumenAkip::findOrFail($id);
        $new_status = $dokumen->status == 'aktif' ? 'nonaktif' : 'aktif';
        $dokumen->update(['status' => $new_status]);
        return redirect()->route('admin.akip.index')->with('success', 'Status dokumen berhasil diubah!');
    }
}