<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkuInfografis extends Model
{
    protected $table = 'iku_infografis';
    
    protected $fillable = [
        'kategori', 'file_name'
    ];
}