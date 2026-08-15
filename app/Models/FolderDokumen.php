<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolderDokumen extends Model
{
    protected $table = 'folder_dokumen';

    protected $fillable = ['nama', 'deskripsi', 'divisi', 'status', 'parent_id', 'created_by'];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function uploads()
    {
        return $this->hasMany(UploadAnggota::class, 'folder_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
