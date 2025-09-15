<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\TemaDinamicoController;

class DynamicRoutesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registrarRotasDinamicas();
    }

    /**
     * Registrar rotas dinâmicas dos temas
     */
    private function registrarRotasDinamicas()
    {
        $rotasPath = base_path('routes/temas_dinamicas.php');
        
        if (File::exists($rotasPath)) {
            $rotasDinamicas = include $rotasPath;
            
            foreach ($rotasDinamicas as $nomeTema => $rotas) {
                foreach ($rotas as $nomeRota => $config) {
                    $rota = $config['rota'];
                    $pagina = $config['pagina'];
                    
                    // Registrar rota dinâmica
                    Route::get($rota, [TemaDinamicoController::class, 'renderizarPagina'])
                        ->defaults('nomeTema', $nomeTema)
                        ->defaults('pagina', $pagina)
                        ->name("tema.{$nomeTema}.{$nomeRota}");
                }
            }
        }
    }
}
