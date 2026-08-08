<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenAkip extends Model
{
    protected $table = 'dokumen_akip';
    
    protected $fillable = [
        'judul', 'deskripsi', 'file_dokumen', 'tipe_konten',
        'link_url', 'file_type', 'file_size', 'tahun', 'urutan', 'status'
    ];
}