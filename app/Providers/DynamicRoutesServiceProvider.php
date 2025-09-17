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
        try {
            // Carregar rotas dinâmicas do banco de dados
            $rotasDinamicas = \DB::table('rotas_dinamicas')
                ->where('ativo', 1)
                ->get();
            
            // Rotas principais que não devem ser sobrescritas
            $rotasPrincipais = ['/', '/sobre', '/contato', '/login', '/dashboard'];
            
            foreach ($rotasDinamicas as $rotaDinamica) {
                // Verificar se a rota não conflita com rotas principais
                if (!in_array($rotaDinamica->rota, $rotasPrincipais)) {
                    // Registrar rota dinâmica
                    Route::get($rotaDinamica->rota, [\App\Http\Controllers\TemasController::class, 'renderizarPaginaDinamica'])
                        ->defaults('tema', $rotaDinamica->tema)
                        ->defaults('pagina', $rotaDinamica->pagina)
                        ->name("tema.{$rotaDinamica->tema}.{$rotaDinamica->nome_rota}");
                } else {
                    \Log::info("Rota dinâmica ignorada para evitar conflito: {$rotaDinamica->rota}");
                }
            }
        } catch (\Exception $e) {
            // Em caso de erro (ex: tabela não existe), não registrar rotas
            \Log::warning("Erro ao carregar rotas dinâmicas: " . $e->getMessage());
        }
    }
}
