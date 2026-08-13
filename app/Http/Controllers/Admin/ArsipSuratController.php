<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArsipSurat;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArsipSuratController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $bidangId = $user->isSuperAdmin()
            ? $request->get('bidang_id')
            : $user->bidang_id;

        $query = ArsipSurat::aktif()->with(['bidang', 'uploader'])->latest('uploaded_at');

        if ($bidangId) {
            $query->where('bidang_id', $bidangId);
        }

        if ($request->filled('search')) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_surat', 'like', "%{$keyword}%")
                    ->orWhere('perihal', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_surat', $request->get('jenis'));
        }

        $arsip = $query->paginate(15)->withQueryString();

        $rekap = ArsipSurat::aktif()
            ->selectRaw('bidang_id, COUNT(*) as total, SUM(jenis_surat = "masuk") as masuk, SUM(jenis_surat = "keluar") as keluar, SUM(jenis_surat = "internal") as internal')
            ->groupBy('bidang_id')
            ->pluck('total', 'bidang_id');

        $bidangList = Bidang::orderBy('nama_bidang')->get();

        $totalMasuk = ArsipSurat::aktif()->where('jenis_surat', 'masuk')->where(fn ($q) => $this->scopeBidang($q, $user, $bidangId))->count();
        $totalKeluar = ArsipSurat::aktif()->where('jenis_surat', 'keluar')->where(fn ($q) => $this->scopeBidang($q, $user, $bidangId))->count();
        $totalInternal = ArsipSurat::aktif()->where('jenis_surat', 'internal')->where(fn ($q) => $this->scopeBidang($q, $user, $bidangId))->count();
        $totalArsip = $totalMasuk + $totalKeluar + $totalInternal;

        return view('admin.arsip-surat', compact(
            'arsip',
            'rekap',
            'bidangList',
            'totalArsip',
            'totalMasuk',
            'totalKeluar',
            'totalInternal'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user->isAdminBidang()) {
            abort(403, 'Hanya admin bidang yang dapat mengunggah arsip.');
        }

        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string|max:255',
            'jenis_surat' => 'required|in:masuk,keluar,internal',
            'file_surat' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'keterangan' => 'nullable|string',
        ]);

        $bidang = $user->bidang;
        if (! $bidang) {
            return back()->with('error', 'Bidang akun belum diatur, hubungi Super Admin.');
        }

        $file = $request->file('file_surat');
        $tahun = (int) $request->date('tanggal_surat')->format('Y');
        $bulan = $request->date('tanggal_surat')->format('m');
        $folder = $bidang->kode_bidang.'/'.$tahun.'/'.$bulan;

        $filePath = $file->store($folder, 'arsip');

        ArsipSurat::create([
            'bidang_id' => $bidang->id,
            'nomor_surat' => $request->nomor_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'perihal' => $request->perihal,
            'jenis_surat' => $request->jenis_surat,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $user->id,
            'uploaded_at' => now(),
            'keterangan' => $request->keterangan,
            'is_deleted' => false,
        ]);

        return redirect()->route('admin.arsip.index')->with('success', 'Arsip surat berhasil diunggah!');
    }

    public function download(ArsipSurat $arsip)
    {
        $user = Auth::user();

        if (! $user->isSuperAdmin() && $arsip->bidang_id !== $user->bidang_id) {
            abort(403, 'Akses ditolak.');
        }

        return Storage::disk('arsip')->download($arsip->file_path, $arsip->file_name);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        if (! $user->isAdminBidang()) {
            abort(403, 'Hanya admin bidang yang dapat menghapus arsip.');
        }

        $arsip = ArsipSurat::findOrFail($request->delete_id);

        if ($arsip->uploaded_by !== $user->id) {
            abort(403, 'Anda hanya dapat menghapus arsip milik sendiri.');
        }

        Storage::disk('arsip')->delete($arsip->file_path);
        $arsip->update(['is_deleted' => true]);

        return redirect()->route('admin.arsip.index')->with('success', 'Arsip surat berhasil dihapus!');
    }

    public function cetak(Request $request)
    {
        $user = Auth::user();

        $query = ArsipSurat::aktif()->with(['bidang', 'uploader'])->orderBy('uploaded_at');

        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->get('bidang_id'));
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_surat', [$request->get('dari'), $request->get('sampai')]);
        }

        $arsip = $query->get();
        $bidangNama = $request->filled('bidang_id')
            ? optional(Bidang::find($request->get('bidang_id')))->nama_bidang
            : 'Semua Bidang';

        return view('admin.arsip-cetak', compact('arsip', 'bidangNama'));
    }

    protected function scopeBidang($q, $user, $bidangId)
    {
        if ($user->isSuperAdmin() && ! $bidangId) {
            return $q;
        }

        return $q->where('bidang_id', $user->isSuperAdmin() ? $bidangId : $user->bidang_id);
    }
}
