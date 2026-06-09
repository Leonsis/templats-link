<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TemaDinamicoController extends Controller
{
    /**
     * Renderizar página dinâmica do tema
     */
    public function renderizarPagina($nomeTema, $pagina)
    {
        try {
            // Verificar se o tema existe
            $temaViewsPath = resource_path('views/temas/' . $nomeTema);
            if (!File::exists($temaViewsPath)) {
                abort(404, 'Tema não encontrado');
            }

            // Verificar se a página existe
            $arquivoBlade = $temaViewsPath . '/' . $pagina . '.blade.php';
            if (!File::exists($arquivoBlade)) {
                abort(404, 'Página não encontrada');
            }

            // Renderizar a view
            return view("temas.{$nomeTema}.{$pagina}");

        } catch (\Exception $e) {
            abort(500, 'Erro ao renderizar página: ' . $e->getMessage());
        }
    }
}
