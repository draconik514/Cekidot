<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenIki;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DokumenIkiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tahun_list = range(2025, 2030);
        $tahun_aktif = request('tahun', date('Y'));

        if (! in_array((int) $tahun_aktif, $tahun_list)) {
            $tahun_aktif = $tahun_list[0];
        }

        $query = DokumenIki::where('tahun', $tahun_aktif);

        if ($user->isAdminDivisi()) {
            $query->where('divisi', $user->divisi);
        }

        $kategori_aktif = request('kategori', '');
        if ($kategori_aktif !== '') {
            $query->where('kategori', $kategori_aktif);
        }

        $dokumen = $query->orderBy('urutan')->get();
        $total_baru = 0;
        $divisi_list = ['Kepegawaian','Program','Keuangan','Ekraf','Destinasi','Pemasaran','Sdm'];
        $is_admin_divisi = $user->isAdminDivisi();
        $kategori_list = DokumenIki::where('kategori', '!=', '')->whereNotNull('kategori')
            ->distinct('kategori')
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('admin.iki', compact('dokumen', 'tahun_aktif', 'tahun_list', 'total_baru', 'divisi_list', 'is_admin_divisi', 'kategori_list', 'kategori_aktif'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

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

        $divisi = $user->isAdminDivisi() ? $user->divisi : $request->divisi;

        $file_name = '';
        $file_type = '';
        $file_size = 0;

        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $file_name = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->storeAs('uploads/iki', $file_name, 'public');
            $file_type = $file->getClientOriginalExtension();
            $file_size = $file->getSize();
        }

        $max_urutan = DokumenIki::where('tahun', $request->tahun)->max('urutan') ?? 0;

        DokumenIki::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori ?? null,
            'deskripsi' => $request->deskripsi,
            'file_dokumen' => $file_name,
            'tipe_konten' => $request->tipe_konten,
            'link_url' => $request->tipe_konten == 'link' ? $request->link_url : null,
            'file_type' => $file_type,
            'file_size' => $file_size,
            'tahun' => $request->tahun,
            'divisi' => $divisi,
            'urutan' => $max_urutan + 1,
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.iki.index', ['tahun' => $request->tahun])
            ->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $id = $request->edit_id;
        $dokumen = DokumenIki::findOrFail($id);

        if ($user->isAdminDivisi() && $dokumen->divisi !== $user->divisi) {
            abort(403, 'Anda hanya dapat mengelola dokumen divisi Anda.');
        }

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
            'kategori' => $request->edit_kategori ?? null,
            'deskripsi' => $request->edit_deskripsi,
            'tahun' => $request->edit_tahun,
            'tipe_konten' => $request->edit_tipe_konten,
        ];

        if ($user->isAdminDivisi()) {
            $data['divisi'] = $user->divisi;
        }

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
                Storage::disk('public')->delete('uploads/iki/'.$dokumen->file_dokumen);
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

                Storage::disk('public')->delete('uploads/iki/'.$dokumen->file_dokumen);

                $file_name = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $file->storeAs('uploads/iki', $file_name, 'public');
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

        return redirect()->route('admin.iki.index', ['tahun' => $request->edit_tahun])
            ->with('success', 'Dokumen berhasil diupdate!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $dokumen = DokumenIki::findOrFail($id);

        if ($user->isAdminDivisi() && $dokumen->divisi !== $user->divisi) {
            abort(403, 'Anda hanya dapat mengelola dokumen divisi Anda.');
        }

        if ($dokumen->tipe_konten == 'file' && $dokumen->file_dokumen) {
            Storage::disk('public')->delete('uploads/iki/'.$dokumen->file_dokumen);
        }

        $dokumen->delete();

        return redirect()->route('admin.iki.index')->with('success', 'Dokumen berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $user = Auth::user();
        $dokumen = DokumenIki::findOrFail($id);

        if ($user->isAdminDivisi() && $dokumen->divisi !== $user->divisi) {
            abort(403, 'Anda hanya dapat mengelola dokumen divisi Anda.');
        }

        $new_status = $dokumen->status == 'aktif' ? 'nonaktif' : 'aktif';
        $dokumen->update(['status' => $new_status]);

        return redirect()->route('admin.iki.index')->with('success', 'Status dokumen berhasil diubah!');
    }
}
