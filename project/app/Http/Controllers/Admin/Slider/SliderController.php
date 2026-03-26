<?php

namespace App\Http\Controllers\Admin\Slider;

use App\Http\Controllers\Controller;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = SliderImage::orderBy('order')->get();
        
        return view('admin.sliders.index', compact('sliders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // 2MB
        ]);

        $path = $request->file('image')->store('sliders', 'public');
        SliderImage::create([
            'image_path' => $path,
            'order' => (SliderImage::max('order') ?: 0) + 1,
            'active' => true,
        ]);
        return redirect()->route('admin.sliders.index')->with('success', 'Image added to DB.');

    }

    public function destroy(SliderImage $sliderImage)
    {
        Storage::disk('public')->delete($sliderImage->image_path);
        $sliderImage->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Image deleted.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($request->order as $index => $id) {
            SliderImage::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}

