<?php

namespace App\Http\Controllers\Admin\Anggota;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KelolaAnggotaController extends Controller
{
    protected function scopeToOwnBidang(): void
    {
        $user = Auth::user();

        if (! $user->isAdminBidang()) {
            abort(403, 'Halaman ini hanya untuk admin bidang.');
        }

        if (! $user->bidang_id) {
            abort(403, 'Bidang akun belum diatur. Hubungi Super Admin.');
        }
    }

    public function index()
    {
        $this->scopeToOwnBidang();
        $user = Auth::user();

        $anggota = User::with('bidang')
            ->where('bidang_id', $user->bidang_id)
            ->where('role', 'anggota')
            ->orderBy('nama_admin')
            ->get();

        return view('admin.kelola-anggota', compact('anggota'));
    }

    public function store(Request $request)
    {
        $this->scopeToOwnBidang();
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|unique:users,username',
            'nama_admin' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'username' => $request->username,
            'nama_admin' => $request->nama_admin,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
            'divisi' => $request->divisi,
            'bidang_id' => $user->bidang_id,
            'is_active' => true,
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $this->scopeToOwnBidang();
        $auth = Auth::user();

        if ($user->bidang_id !== $auth->bidang_id || $user->role !== 'anggota') {
            abort(403, 'Anda hanya dapat mengelola anggota bidang Anda.');
        }

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

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Anggota berhasil diupdate!');
    }

    public function toggleActive(User $user)
    {
        $this->scopeToOwnBidang();
        $auth = Auth::user();

        if ($user->bidang_id !== $auth->bidang_id || $user->role !== 'anggota') {
            abort(403, 'Anda hanya dapat mengelola anggota bidang Anda.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Status anggota berhasil diubah!');
    }

    public function destroy(User $user)
    {
        $this->scopeToOwnBidang();
        $auth = Auth::user();

        if ($user->bidang_id !== $auth->bidang_id || $user->role !== 'anggota') {
            abort(403, 'Anda hanya dapat mengelola anggota bidang Anda.');
        }

        $user->delete();

        return back()->with('success', 'Anggota berhasil dihapus!');
    }
}
