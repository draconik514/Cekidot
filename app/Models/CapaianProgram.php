<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapaianProgram extends Model
{
    protected $table = 'capaian_program';
    
    protected $fillable = [
        'program', 'sasaran', 'indikator', 'target', 'realisasi',
        'capaian', 'frekwensi', 'sumber_data', 'file_sumber',
        'penanggung_jawab', 'tahun'
    ];
}