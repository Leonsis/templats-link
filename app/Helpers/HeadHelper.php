<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class HeadHelper
{
    private static $configsCache = [];
    
    /**
     * Limpar todo o cache de configurações
     */
    public static function clearAllCache()
    {
        self::$configsCache = [];
    }
    
    public static function getConfigs($pagina = 'global', $tema = null)
    {
        // Se não especificado, usar o tema ativo
        if (!$tema) {
            $tema = \App\Helpers\ThemeHelper::getActiveTheme();
        }
        
        $cacheKey = "{$tema}_{$pagina}";
        
        if (!isset(self::$configsCache[$cacheKey])) {
            try {
                // Normalizar nome da página para busca (lowercase, substituir underscores por hífens)
                $paginaNormalizada = strtolower(str_replace('_', '-', $pagina));
                
                // Primeiro, tentar buscar configurações específicas do tema com nome exato
                $configs = DB::table('head_configs')
                    ->where('pagina', $pagina)
                    ->where('tema', $tema)
                    ->first();
                
                // Se não encontrar, tentar com nome normalizado
                if (!$configs) {
                    $configs = DB::table('head_configs')
                        ->where('pagina', $paginaNormalizada)
                        ->where('tema', $tema)
                        ->first();
                }
                
                // Se ainda não encontrar, tentar busca case-insensitive
                if (!$configs) {
                    $configs = DB::table('head_configs')
                        ->whereRaw('LOWER(pagina) = ?', [strtolower($pagina)])
                        ->where('tema', $tema)
                        ->first();
                }
                
                // Se não encontrar configurações específicas do tema, buscar configurações globais
                // Mas apenas se o tema ativo for main-Thema
                if (!$configs && $tema === 'main-Thema') {
                    $configs = DB::table('head_configs')
                        ->where('pagina', $pagina)
                        ->where('tema', 'global')
                        ->first();
                }
                
                // Se ainda não encontrar, usar configurações padrão
                if (!$configs) {
                    // Se for tema personalizado, usar valores específicos do tema
                    if ($tema !== 'main-Thema') {
                        $configs = (object) [
                            'pagina' => $pagina,
                            'tema' => $tema,
                            'meta_title' => ucfirst($pagina) . ' - ' . $tema,
                            'meta_description' => 'Página ' . ucfirst($pagina) . ' do tema ' . $tema . '. Configure as meta tags específicas desta página.',
                            'meta_keywords' => strtolower($pagina) . ', ' . strtolower($tema) . ', página',
                            'favicon' => '',
                            'logo' => '',
                            'logo_footer' => '',
                            'email_contato' => 'contato@templats-link.com',
                            'telefone' => '+55 (11) 99999-9999',
                            'whatsapp' => '+5511999999999',
                            'endereco' => 'Rua das Tecnologias, 123 - Centro, São Paulo - SP',
                            'horario_atendimento' => 'Segunda a Sexta: 8h às 18h',
                            'facebook' => '',
                            'instagram' => '',
                            'twitter' => '',
                            'linkedin' => '',
                            'youtube' => '',
                            'github' => '',
                            'descricao_footer' => 'Somos especialistas em desenvolvimento web.',
                            'copyright_footer' => '© {ano} Templats Link. Todos os direitos reservados.',
                            'gtm_head' => '',
                            'gtm_body' => '',
                            'created_at' => null,
                            'updated_at' => null,
                        ];
                    } else {
                        // Para main-Thema, usar valores padrão originais
                        $configs = (object) [
                            'pagina' => $pagina,
                            'tema' => $tema,
                            'meta_title' => 'Templats Link - Templates e Desenvolvimento Web',
                            'meta_description' => 'Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais.',
                            'meta_keywords' => 'templates, desenvolvimento web, sites, laravel, php',
                            'favicon' => '',
                            'logo' => '',
                            'logo_footer' => '',
                            'email_contato' => 'contato@templats-link.com',
                            'telefone' => '+55 (11) 99999-9999',
                            'whatsapp' => '+5511999999999',
                            'endereco' => 'Rua das Tecnologias, 123 - Centro, São Paulo - SP',
                            'horario_atendimento' => 'Segunda a Sexta: 8h às 18h',
                            'facebook' => '',
                            'instagram' => '',
                            'twitter' => '',
                            'linkedin' => '',
                            'youtube' => '',
                            'github' => '',
                            'descricao_footer' => 'Somos especialistas em desenvolvimento web.',
                            'copyright_footer' => '© {ano} Templats Link. Todos os direitos reservados.',
                            'gtm_head' => '',
                            'gtm_body' => '',
                            'created_at' => null,
                            'updated_at' => null,
                        ];
                    }
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
                    'favicon' => '',
                    'logo' => '',
                    'logo_footer' => '',
                    'email_contato' => 'contato@templats-link.com',
                    'telefone' => '+55 (11) 99999-9999',
                    'whatsapp' => '+5511999999999',
                    'endereco' => 'Rua das Tecnologias, 123 - Centro, São Paulo - SP',
                    'horario_atendimento' => 'Segunda a Sexta: 8h às 18h',
                    'facebook' => '',
                    'instagram' => '',
                    'twitter' => '',
                    'linkedin' => '',
                    'youtube' => '',
                    'github' => '',
                    'descricao_footer' => 'Somos especialistas em desenvolvimento web.',
                    'copyright_footer' => '© {ano} Templats Link. Todos os direitos reservados.',
                    'gtm_head' => '',
                    'gtm_body' => '',
                    'created_at' => null,
                    'updated_at' => null,
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
    
    public static function getMetaTitle($pagina = 'global', $tema = null, $fallback = null)
    {
        // Se não foi passado o tema, usar o tema ativo
        if (!$tema) {
            $tema = ThemeHelper::getActiveTheme();
        }
        
        $configs = self::getConfigs($pagina, $tema);
        
        // Se o valor existe e não está vazio, retornar
        if (!empty($configs->meta_title)) {
            return $configs->meta_title;
        }
        
        // Se o valor está vazio mas a configuração existe no banco, usar fallback
        // Se não existe no banco, usar valor padrão específico do tema
        if ($tema !== 'main-Thema') {
            return ucfirst(str_replace('-', ' ', $pagina)) . ' - ' . $tema;
        }
        
        return $fallback ?: 'Templats Link';
    }
    
    public static function getMetaDescription($pagina = 'global', $tema = null)
    {
        // Se não foi passado o tema, usar o tema ativo
        if (!$tema) {
            $tema = ThemeHelper::getActiveTheme();
        }
        
        $configs = self::getConfigs($pagina, $tema);
        
        // Se o valor existe e não está vazio, retornar
        if (!empty($configs->meta_description)) {
            return $configs->meta_description;
        }
        
        // Se o valor está vazio mas a configuração existe no banco, usar fallback
        // Se não existe no banco, usar valor padrão específico do tema
        if ($tema !== 'main-Thema') {
            return 'Página ' . ucfirst(str_replace('-', ' ', $pagina)) . ' do tema ' . $tema . '. Configure as meta tags específicas desta página.';
        }
        
        return 'Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais.';
    }
    
    public static function getMetaKeywords($pagina = 'global', $tema = null)
    {
        // Se não foi passado o tema, usar o tema ativo
        if (!$tema) {
            $tema = ThemeHelper::getActiveTheme();
        }
        
        $configs = self::getConfigs($pagina, $tema);
        
        // Se o valor existe e não está vazio, retornar
        if (!empty($configs->meta_keywords)) {
            return $configs->meta_keywords;
        }
        
        // Se o valor está vazio mas a configuração existe no banco, usar fallback
        // Se não existe no banco, usar valor padrão específico do tema
        if ($tema !== 'main-Thema') {
            return strtolower(str_replace('-', ' ', $pagina)) . ', ' . strtolower($tema) . ', página';
        }
        
        return 'templates, desenvolvimento web, sites, laravel, php';
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
            'github' => $configs->github ?: '',
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

    public static function getGithub()
    {
        $configs = self::getConfigs('global');
        return $configs->github ?: '';
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
    
    public static function getMetaRobots($pagina = 'global', $tema = null)
    {
        // Se não foi passado o tema, usar o tema ativo
        if (!$tema) {
            $tema = ThemeHelper::getActiveTheme();
        }
        
        $configs = self::getConfigs($pagina, $tema);
        
        return $configs->meta_robots ?? 'index, follow';
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
    
    /**
     * Gera a URL canônica correta para a página atual
     * Normaliza URLs duplicadas e remove query strings que não alteram o conteúdo
     * 
     * @param string|null $currentPage Nome da página atual (opcional)
     * @param string|null $tema Nome do tema (opcional)
     * @return string URL canônica
     */
    public static function getCanonicalUrl($currentPage = null, $tema = null)
    {
        $request = request();
        $path = $request->path();
        $host = $request->getHost();
        $scheme = $request->secure() ? 'https' : 'http';
        
        // Normalizar path: remover barras duplicadas e barras finais
        $path = trim($path, '/');
        $path = preg_replace('#/+#', '/', $path);
        
        // Mapeamento de páginas com conteúdo idêntico
        // Todas as variações apontam para a URL canônica principal
        $canonicalPaths = [
            'home' => '',  // /home → /
            'index' => '', // /index → /
            '' => '',     // / → /
        ];
        
        // Se o path está no mapeamento, usar a versão canônica
        if (isset($canonicalPaths[$path])) {
            $path = $canonicalPaths[$path];
        }
        
        // Normalizar path final
        if (empty($path)) {
            $path = '/';
        } else {
            $path = '/' . $path;
        }
        
        // Adicionar www ao host para URL canônica (exceto localhost)
        if ($host !== 'localhost' && 
            $host !== '127.0.0.1' && 
            $host !== '::1' &&
            !str_contains($host, 'localhost')) {
            if (!str_starts_with($host, 'www.')) {
                $host = 'www.' . $host;
            }
        }
        
        // Construir URL canônica (sem query strings que não alteram o conteúdo)
        // Query strings como utm_source, ref, etc. não devem aparecer na URL canônica
        $canonicalUrl = $scheme . '://' . $host . $path;
        
        return $canonicalUrl;
    }
}
