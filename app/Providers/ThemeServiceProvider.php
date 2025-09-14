<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\ThemeHelper;

class ThemeServiceProvider extends ServiceProvider
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
        // Compartilhar o tema ativo com todas as views
        view()->composer('*', function ($view) {
            $view->with('temaAtivo', ThemeHelper::getActiveTheme());
        });
    }
}
