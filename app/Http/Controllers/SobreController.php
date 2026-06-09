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
        $viewPath = \App\Helpers\ThemeHelper::getThemeViewPath('sobre');
        return view($viewPath);
    }
}
