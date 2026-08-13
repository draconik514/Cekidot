<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenIki extends Model
{
    protected $table = 'dokumen_iki';

    protected $fillable = [
        'judul', 'kategori', 'deskripsi', 'file_dokumen', 'tipe_konten',
        'link_url', 'file_type', 'file_size', 'tahun', 'bidang_id', 'urutan', 'status',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}
