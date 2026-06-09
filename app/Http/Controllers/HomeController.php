<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $viewPath = \App\Helpers\ThemeHelper::getThemeViewPath('home');
        
        // Buscar blogs para exibir na home (se necessário)
        $blogs = DB::table('posts')
            ->where('ativo', 1)
            ->orderBy('created_at', 'desc')
            ->limit(6) // Limitar a 6 posts na home
            ->get()
            ->map(function($post) {
                return [
                    'title' => $post->titulo,
                    'slug' => $post->slug ?? Str::slug($post->titulo),
                    'image' => $post->imagem_apresentacao ? asset('storage/posts/' . $post->imagem_apresentacao) : asset('temas/prestacon/assets/images/default-blog.jpg'),
                    'excerpt' => Str::limit(strip_tags($post->conteudo), 150),
                    'author' => $post->autor ?? 'Admin',
                    'date' => \Carbon\Carbon::parse($post->created_at)->format('d/m/Y'),
                    'created_at' => $post->created_at,
                ];
            })
            ->toArray();
        
        return view($viewPath, ['blogs' => $blogs]);
    }
}
