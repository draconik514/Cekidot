<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\FolderDokumen;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderDokumenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = FolderDokumen::with(['pembuat', 'bidang']);

        if ($user->isAdminDivisi()) {
            $query->where(fn ($q) => $q->where('divisi', $user->divisi)->orWhere('divisi', 'Semua'));
<<<<<<< HEAD
=======
        }

        if ($user->isAdminBidang()) {
            $query->where('bidang_id', $user->bidang_id);
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
        }

        $folders = $query->orderBy('nama')->get();
        $total_baru = SuratMasuk::where('status', 'baru')->count();
        $divisi_list = ['Kepegawaian', 'Program', 'Keuangan', 'Ekraf', 'Destinasi', 'Pemasaran', 'Sdm', 'Semua'];
        $bidang_list = Bidang::orderBy('nama_bidang')->get();

        return view('admin.folder-dokumen', compact('folders', 'total_baru', 'divisi_list', 'bidang_list'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string',
<<<<<<< HEAD
            'parent_id' => 'nullable|exists:folder_dokumen,id',
            'divisi' => 'required',
        ]);

        $parent = $request->filled('parent_id') ? FolderDokumen::findOrFail($request->parent_id) : null;
        $divisi = $parent ? $parent->divisi : $request->divisi;
=======
            'divisi' => 'required',
        ]);

        $bidangId = $user->isAdminBidang() ? $user->bidang_id : $request->bidang_id;
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618

        FolderDokumen::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
<<<<<<< HEAD
            'divisi' => $divisi,
            'parent_id' => $parent?->id,
=======
            'divisi' => $request->divisi,
            'bidang_id' => $bidangId,
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
            'status' => 'aktif',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Folder berhasil dibuat!');
    }

    public function update(Request $request, FolderDokumen $folder)
    {
<<<<<<< HEAD
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

=======
        $user = Auth::user();

        if ($user->isAdminBidang() && $folder->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda hanya dapat mengelola folder bidang Anda.');
        }

        $request->validate(['nama' => 'required|string']);
        $folder->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'divisi' => $request->divisi,
            'bidang_id' => $user->isAdminBidang() ? $user->bidang_id : ($request->bidang_id ?? $folder->bidang_id),
        ]);

>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
        return back()->with('success', 'Folder berhasil diupdate!');
    }

    public function destroy(FolderDokumen $folder)
    {
<<<<<<< HEAD
        if ($folder->uploads()->count() > 0 || $folder->children()->count() > 0) {
            return back()->with('error', 'Folder tidak bisa dihapus karena masih berisi dokumen atau sub-folder!');
=======
        $user = Auth::user();

        if ($user->isAdminBidang() && $folder->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda hanya dapat mengelola folder bidang Anda.');
        }

        if ($folder->uploads()->count() > 0) {
            return back()->with('error', 'Folder tidak bisa dihapus karena masih ada dokumen di dalamnya!');
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
        }
        $folder->delete();

        return back()->with('success', 'Folder berhasil dihapus!');
    }
}
