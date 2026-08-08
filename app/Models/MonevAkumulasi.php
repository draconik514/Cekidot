<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonevAkumulasi extends Model
{
    protected $table = 'monev_akumulasi';
    
    protected $fillable = [
        'tahun', 'sub_kegiatan', 'indikator',
        'target_ik', 'target_keu', 'realisasi_ik', 'realisasi_keu',
        'capaian_ik', 'capaian_keu', 'predikat_ik', 'predikat_keu', 'status'
    ];
}