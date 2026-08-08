<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkuEkraf extends Model
{
    protected $table = 'iku_ekraf';
    
    protected $fillable = [
        'kategori', 'tahun', 'sektor', 'koofisien',
        'nilai_bps', 'jumlah_rp', 'hasil_penjumlahan'
    ];
}