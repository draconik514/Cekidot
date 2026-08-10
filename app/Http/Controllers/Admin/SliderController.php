<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        $slides = Slider::orderBy('urutan')->get();
        $total_slider = $slides->count();
        return view('admin.slider', compact('slides', 'total_slider'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('gambar');
        $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $file->move(public_path('assets/img/slider'), $file_name);

        $max_urutan = Slider::max('urutan') ?? 0;

        Slider::create([
            'gambar' => $file_name,
            'judul' => $request->judul,
            'urutan' => $max_urutan + 1,
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.slider.index')->with('success', 'Slide berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        
        $path = public_path('assets/img/slider/' . $slider->gambar);
        if (file_exists($path)) unlink($path);

        $slider->delete();
        return redirect()->route('admin.slider.index')->with('success', 'Slide berhasil dihapus!');
    }
}