<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'nama_admin',
        'email',
        'role',
        'divisi',
    ];

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdminDivisi(): bool { return $this->role === 'admin_divisi'; }
    public function isAnggota(): bool { return $this->role === 'anggota'; }

    public function uploads() { return $this->hasMany(UploadAnggota::class); }
    public function folderDibuat() { return $this->hasMany(FolderDokumen::class, 'created_by'); }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}