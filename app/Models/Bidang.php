<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    protected $table = 'bidang';

    protected $fillable = [
        'nama_bidang',
        'kode_bidang',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function arsipSurat(): HasMany
    {
        return $this->hasMany(ArsipSurat::class);
    }
}
