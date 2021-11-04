<?php

namespace App\Http\Controllers;

use App\Letter;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $letters = Letter::all();
        return view('home', compact('letters'));
    }
}
