<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratMasuk;

class SuratMasukController extends Controller
{
    public function index()
    {
        $surat = SuratMasuk::orderBy('id', 'desc')->get();
        $total_baru = SuratMasuk::where('status', 'baru')->count();
        return view('admin.surat-masuk', compact('surat', 'total_baru'));
    }

    public function destroy(Request $request)
    {
        $id = $request->delete_id;
        $surat = SuratMasuk::findOrFail($id);
        
        if ($surat->file_surat) {
            $file_path = public_path('uploads/surat/' . $surat->file_surat);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $surat->delete();
        return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil dihapus!');
    }

    public function tandaiDibaca($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $surat->update([
            'dibaca' => true,
            'status' => 'dibaca',
        ]);
        return redirect()->route('admin.surat.index')->with('success', 'Surat ditandai sudah dibaca!');
    }
}