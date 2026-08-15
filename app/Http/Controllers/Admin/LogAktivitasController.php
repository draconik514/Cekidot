<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        $query = LogAktivitas::with(['user', 'upload.folder']);

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }
        if ($request->filled('q')) {
            $query->where('detail', 'like', '%'.$request->q.'%');
        }

        $logs = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $total_baru = SuratMasuk::where('status', 'baru')->count();

        return view('admin.log-aktivitas', compact('logs', 'total_baru'));
    }
}
