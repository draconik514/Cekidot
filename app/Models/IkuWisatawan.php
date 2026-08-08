<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkuWisatawan extends Model
{
    protected $table = 'iku_wisatawan';
    
    protected $fillable = [
        'kategori', 'subkategori', 'tahun', 'kabkota',
        'januari', 'februari', 'maret', 'april', 'mei', 'juni',
        'juli', 'agustus', 'september', 'oktober', 'november', 'desember',
        'total'
    ];
}