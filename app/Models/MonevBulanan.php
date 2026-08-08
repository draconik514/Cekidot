<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonevBulanan extends Model
{
    protected $table = 'monev_bulanan';
    
    protected $fillable = [
        'tahun', 'bulan', 'sub_kegiatan', 'indikator',
        'target_ik', 'target_keu', 'realisasi_ik', 'realisasi_keu',
        'capaian_ik', 'capaian_keu', 'sumber_data',
        'faktor_penghambat', 'faktor_pendukung'
    ];
}