<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (!in_array(Auth::user()->role, ['super_admin', 'admin_divisi'])) {
            return redirect()->route('anggota.dashboard')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}