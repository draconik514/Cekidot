<?php

namespace App\Http\Controllers;

use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slider::where('status', 'aktif')
            ->orderBy('urutan')
            ->limit(6)
            ->get();

        return view('public.home', compact('slides'));
    }
}