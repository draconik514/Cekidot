<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolderDokumen extends Model
{
    protected $table = 'folder_dokumen';

<<<<<<< HEAD
    protected $fillable = ['nama', 'deskripsi', 'divisi', 'status', 'parent_id', 'created_by'];
=======
    protected $fillable = ['nama', 'deskripsi', 'divisi', 'bidang_id', 'status', 'created_by'];
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function uploads()
    {
        return $this->hasMany(UploadAnggota::class, 'folder_id');
    }

<<<<<<< HEAD
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
=======
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
    }
}
