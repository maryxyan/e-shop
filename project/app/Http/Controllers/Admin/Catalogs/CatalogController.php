<?php

namespace App\Http\Controllers\Admin\Catalogs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CatalogController extends Controller
{
    public function index()
    {
        $catalogDir = public_path('assets/catalogs');
        $catalogs = [];
        if (File::exists($catalogDir)) {
            $files = File::files($catalogDir);
            $catalogs = array_map(function ($file) {
                return [
                    'name' => $file->getFilename(),
                    'path' => str_replace(public_path(), '', $file->getRealPath()),
                    'size' => $file->getSize(),
                    'url' => asset('assets/catalogs/' . $file->getFilename()),
                ];
            }, $files);
        }
        return view('admin.catalogs.index', compact('catalogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'name' => 'required|string|max:255'
        ]);

        $catalogDir = public_path('assets/catalogs');
        if (!File::exists($catalogDir)) {
            File::makeDirectory($catalogDir, 0755, true);
        }

        $file = $request->file('pdf');
        $extension = $file->getClientOriginalExtension();
        $display_name = $request->name;
        $name = preg_replace('/[^a-zA-Z0-9\\s]/', '', $request->name);
        $name = str_replace(' ', '_', $name);
        $filename = $name . '_' . time() . '.' . $extension;
        $file->move($catalogDir, $filename);

        return redirect()->route('admin.catalogs.index')
            ->with('success', 'PDF uploaded as "' . $filename . '"!');
    }

    public function destroy($filename)
    {
        $filePath = public_path('assets/catalogs/' . basename($filename));
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
        return redirect()->route('admin.catalogs.index')
            ->with('success', 'PDF deleted!');
    }
}
