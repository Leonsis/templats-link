<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class HeadHelper
{
    private static $configsCache = [];
    
    public static function getConfigs($pagina = 'global', $tema = null)
    {
        // Se não especificado, usar o tema ativo
        if (!$tema) {
            $tema = \App\Helpers\ThemeHelper::getActiveTheme();
        }
        
        $cacheKey = "{$tema}_{$pagina}";
        
        if (!isset(self::$configsCache[$cacheKey])) {
            try {
                // Primeiro, tentar buscar configurações específicas do tema
                $configs = DB::table('head_configs')
                    ->where('pagina', $pagina)
                    ->where('tema', $tema)
                    ->first();
                
                // Se não encontrar configurações específicas do tema, buscar configurações globais
                if (!$configs) {
                    $configs = DB::table('head_configs')
                        ->where('pagina', $pagina)
                        ->where('tema', 'global')
                        ->first();
                }
                
                // Se ainda não encontrar, usar configurações padrão
                if (!$configs) {
                    $configs = (object) [
                        'pagina' => $pagina,
                        'tema' => $tema,
                        'meta_title' => 'Templats Link - Templates e Desenvolvimento Web',
                        'meta_description' => 'Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais.',
                        'meta_keywords' => 'templates, desenvolvimento web, sites, laravel, php',
                        'gtm_head' => '',
                        'gtm_body' => '',
                    ];
                }
                
                self::$configsCache[$cacheKey] = $configs;
                
            } catch (\Exception $e) {
                // Fallback para configurações padrão em caso de erro
                self::$configsCache[$cacheKey] = (object) [
                    'pagina' => $pagina,
                    'tema' => $tema,
                    'meta_title' => 'Templats Link - Templates e Desenvolvimento Web',
                    'meta_description' => 'Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais.',
                    'meta_keywords' => 'templates, desenvolvimento web, sites, laravel, php',
                    'gtm_head' => '',
                    'gtm_body' => '',
                ];
            }
        }
        
        return self::$configsCache[$cacheKey];
    }
    
    public static function getAllConfigs($tema = null)
    {
        try {
            if (!$tema) {
                $tema = \App\Helpers\ThemeHelper::getActiveTheme();
            }
            
            return DB::table('head_configs')
                ->where('tema', $tema)
                ->orWhere('tema', 'global')
                ->orderBy('pagina')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
    
    public static function getMetaTitle($pagina = 'global', $fallback = null)
    {
        $configs = self::getConfigs($pagina);
        return $configs->meta_title ?: $fallback ?: 'Templats Link';
    }
    
    public static function getMetaDescription($pagina = 'global')
    {
        $configs = self::getConfigs($pagina);
        return $configs->meta_description ?: 'Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais.';
    }
    
    public static function getMetaKeywords($pagina = 'global')
    {
        $configs = self::getConfigs($pagina);
        return $configs->meta_keywords ?: 'templates, desenvolvimento web, sites, laravel, php';
    }
    
    public static function getGtmHead($pagina = 'global')
    {
        // Sempre usar configurações globais para GTM
        $configs = self::getConfigs('global');
        return $configs->gtm_head ?: '';
    }
    
    public static function getGtmBody($pagina = 'global')
    {
        // Sempre usar configurações globais para GTM
        $configs = self::getConfigs('global');
        return $configs->gtm_body ?: '';
    }
    
    public static function getFavicon($pagina = 'global')
    {
        // Sempre usar configurações globais para favicon
        $configs = self::getConfigs('global');
        if ($configs->favicon) {
            // Extrair apenas o nome do arquivo
            $filename = basename($configs->favicon);
            return route('favicon', ['filename' => $filename]);
        }
        return '';
    }
    
    public static function getNavbarConfigs($tema = null)
    {
        return self::getConfigs('global', $tema);
    }
    
    public static function getLogo()
    {
        $configs = self::getConfigs('global');
        if ($configs->logo) {
            $filename = basename($configs->logo);
            
            // Verificar se é um logo ou favicon baseado no caminho
            if (strpos($configs->logo, 'uploads/logos/') !== false) {
                return route('logo', ['filename' => $filename]);
            } elseif (strpos($configs->logo, 'uploads/favicons/') !== false) {
                return route('favicon', ['filename' => $filename]);
            } else {
                // Fallback para logo
                return route('logo', ['filename' => $filename]);
            }
        }
        return '';
    }
    
    public static function getLogoFooter()
    {
        $configs = self::getConfigs('global');
        if ($configs->logo_footer) {
            $filename = basename($configs->logo_footer);
            
            // Verificar se é um logo ou favicon baseado no caminho
            if (strpos($configs->logo_footer, 'uploads/logos/') !== false) {
                return route('logo', ['filename' => $filename]);
            } elseif (strpos($configs->logo_footer, 'uploads/favicons/') !== false) {
                return route('favicon', ['filename' => $filename]);
            } else {
                // Fallback para logo
                return route('logo', ['filename' => $filename]);
            }
        }
        return '';
    }
    
    public static function getEmailContato()
    {
        $configs = self::getConfigs('global');
        return $configs->email_contato ?: '';
    }
    
    public static function getTelefone()
    {
        $configs = self::getConfigs('global');
        return $configs->telefone ?: '';
    }
    
    public static function getWhatsapp()
    {
        $configs = self::getConfigs('global');
        return $configs->whatsapp ?: '';
    }
    
    public static function getEndereco()
    {
        $configs = self::getConfigs('global');
        return $configs->endereco ?: '';
    }
    
    public static function getHorarioAtendimento()
    {
        $configs = self::getConfigs('global');
        return $configs->horario_atendimento ?: '';
    }
    
    public static function getRedesSociais()
    {
        $configs = self::getConfigs('global');
        return [
            'facebook' => $configs->facebook ?: '',
            'instagram' => $configs->instagram ?: '',
            'twitter' => $configs->twitter ?: '',
            'linkedin' => $configs->linkedin ?: '',
            'youtube' => $configs->youtube ?: '',
        ];
    }
    
    public static function getFacebook()
    {
        $configs = self::getConfigs('global');
        return $configs->facebook ?: '';
    }
    
    public static function getTwitter()
    {
        $configs = self::getConfigs('global');
        return $configs->twitter ?: '';
    }
    
    public static function getInstagram()
    {
        $configs = self::getConfigs('global');
        return $configs->instagram ?: '';
    }
    
    public static function getLinkedin()
    {
        $configs = self::getConfigs('global');
        return $configs->linkedin ?: '';
    }
    
    public static function getYoutube()
    {
        $configs = self::getConfigs('global');
        return $configs->youtube ?: '';
    }
    
    public static function getDescricaoFooter()
    {
        $configs = self::getConfigs('global');
        return $configs->descricao_footer ?: '';
    }
    
    public static function getCopyrightFooter()
    {
        $configs = self::getConfigs('global');
        $copyright = $configs->copyright_footer ?: '© {ano} Templats-link. Todos os direitos reservados.';
        
        // Substituir {ano} pelo ano atual
        return str_replace('{ano}', date('Y'), $copyright);
    }
    
    public static function clearCache($pagina = null, $tema = null)
    {
        if ($pagina && $tema) {
            $cacheKey = "{$tema}_{$pagina}";
            unset(self::$configsCache[$cacheKey]);
        } elseif ($pagina) {
            // Limpar cache para todas as páginas que começam com o nome da página
            foreach (self::$configsCache as $key => $value) {
                if (strpos($key, "_{$pagina}") !== false) {
                    unset(self::$configsCache[$key]);
                }
            }
        } else {
            self::$configsCache = [];
        }
    }
}
