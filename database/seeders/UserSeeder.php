<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'nama_admin' => 'Administrator',
            'email' => 'admin@cekidot.go.id',
        ]);
    }
}