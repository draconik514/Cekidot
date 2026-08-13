<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\SuratMasuk;
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

        $users = $query->with('bidang')->orderBy('divisi')->orderBy('nama_admin')->get();
        $total_baru = SuratMasuk::where('status', 'baru')->count();
        $divisi_list = ['Kepegawaian', 'Program', 'Keuangan', 'Ekraf', 'Destinasi', 'Pemasaran', 'Sdm'];
        $bidang_list = Bidang::orderBy('nama_bidang')->get();

        return view('admin.manajemen-user', compact('users', 'total_baru', 'divisi_list', 'bidang_list'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|unique:users,username',
            'nama_admin' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($request->input('role') === 'admin_bidang') {
            $request->validate(['bidang_id' => 'required|exists:bidang,id']);
        } else {
            $request->validate(['divisi' => 'required']);
        }

        $role = 'anggota';
        $divisi = $request->divisi;
        $bidangId = null;

        if ($user->isSuperAdmin()) {
            if ($request->role === 'admin_divisi') {
                $role = 'admin_divisi';
            } elseif ($request->role === 'admin_bidang') {
                $role = 'admin_bidang';
                $bidangId = $request->bidang_id;
            }
        }

        User::create([
            'username' => $request->username,
            'nama_admin' => $request->nama_admin,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'divisi' => $role === 'admin_bidang' ? null : $divisi,
            'bidang_id' => $bidangId,
            'is_active' => true,
        ]);

        return back()->with('success', 'Akun berhasil dibuat!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama_admin' => 'required|string',
            'username' => 'required|string|unique:users,username,'.$user->id,
        ]);

        $data = [
            'nama_admin' => $request->nama_admin,
            'username' => $request->username,
            'email' => $request->email,
            'divisi' => $request->divisi,
        ];

        if (Auth::user()->isSuperAdmin()) {
            if ($request->input('role') === 'admin_bidang') {
                $data['role'] = 'admin_bidang';
                $data['bidang_id'] = $request->bidang_id;
                $data['divisi'] = null;
            } elseif ($request->filled('role')) {
                $data['role'] = $request->role;
            }
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Akun berhasil diupdate!');
    }

    public function toggleActive(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Tidak bisa nonaktifkan super admin!');
        }
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Status akun berhasil diubah!');
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
