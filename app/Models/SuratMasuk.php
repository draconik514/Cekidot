<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';

    protected $fillable = [
        'nama_pengirim', 'email', 'no_hp', 'instansi',
        'perihal', 'isi', 'file', 'status',
        'asal_instansi', 'tanggal_masuk',
        'nomor_surat', 'tanggal_surat', 'keterangan',
        'file_surat', 'ip_address', 'dibaca',
    ];
}