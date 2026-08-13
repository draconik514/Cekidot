<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipSurat extends Model
{
    protected $table = 'arsip_surat';

    protected $fillable = [
        'bidang_id',
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'jenis_surat',
        'file_path',
        'file_name',
        'file_size',
        'uploaded_by',
        'uploaded_at',
        'keterangan',
        'is_deleted',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date:Y-m-d',
            'uploaded_at' => 'datetime',
            'is_deleted' => 'boolean',
        ];
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_deleted', false);
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
