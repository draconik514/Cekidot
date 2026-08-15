<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadAnggota extends Model
{
    protected $table = 'upload_anggota';

    protected $fillable = ['user_id', 'folder_id', 'judul', 'file_name', 'file_type', 'file_size', 'keterangan', 'tahun', 'bulan', 'tanggal_upload', 'status'];

    protected $casts = ['tanggal_upload' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function folder()
    {
        return $this->belongsTo(FolderDokumen::class, 'folder_id');
    }

    public function getUkuranAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }

    public function getDapatDipreviewAttribute(): bool
    {
        return in_array(strtolower($this->file_type), ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
    }
}
