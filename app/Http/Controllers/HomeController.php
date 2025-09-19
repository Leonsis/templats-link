<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $viewPath = \App\Helpers\ThemeHelper::getThemeViewPath('home');
        return view($viewPath);
    }
}
