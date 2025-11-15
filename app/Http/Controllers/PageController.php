<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function helpCenter()
    {
        return view('pages.help-center');
    }

    public function warranties()
    {
        return view('pages.warranties');
    }

    public function returns()
    {
        return view('pages.returns');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function blog()
    {
        return view('pages.blog');
    }

    public function development()
    {
        return view('pages.development');
    }
}

