<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();

            $role = $user->role;
            if ($role === 'anggota') {
                return redirect()->route('anggota.dashboard')->with('success', 'Login berhasil!');
            }

            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function resetPassword()
    {
        $user = User::where('username', 'admin')->first();
        if ($user) {
            $user->password = Hash::make('password');
            $user->save();

            return view('auth.reset-password')->with('success', 'Password berhasil direset!');
        }

        return view('auth.reset-password')->with('error', 'User admin tidak ditemukan!');
    }
}
