<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManajemenUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = User::where('role', '!=', 'super_admin');

        if ($user->isAdminDivisi()) {
            $query->where('divisi', $user->divisi)->where('role', 'anggota');
        }

        $users = $query->orderBy('divisi')->orderBy('nama_admin')->get();
        $total_baru = \App\Models\SuratMasuk::where('status', 'baru')->count();
        $divisi_list = ['Kepegawaian', 'Program', 'Keuangan', 'Ekraf', 'Destinasi', 'Pemasaran', 'Sdm'];

        return view('admin.manajemen-user', compact('users', 'total_baru', 'divisi_list'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username'   => 'required|string|unique:users,username',
            'nama_admin' => 'required|string',
            'password'   => 'required|string|min:6',
            'divisi'     => 'required',
        ]);

        $role = 'anggota';
        $divisi = $request->divisi;

        if ($user->isSuperAdmin() && $request->role === 'admin_divisi') {
            $role = 'admin_divisi';
        }

        User::create([
            'username'   => $request->username,
            'nama_admin' => $request->nama_admin,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $role,
            'divisi'     => $divisi,
        ]);

        return back()->with('success', 'Akun berhasil dibuat!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama_admin' => 'required|string',
            'username'   => 'required|string|unique:users,username,' . $user->id,
        ]);

        $data = [
            'nama_admin' => $request->nama_admin,
            'username'   => $request->username,
            'email'      => $request->email,
            'divisi'     => $request->divisi,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if (Auth::user()->isSuperAdmin() && $request->filled('role')) {
            $data['role'] = $request->role;
        }

        $user->update($data);
        return back()->with('success', 'Akun berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Tidak bisa hapus super admin!');
        }
        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus!');
    }
}
