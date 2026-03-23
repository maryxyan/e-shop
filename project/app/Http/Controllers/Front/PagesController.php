<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * Display services page
     */
    public function servicii()
    {
        return view('front.pages.servicii');
    }

/**
     * Display company page - redirects to external about page
     */
    public function companie()
    {
        return view('front.pages.companie');
    }

    /**
     * Display catalogs page
     */
    public function cataloage()
    {
        $catalogDir = public_path('../public/assets/catalogs');
        $catalogs = [];
        if (is_dir($catalogDir)) {
            $files = glob($catalogDir . '/*.pdf');
            $catalogs = array_map(function ($file) {
                $path = str_replace(public_path(), '', $file);
                $display_name = preg_replace('/_\\d{10}\\.(pdf)$/i', '', basename($file));
                return [
                    'name' => basename($file),
                    'display_name' => $display_name,
                    'path' => $path,
                    'size' => filesize($file),
                    'url' => asset('assets/catalogs/' . basename($file)),
                ];
            }, $files);
        }
        return view('front.pages.cataloage', compact('catalogs'));
    }
}

