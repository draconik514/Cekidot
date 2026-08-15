<?php

namespace App\Http\Controllers;

use App\Models\FolderDokumen;
use App\Models\LogAktivitas;
use App\Models\UploadAnggota;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    private function folderScope($user)
    {
        return fn ($q) => $q->where('status', 'aktif')
            ->where(fn ($qq) => $qq->where('divisi', $user->divisi)->orWhere('divisi', 'Semua'));
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $parents = FolderDokumen::with(['uploads.user', 'children.uploads.user'])
            ->whereNull('parent_id')
            ->where($this->folderScope($user))
            ->orderBy('nama')->get();

        $search = trim((string) $request->input('q'));
        $results = collect();

        if ($search !== '') {
            $results = UploadAnggota::with(['folder', 'user'])
                ->whereHas('folder', $this->folderScope($user))
                ->where(fn ($q) => $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhere('tanggal_upload', 'like', "%{$search}%"))
                ->orderByDesc('tanggal_upload')->get();
        }

        $uploadSelect = [];
        $total_dokumen = 0;
        foreach ($parents as $parent) {
            $uploadSelect[$parent->id] = $parent->nama;
            $total_dokumen += $parent->uploads->count();
            foreach ($parent->children as $child) {
                $uploadSelect[$child->id] = '— '.$child->nama;
                $total_dokumen += $child->uploads->count();
            }
        }

        $total_folder = $parents->count() + $parents->sum(fn ($p) => $p->children->count());
        $dokumen_saya = UploadAnggota::where('user_id', $user->id)->count();

        return view('anggota.dashboard', compact('parents', 'results', 'search', 'uploadSelect', 'total_dokumen', 'total_folder', 'dokumen_saya'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'folder_id' => 'required|exists:folder_dokumen,id',
            'judul' => 'required|string',
            'file_dokumen' => 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,webp,txt,zip,rar',
            'tanggal_upload' => 'required|date',
        ]);

        $folder = FolderDokumen::where('id', $request->folder_id)->where($this->folderScope($user))->first();
        abort_unless($folder, 403);

        $file = $request->file('file_dokumen');
        $file_name = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $file->storeAs('uploads/anggota', $file_name, 'public');

        $tanggal = Carbon::parse($request->tanggal_upload);

        $upload = UploadAnggota::create([
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

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'upload_id' => $upload->id,
            'aksi' => 'upload',
            'detail' => $request->judul,
        ]);

        return back()->with('success', 'Dokumen berhasil diupload!');
    }

    public function download(UploadAnggota $upload)
    {
        $user = Auth::user();
        $folder = $upload->folder;
        abort_unless($folder && in_array($folder->divisi, [$user->divisi, 'Semua']), 403);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'upload_id' => $upload->id,
            'aksi' => 'unduh',
            'detail' => $upload->judul,
        ]);

        return Storage::disk('public')->download(
            'uploads/anggota/'.$upload->file_name,
            $upload->judul.'.'.$upload->file_type
        );
    }
}
