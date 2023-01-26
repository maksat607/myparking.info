<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends AppController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = __('Dashboard');
        return view('home', compact('title'));
    }
}
