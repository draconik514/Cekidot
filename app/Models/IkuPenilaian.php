<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkuPenilaian extends Model
{
    protected $table = 'iku_penilaian';
    
    protected $fillable = [
        'kategori', 'tahun', 'nama_kriteria', 'nilai', 'bobot',
        'target', 'realisasi', 'link_sumber', 'file_sumber'
    ];
}