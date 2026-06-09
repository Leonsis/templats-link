<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AMPHelper
{
    /**
     * Verifica se a página deve ser servida em versão AMP
     * 
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public static function shouldServeAMP($request)
    {
        // Verificar se é uma requisição AMP explícita
        if ($request->has('amp') || $request->path() === 'amp' || str_contains($request->path(), '/amp/')) {
            return true;
        }
        
        // Verificar se o User-Agent é de um bot/crawler que prefere AMP
        $userAgent = $request->userAgent() ?? '';
        $ampBots = ['Googlebot', 'bingbot', 'Twitterbot', 'LinkedInBot'];
        
        foreach ($ampBots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Gera a URL da versão AMP de uma página
     * 
     * @param string $url
     * @return string
     */
    public static function getAMPUrl($url)
    {
        // Remover protocolo e domínio se existir
        $path = parse_url($url, PHP_URL_PATH) ?? $url;
        
        // Garantir que começa com /
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        
        // Adicionar /amp antes do path ou como query string
        $baseUrl = config('app.url', '');
        
        // Se a URL já contém /amp/, retornar como está
        if (str_contains($path, '/amp/')) {
            return $baseUrl . $path;
        }
        
        // Adicionar /amp/ antes do path
        return $baseUrl . '/amp' . $path;
    }
    
    /**
     * Remove o prefixo /amp da URL para obter a URL original
     * 
     * @param string $url
     * @return string
     */
    public static function getCanonicalUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH) ?? $url;
        
        // Remover /amp do início do path
        if (str_starts_with($path, '/amp')) {
            $path = substr($path, 4);
        }
        
        // Garantir que começa com /
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        
        // Normalizar domínio: adicionar www (versão canônica com www)
        $host = parse_url($url, PHP_URL_HOST) ?? request()->getHost();
        $scheme = parse_url($url, PHP_URL_SCHEME) ?? (request()->secure() ? 'https' : 'http');
        
        // Exceto localhost
        if ($host !== 'localhost' && 
            $host !== '127.0.0.1' && 
            $host !== '::1' &&
            !str_contains($host, 'localhost')) {
            // Adicionar www se não tiver
            if (!str_starts_with($host, 'www.')) {
                $host = 'www.' . $host;
            }
        }
        
        return $scheme . '://' . $host . $path;
    }
    
    /**
     * Valida se o HTML é válido para AMP
     * 
     * @param string $html
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validateAMP($html)
    {
        $errors = [];
        $warnings = [];
        
        // Validações básicas
        if (!str_contains($html, '<!doctype html>') && !str_contains($html, '<!DOCTYPE html>')) {
            $errors[] = 'HTML deve começar com <!doctype html>';
        }
        
        if (!str_contains($html, '⚡') && !str_contains($html, 'amp')) {
            $errors[] = 'HTML deve conter o atributo ⚡ ou amp no elemento html';
        }
        
        // Verificar se contém script do AMP runtime
        if (!str_contains($html, 'https://cdn.ampproject.org/v0.js')) {
            $errors[] = 'HTML deve incluir o script do AMP runtime';
        }
        
        // Verificar tags proibidas
        $prohibitedTags = ['<script', '<iframe', '<form', '<input', '<button'];
        foreach ($prohibitedTags as $tag) {
            if (str_contains($html, $tag) && !str_contains($html, 'amp-')) {
                // Verificar se não é um componente AMP permitido
                if ($tag === '<script' && str_contains($html, 'amp-')) {
                    continue; // Scripts AMP são permitidos
                }
                if ($tag === '<iframe' && str_contains($html, 'amp-iframe')) {
                    continue; // amp-iframe é permitido
                }
                if ($tag === '<form' && str_contains($html, 'amp-form')) {
                    continue; // amp-form é permitido
                }
                if ($tag === '<input' && str_contains($html, 'amp-form')) {
                    continue; // inputs em amp-form são permitidos
                }
                if ($tag === '<button' && str_contains($html, 'amp-form')) {
                    continue; // buttons em amp-form são permitidos
                }
                
                $warnings[] = "Tag {$tag} encontrada. Certifique-se de usar componentes AMP equivalentes.";
            }
        }
        
        // Verificar se tem viewport meta tag
        if (!str_contains($html, 'viewport')) {
            $errors[] = 'HTML deve conter meta tag viewport';
        }
        
        // Verificar se tem charset
        if (!str_contains($html, 'charset')) {
            $errors[] = 'HTML deve conter meta tag charset';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings
        ];
    }
    
    /**
     * Converte HTML regular para HTML AMP válido
     * 
     * @param string $html
     * @return string
     */
    public static function convertToAMP($html)
    {
        // Substituir tags proibidas por componentes AMP
        $html = preg_replace('/<img\s+([^>]*)>/i', '<amp-img $1></amp-img>', $html);
        $html = preg_replace('/<iframe\s+([^>]*)>/i', '<amp-iframe $1></amp-iframe>', $html);
        $html = preg_replace('/<video\s+([^>]*)>/i', '<amp-video $1></amp-video>', $html);
        $html = preg_replace('/<audio\s+([^>]*)>/i', '<amp-audio $1></amp-audio>', $html);
        
        // Remover scripts inline (exceto amp-*)
        $html = preg_replace('/<script(?![^>]*amp-)([^>]*)>(.*?)<\/script>/is', '', $html);
        
        // Adicionar atributo amp ao elemento html se não existir
        if (!str_contains($html, '⚡') && !str_contains($html, ' amp')) {
            $html = preg_replace('/<html([^>]*)>/i', '<html$1 ⚡>', $html);
        }
        
        return $html;
    }
    
    /**
     * Verifica se o tema suporta AMP
     * 
     * @param string $tema
     * @return bool
     */
    public static function themeSupportsAMP($tema)
    {
        $ampLayoutPath = resource_path("views/temas/{$tema}/layouts/amp.blade.php");
        return file_exists($ampLayoutPath);
    }
    
    /**
     * Obtém o layout AMP para um tema
     * 
     * @param string $tema
     * @return string|null
     */
    public static function getAMPLayout($tema)
    {
        if (self::themeSupportsAMP($tema)) {
            return "temas.{$tema}.layouts.amp";
        }
        
        // Fallback para layout AMP genérico
        return 'layouts.amp';
    }
    
    /**
     * Gera meta tags AMP necessárias
     * 
     * @param string $canonicalUrl
     * @param string $title
     * @param string $description
     * @param string $image
     * @return string
     */
    public static function generateAMPMetaTags($canonicalUrl, $title = '', $description = '', $image = '')
    {
        $meta = '<link rel="canonical" href="' . htmlspecialchars($canonicalUrl) . '">' . "\n";
        
        if ($title) {
            $meta .= '<meta property="og:title" content="' . htmlspecialchars($title) . '">' . "\n";
        }
        
        if ($description) {
            $meta .= '<meta name="description" content="' . htmlspecialchars($description) . '">' . "\n";
            $meta .= '<meta property="og:description" content="' . htmlspecialchars($description) . '">' . "\n";
        }
        
        if ($image) {
            $meta .= '<meta property="og:image" content="' . htmlspecialchars($image) . '">' . "\n";
        }
        
        return $meta;
    }
}

