<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkuPdrb extends Model
{
    protected $table = 'iku_pdrb';
    
    protected $fillable = [
        'kategori', 'tahun', 'target', 'realitas', 'capaian'
    ];
}