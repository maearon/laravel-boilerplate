<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StaticPageService;

class StaticPagesController extends Controller
{
    public function __construct(
        private readonly StaticPageService $staticPageService,
    ) {}

    /**
     * Show the home page.
     */
    public function home()
    {
        $data = $this->staticPageService->homeDataFor(auth()->user());
        if (!empty($data)) {
            return view('static_pages.home', $data);
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
