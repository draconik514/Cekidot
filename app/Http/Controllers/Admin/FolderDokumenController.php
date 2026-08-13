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
        }

        if ($user->isAdminBidang()) {
            $query->where('bidang_id', $user->bidang_id);
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
            'divisi' => 'required',
        ]);

        $bidangId = $user->isAdminBidang() ? $user->bidang_id : $request->bidang_id;

        FolderDokumen::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'divisi' => $request->divisi,
            'bidang_id' => $bidangId,
            'status' => 'aktif',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Folder berhasil dibuat!');
    }

    public function update(Request $request, FolderDokumen $folder)
    {
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

        return back()->with('success', 'Folder berhasil diupdate!');
    }

    public function destroy(FolderDokumen $folder)
    {
        $user = Auth::user();

        if ($user->isAdminBidang() && $folder->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda hanya dapat mengelola folder bidang Anda.');
        }

        if ($folder->uploads()->count() > 0) {
            return back()->with('error', 'Folder tidak bisa dihapus karena masih ada dokumen di dalamnya!');
        }
        $folder->delete();

        return back()->with('success', 'Folder berhasil dihapus!');
    }
}
