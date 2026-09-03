<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('public.home');
    }

    public function apropos()
    {
        return view('public.apropos');
    }

    public function contact()
    {
        return view('public.contact');
    }
}