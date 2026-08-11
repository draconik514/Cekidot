<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadAnggota extends Model
{
    protected $table = 'upload_anggota';
    protected $fillable = ['user_id', 'folder_id', 'judul', 'file_name', 'file_type', 'file_size', 'keterangan', 'tahun', 'bulan', 'tanggal_upload', 'status'];

    protected $casts = ['tanggal_upload' => 'date'];

    public function user() { return $this->belongsTo(User::class); }
    public function folder() { return $this->belongsTo(FolderDokumen::class, 'folder_id'); }
}
