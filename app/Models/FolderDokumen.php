<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolderDokumen extends Model
{
    protected $table = 'folder_dokumen';
    protected $fillable = ['nama', 'deskripsi', 'divisi', 'status', 'created_by'];

    public function pembuat() { return $this->belongsTo(User::class, 'created_by'); }
    public function uploads() { return $this->hasMany(UploadAnggota::class, 'folder_id'); }
}
