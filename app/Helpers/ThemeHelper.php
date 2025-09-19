<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class ThemeHelper
{
    /**
     * Obtém o tema principal ativo
     */
    public static function getActiveTheme()
    {
        $configPath = config_path('tema_principal.php');
        
        if (File::exists($configPath)) {
            $config = include $configPath;
            $temaAtivo = $config['tema_principal'] ?? null;
            
            // Verificar se o tema ainda existe
            if ($temaAtivo && self::themeExists($temaAtivo)) {
                return $temaAtivo;
            }
        }
        
        // Fallback para main-Thema se nenhum tema estiver selecionado ou se o tema não existir
        return 'main-Thema';
    }
    
    /**
     * Obtém o caminho da view do tema ativo
     */
    public static function getThemeViewPath($viewName)
    {
        $temaAtivo = self::getActiveTheme();
        
        // Se for main-Thema, usar o caminho original
        if ($temaAtivo === 'main-Thema') {
            return "main-Thema.{$viewName}";
        }
        
        $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
        
        // Primeiro, tentar com o nome original da página
        $arquivoBlade = $temaViewsPath . '/' . $viewName . '.blade.php';
        
        // Se não existir, tentar com o mapeamento
        if (!File::exists($arquivoBlade)) {
            $mapeamento = [
                'home' => 'index',
                'sobre' => 'about',
                'contato' => 'contact'
            ];
            $arquivoReal = $mapeamento[$viewName] ?? $viewName;
        } else {
            $arquivoReal = $viewName;
        }
        
        // Usar o caminho temas.nomeTema.arquivoReal
        return "temas.{$temaAtivo}.{$arquivoReal}";
    }
    
    /**
     * Verifica se um tema existe
     */
    public static function themeExists($nomeTema)
    {
        // Tratar main-Thema de forma especial
        if ($nomeTema === 'main-Thema') {
            $temaViewsPath = resource_path('views/main-Thema');
            return File::exists($temaViewsPath);
        }
        
        // Para outros temas, verificar em public/temas e views/temas
        $temaPath = public_path('temas/' . $nomeTema);
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        return File::exists($temaPath) && File::exists($temaViewsPath);
    }
    
    /**
     * Obtém o caminho do layout do tema ativo
     */
    public static function getActiveThemeLayout()
    {
        $temaAtivo = self::getActiveTheme();
        return "temas.{$temaAtivo}.layouts.app";
    }
    
    /**
     * Obtém o caminho dos includes do tema ativo
     */
    public static function getActiveThemeIncludes()
    {
        $temaAtivo = self::getActiveTheme();
        return "temas.{$temaAtivo}.inc";
    }
    
    /**
     * Obtém o caminho dos assets do tema ativo
     */
    public static function getActiveThemeAssets($path = '')
    {
        $temaAtivo = self::getActiveTheme();
        return asset("temas/{$temaAtivo}/assets/{$path}");
    }
}
