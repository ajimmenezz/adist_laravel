<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = 'Inicio';
        $subtitle = 'Bienvenido a AdIST';
        $breadcrumb = [];
        return view('home', compact('title', 'subtitle', 'breadcrumb'));
    }
}
