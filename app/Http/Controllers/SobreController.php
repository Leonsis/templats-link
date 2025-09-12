<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SobreController extends Controller
{
    /**
     * Display the about page.
     */
    public function index()
    {
        return view('sobre');
    }
}
