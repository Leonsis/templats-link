<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class FloatingButtonHelper
{
    private static $configsCache = [];
    
    /**
     * Obter configurações dos botões flutuantes
     */
    public static function getConfigs($tema = null)
    {
        // Se não especificado, usar o tema ativo
        if (!$tema) {
            $tema = \App\Helpers\ThemeHelper::getActiveTheme();
        }
        
        $cacheKey = "floating_buttons_{$tema}";
        
        if (!isset(self::$configsCache[$cacheKey])) {
            try {
                // Buscar configurações específicas do tema
                $configs = DB::table('head_configs')
                    ->where('pagina', 'global')
                    ->where('tema', $tema)
                    ->first();
                
                // Se não encontrar configurações específicas do tema, buscar configurações globais
                // Mas apenas se o tema ativo for main-Thema
                if (!$configs && $tema === 'main-Thema') {
                    $configs = DB::table('head_configs')
                        ->where('pagina', 'global')
                        ->where('tema', 'global')
                        ->first();
                }
                
                // Se ainda não encontrar, usar configurações padrão
                if (!$configs) {
                    $configs = (object) [
                        'telefone' => '',
                        'whatsapp' => '',
                        'email_formulario' => ''
                    ];
                }
                
                self::$configsCache[$cacheKey] = $configs;
                
            } catch (\Exception $e) {
                \Log::error('Erro ao buscar configurações dos botões flutuantes: ' . $e->getMessage());
                
                // Retornar configurações padrão em caso de erro
                self::$configsCache[$cacheKey] = (object) [
                    'telefone' => '',
                    'whatsapp' => '',
                    'email_formulario' => ''
                ];
            }
        }
        
        return self::$configsCache[$cacheKey];
    }
    
    /**
     * Obter número do telefone
     */
    public static function getTelefone($tema = null)
    {
        $configs = self::getConfigs($tema);
        return $configs->telefone ?? '';
    }
    
    /**
     * Obter número do WhatsApp
     */
    public static function getWhatsapp($tema = null)
    {
        $configs = self::getConfigs($tema);
        return $configs->whatsapp ?? '';
    }
    
    /**
     * Obter email para formulário
     */
    public static function getEmailFormulario($tema = null)
    {
        $configs = self::getConfigs($tema);
        return $configs->email_formulario ?? '';
    }
    
    /**
     * Verificar se os botões flutuantes estão habilitados
     */
    public static function isEnabled($tema = null)
    {
        $telefone = self::getTelefone($tema);
        $whatsapp = self::getWhatsapp($tema);
        
        return !empty($telefone) || !empty($whatsapp);
    }
    
    /**
     * Limpar cache das configurações
     */
    public static function clearCache($tema = null)
    {
        if ($tema) {
            unset(self::$configsCache["floating_buttons_{$tema}"]);
        } else {
            self::$configsCache = [];
        }
    }
}
