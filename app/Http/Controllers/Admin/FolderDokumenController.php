<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FolderDokumen;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderDokumenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = FolderDokumen::with('pembuat');

        if ($user->isAdminDivisi()) {
            $query->where(fn ($q) => $q->where('divisi', $user->divisi)->orWhere('divisi', 'Semua'));
        }

        $folders = $query->orderBy('nama')->get();
        $total_baru = SuratMasuk::where('status', 'baru')->count();
        $divisi_list = ['Kepegawaian', 'Program', 'Keuangan', 'Ekraf', 'Destinasi', 'Pemasaran', 'Sdm', 'Semua'];

        return view('admin.folder-dokumen', compact('folders', 'total_baru', 'divisi_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'parent_id' => 'nullable|exists:folder_dokumen,id',
            'divisi' => 'required',
        ]);

        $parent = $request->filled('parent_id') ? FolderDokumen::findOrFail($request->parent_id) : null;
        $divisi = $parent ? $parent->divisi : $request->divisi;

        FolderDokumen::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'divisi' => $divisi,
            'parent_id' => $parent?->id,
            'status' => 'aktif',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Folder berhasil dibuat!');
    }

    public function update(Request $request, FolderDokumen $folder)
    {
        $request->validate([
            'nama' => 'required|string',
            'parent_id' => 'nullable|exists:folder_dokumen,id',
        ]);

        $parent = $request->filled('parent_id') ? FolderDokumen::findOrFail($request->parent_id) : null;

        $folder->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'divisi' => $parent ? $parent->divisi : $folder->divisi,
            'parent_id' => $parent?->id,
        ]);

        return back()->with('success', 'Folder berhasil diupdate!');
    }

    public function destroy(FolderDokumen $folder)
    {
        if ($folder->uploads()->count() > 0 || $folder->children()->count() > 0) {
            return back()->with('error', 'Folder tidak bisa dihapus karena masih berisi dokumen atau sub-folder!');
        }
        $folder->delete();

        return back()->with('success', 'Folder berhasil dihapus!');
    }
}
