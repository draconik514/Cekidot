<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'gambar', 'judul', 'urutan', 'status'
    ];
}