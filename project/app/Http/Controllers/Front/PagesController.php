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
        return view('front.pages.cataloage');
    }
}

