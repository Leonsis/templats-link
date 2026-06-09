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
            // Verificar conexão com banco antes de tentar acessar
            if (!app()->runningInConsole()) {
                try {
                    \DB::connection()->getPdo();
                } catch (\Exception $e) {
                    \Log::warning("Não foi possível conectar ao banco de dados: " . $e->getMessage());
                    return;
                }
            }
            
            // Verificar se a tabela rotas_dinamicas existe
            if (!\Schema::hasTable('rotas_dinamicas')) {
                \Log::info("Tabela rotas_dinamicas não existe, pulando registro de rotas dinâmicas");
                return;
            }
            
            // Rotas principais que não devem ser sobrescritas
            $rotasPrincipais = ['/', '/sobre', '/contato', '/login', '/dashboard', '/blog'];
            
            // Obter tema ativo
            $temaAtivo = null;
            try {
                $temaAtivo = \DB::table('temas')
                    ->where('ativo', 1)
                    ->value('nome');
            } catch (\Exception $e) {
                \Log::warning("Erro ao obter tema ativo: " . $e->getMessage());
            }
            
            // Carregar rotas dinâmicas do banco de dados
            // Se houver tema ativo, carregar apenas as rotas desse tema
            // Caso contrário, carregar todas as rotas ativas
            $query = \DB::table('rotas_dinamicas')
                ->where('ativo', 1);
            
            if ($temaAtivo) {
                $query->where('tema', $temaAtivo);
            }
            
            $rotasDinamicas = $query
                ->limit(500) // Limitar número de rotas para evitar sobrecarga
                ->get();
            
            \Log::info("Carregando " . $rotasDinamicas->count() . " rotas dinâmicas");
            
            // Ordenar rotas: primeiro rotas estáticas (sem parâmetros), depois rotas com parâmetros
            $rotasEstaticas = $rotasDinamicas->filter(function($rota) {
                return !str_contains($rota->rota, '{');
            });
            
            $rotasComParametros = $rotasDinamicas->filter(function($rota) {
                return str_contains($rota->rota, '{');
            });
            
            // Primeiro registrar rotas estáticas (têm prioridade)
            $rotasParaRegistrar = $rotasEstaticas->merge($rotasComParametros);
            
            foreach ($rotasParaRegistrar as $rotaDinamica) {
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
                
                // Verificar se já existe uma rota estática com essa URI
                try {
                    $rotasExistentes = Route::getRoutes();
                    foreach ($rotasExistentes as $rota) {
                        $uri = $rota->uri();
                        // Comparar URI exata (sem parâmetros)
                        if ($uri === $rotaCompleta || $uri === ltrim($rotaCompleta, '/')) {
                            \Log::info("Pulando rota dinâmica que conflita com rota estática existente: {$rotaCompleta} (rota estática: {$uri})");
                            continue 2; // Continue o loop externo
                        }
                    }
                } catch (\Exception $e) {
                    // Se houver erro ao verificar rotas, continuar normalmente
                }
                
                // Verificar se a rota dinâmica com {slug} conflita com rota estática
                // Ex: /blog/{slug} não deve ser registrada se já existe /blog
                if (str_contains($rotaCompleta, '{slug}')) {
                    $rotaBase = str_replace('/{slug}', '', $rotaCompleta);
                    
                    // Verificar se a rota base está na lista de rotas principais
                    if (in_array($rotaBase, $rotasPrincipais)) {
                        \Log::info("Pulando rota dinâmica com slug que conflita com rota estática: {$rotaCompleta}");
                        continue;
                    }
                    
                    // Verificar se já existe uma rota estática com esse nome
                    // IMPORTANTE: Para detail-blogs, permitir que a rota dinâmica coexista com a rota de query string
                    // A rota de query string (/detail-blogs?slug=) não deve bloquear a rota dinâmica (/detail-blogs/{slug})
                    try {
                        $rotasExistentes = Route::getRoutes();
                        $devePular = false;
                        
                        foreach ($rotasExistentes as $rota) {
                            $uri = $rota->uri();
                            
                            // Se for detail-blogs ou detail_blogs, permitir coexistência (rota de query string não bloqueia rota dinâmica)
                            if (($rotaBase === '/detail-blogs' || $rotaBase === '/detail_blogs') && ($uri === 'detail-blogs' || $uri === 'detail_blogs')) {
                                // Não bloquear - permitir que ambas coexistam
                                continue;
                            }
                            
                            // Para outras rotas, verificar conflito
                            if ($uri === $rotaBase || $uri === ltrim($rotaBase, '/')) {
                                \Log::info("Pulando rota dinâmica com slug que conflita com rota estática existente: {$rotaCompleta}");
                                $devePular = true;
                                break;
                            }
                        }
                        
                        if ($devePular) {
                            continue; // Continue o loop externo (foreach das rotas dinâmicas)
                        }
                    } catch (\Exception $e) {
                        // Se houver erro ao verificar rotas, continuar normalmente
                    }
                }
                
                // Criar nome único para a rota para evitar conflitos
                // Normalizar o nome do tema e nome_rota (substituir hífens por camelCase)
                $nomeRotaUnico = theme_route_name($rotaDinamica->tema, $rotaDinamica->nome_rota);
                
                // Verificar se a rota já existe pelo nome (não pelo caminho, pois múltiplos temas podem ter o mesmo caminho)
                try {
                    $rotaExistente = Route::getRoutes()->getByName($nomeRotaUnico);
                    if ($rotaExistente) {
                        \Log::info("Rota {$nomeRotaUnico} já existe, pulando registro");
                        continue;
                    }
                } catch (\Illuminate\Routing\Exceptions\UrlGenerationException $e) {
                    // Rota não existe, pode registrar
                } catch (\Exception $e) {
                    // Outro tipo de erro, tentar registrar mesmo assim
                }
                
                // Registrar rota dinâmica
                if (str_contains($rotaCompleta, '{slug}')) {
                    // Para rotas com parâmetro slug (como detail_blogs)
                    Route::get($rotaCompleta, function($slug) use ($rotaDinamica) {
                        $controller = new \App\Http\Controllers\TemasController();
                        return $controller->renderizarPaginaDinamica($rotaDinamica->tema, $rotaDinamica->pagina, $slug);
                    })->name($nomeRotaUnico);
                } else {
                    // Para rotas sem parâmetros
                    Route::get($rotaCompleta, function() use ($rotaDinamica) {
                        $controller = new \App\Http\Controllers\TemasController();
                        return $controller->renderizarPaginaDinamica($rotaDinamica->tema, $rotaDinamica->pagina);
                    })->name($nomeRotaUnico);
                }
                
                \Log::info("Rota dinâmica registrada: {$rotaCompleta} → {$nomeRotaUnico}");
            }
            
            \Log::info("Rotas dinâmicas registradas com sucesso (conflitos evitados)");
            
        } catch (\Exception $e) {
            // Em caso de erro (ex: tabela não existe), não registrar rotas
            \Log::warning("Erro ao carregar rotas dinâmicas: " . $e->getMessage());
        }
    }
}
