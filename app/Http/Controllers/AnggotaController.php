<?php

namespace App\Http\Controllers;

use App\Models\FolderDokumen;
use App\Models\UploadAnggota;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $folders = FolderDokumen::where('status', 'aktif')
            ->where(fn ($q) => $q->where('divisi', $user->divisi)->orWhere('divisi', 'Semua'))
            ->orderBy('nama')->get();

        $uploads = UploadAnggota::where('user_id', $user->id)
            ->with('folder')->orderByDesc('created_at')->get();

        return view('anggota.dashboard', compact('folders', 'uploads'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'folder_id' => 'required|exists:folder_dokumen,id',
            'judul' => 'required|string',
            'file_dokumen' => 'required|file|max:51200',
            'tanggal_upload' => 'required|date',
        ]);

        $folder = FolderDokumen::where('id', $request->folder_id)
            ->where(fn ($q) => $q->where('divisi', $user->divisi)->orWhere('divisi', 'Semua'))
            ->firstOrFail();

        $file = $request->file('file_dokumen');
        $file_name = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $file->storeAs('uploads/anggota', $file_name, 'public');

        $tanggal = Carbon::parse($request->tanggal_upload);

        UploadAnggota::create([
            'user_id' => Auth::id(),
            'folder_id' => $folder->id,
            'judul' => $request->judul,
            'file_name' => $file_name,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'keterangan' => $request->keterangan,
            'tahun' => $tanggal->year,
            'bulan' => $tanggal->month,
            'tanggal_upload' => $request->tanggal_upload,
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Dokumen berhasil diupload!');
    }

    public function destroy(UploadAnggota $upload)
    {
        if ($upload->user_id !== Auth::id()) {
            abort(403);
        }
        Storage::disk('public')->delete('uploads/anggota/'.$upload->file_name);
        $upload->delete();

        return back()->with('success', 'Dokumen berhasil dihapus!');
    }
}
