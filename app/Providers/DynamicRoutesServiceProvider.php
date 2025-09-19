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
        // Registrar rotas dinâmicas
        $this->registrarRotasDinamicas();
    }

    /**
     * Registrar rotas dinâmicas dos temas
     */
    private function registrarRotasDinamicas()
    {
        try {
            // Verificar se a tabela rotas_dinamicas existe
            if (!\Schema::hasTable('rotas_dinamicas')) {
                \Log::info("Tabela rotas_dinamicas não existe, pulando registro de rotas dinâmicas");
                return;
            }
            
            // Rotas principais que não devem ser sobrescritas
            $rotasPrincipais = ['/', '/sobre', '/contato', '/login'];
            
            // Carregar rotas dinâmicas do banco de dados
            $rotasDinamicas = \DB::table('rotas_dinamicas')
                ->where('ativo', 1)
                ->get();
            
            \Log::info("Carregando " . $rotasDinamicas->count() . " rotas dinâmicas");
            
            foreach ($rotasDinamicas as $rotaDinamica) {
                $rotaCompleta = $rotaDinamica->rota;
                
                // Se a rota não começar com /, adicionar
                if (!str_starts_with($rotaCompleta, '/')) {
                    $rotaCompleta = '/' . $rotaCompleta;
                }
                
                // Verificar se a rota conflita com rotas principais
                if (in_array($rotaCompleta, $rotasPrincipais)) {
                    \Log::info("Pulando rota dinâmica que conflita com rota principal: {$rotaCompleta}");
                    continue;
                }
                
                // Criar nome único para a rota para evitar conflitos
                $nomeRotaUnico = "tema.{$rotaDinamica->tema}.{$rotaDinamica->nome_rota}";
                
                // Registrar rota dinâmica
                Route::get($rotaCompleta, [\App\Http\Controllers\TemasController::class, 'renderizarPaginaDinamica'])
                    ->defaults('tema', $rotaDinamica->tema)
                    ->defaults('pagina', $rotaDinamica->pagina)
                    ->name($nomeRotaUnico);
                
                \Log::info("Rota dinâmica registrada: {$rotaCompleta} → {$nomeRotaUnico}");
            }
            
            \Log::info("Rotas dinâmicas registradas com sucesso (conflitos evitados)");
            
        } catch (\Exception $e) {
            // Em caso de erro (ex: tabela não existe), não registrar rotas
            \Log::warning("Erro ao carregar rotas dinâmicas: " . $e->getMessage());
        }
    }
}
