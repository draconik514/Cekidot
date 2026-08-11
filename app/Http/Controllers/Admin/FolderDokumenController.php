<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FolderDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderDokumenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = FolderDokumen::with('pembuat');

        if ($user->isAdminDivisi()) {
            $query->where(fn($q) => $q->where('divisi', $user->divisi)->orWhere('divisi', 'Semua'));
        }

        $folders = $query->orderBy('nama')->get();
        $total_baru = \App\Models\SuratMasuk::where('status', 'baru')->count();
        $divisi_list = ['Kepegawaian', 'Program', 'Keuangan', 'Ekraf', 'Destinasi', 'Pemasaran', 'Sdm', 'Semua'];

        return view('admin.folder-dokumen', compact('folders', 'total_baru', 'divisi_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string',
            'divisi' => 'required',
        ]);

        FolderDokumen::create([
            'nama'       => $request->nama,
            'deskripsi'  => $request->deskripsi,
            'divisi'     => $request->divisi,
            'status'     => 'aktif',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Folder berhasil dibuat!');
    }

    public function update(Request $request, FolderDokumen $folder)
    {
        $request->validate(['nama' => 'required|string']);
        $folder->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'divisi'    => $request->divisi,
        ]);
        return back()->with('success', 'Folder berhasil diupdate!');
    }

    public function destroy(FolderDokumen $folder)
    {
        if ($folder->uploads()->count() > 0) {
            return back()->with('error', 'Folder tidak bisa dihapus karena masih ada dokumen di dalamnya!');
        }
        $folder->delete();
        return back()->with('success', 'Folder berhasil dihapus!');
    }
}
