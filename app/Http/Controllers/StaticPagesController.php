<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller
{
    /**
     * Show the home page.
     */
    public function home()
    {
        if (Auth::check()) {
            $microposts = Auth::user()->feed();
            $micropost = new \App\Models\Micropost();

            return view('static_pages.home', [
                'microposts' => $microposts,
                'micropost' => $micropost,
            ]);
        }

        return view('static_pages.home');
    }

    /**
     * Show the help page.
     */
    public function help()
    {
        return view('static_pages.help');
    }

    /**
     * Show the about page.
     */
    public function about()
    {
        return view('static_pages.about');
    }

    /**
     * Show the contact page.
     */
    public function contact()
    {
        return view('static_pages.contact');
    }
}
