<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SuratPublicController extends Controller
{
    public function create()
    {
        return view('public.kirim-surat');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|string',
            'tanggal_surat' => 'required|date',
            'asal' => 'required|string',
            'nama_pengirim' => 'required|string',
            'no_hp' => 'required|string',
            'perihal' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file_name = '';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->storeAs('uploads/surat', $file_name, 'public');
        }

        SuratMasuk::create([
            'nomor_surat' => $request->nomor_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'asal_instansi' => $request->asal,
            'nama_pengirim' => $request->nama_pengirim,
            'no_hp' => $request->no_hp,
            'perihal' => $request->perihal,
            'keterangan' => $request->keterangan,
            'file_surat' => $file_name,
            'ip_address' => $request->ip(),
            'dibaca' => false,
            'status' => 'baru',
            'tanggal_masuk' => now(),
        ]);

        return back()->with('success', 'Surat berhasil dikirim!');
    }
}