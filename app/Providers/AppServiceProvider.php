<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Normalizar domínio: adicionar www (versão canônica com www)
        // Exceto em localhost/desenvolvimento
        if (request()->getHost() !== 'localhost' && 
            request()->getHost() !== '127.0.0.1' && 
            request()->getHost() !== '::1' &&
            !str_contains(request()->getHost(), 'localhost')) {
            
            // Forçar HTTPS em produção
            \Illuminate\Support\Facades\URL::forceScheme('https');
            
            // Normalizar domínio adicionando www nas URLs geradas
            $host = request()->getHost();
            if (!str_starts_with($host, 'www.')) {
                // Configurar URL base com www para todas as URLs geradas
                config(['app.url' => 'https://www.' . $host]);
            }
        }
    }
}
