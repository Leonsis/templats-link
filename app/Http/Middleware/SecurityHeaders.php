<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     * 
     * Este middleware aplica automaticamente headers de segurança em todas as páginas do sistema,
     * incluindo temas instalados. As tags de segurança são aplicadas globalmente via middleware
     * registrado no grupo 'web' do Kernel, garantindo proteção em todas as rotas.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // CSP (Content Security Policy) - Atualizado com todas as fontes necessárias para Google Tag Manager, Google Analytics 4, Google Ads, Cloudflare, YouTube e Facebook Pixel
        $csp = "default-src 'self'; "
            . "script-src 'self' https://ajax.googleapis.com https://d3e54v103j8qbb.cloudfront.net https://www.googletagmanager.com https://www.google-analytics.com https://analytics.google.com https://www.gstatic.com https://www.youtube.com https://www.youtube-nocookie.com https://s.ytimg.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://static.cloudflareinsights.com https://googleads.g.doubleclick.net https://www.googleadservices.com https://www.google.com https://connect.facebook.net 'unsafe-inline' 'unsafe-eval'; "
            . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://www.googletagmanager.com; "
            . "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com; "
            . "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://www.googletagmanager.com https://googleads.g.doubleclick.net https://www.google.com https://www.google.com.br; "
            . "child-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://www.googletagmanager.com https://googleads.g.doubleclick.net https://www.google.com https://www.google.com.br; "
            . "media-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://*.googlevideo.com; "
            . "img-src 'self' data: blob: https://i.ytimg.com https://yt3.ggpht.com https://d3e54v103j8qbb.cloudfront.net https://cdn.prod.website-files.com https://www.google-analytics.com https://analytics.google.com https://www.googletagmanager.com https://googleads.g.doubleclick.net https://www.googleadservices.com https://www.google.com https://www.google.com.br https://fonts.gstatic.com https://www.facebook.com; "
            . "connect-src 'self' https://www.google-analytics.com https://analytics.google.com https://stats.g.doubleclick.net https://www.googletagmanager.com https://www.youtube.com https://www.youtube-nocookie.com https://s.ytimg.com https://www.google.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://www.gstatic.com https://googleads.g.doubleclick.net https://www.googleadservices.com https://static.cloudflareinsights.com https://*.googlevideo.com https://connect.facebook.net;";
        
        $csp = trim(preg_replace('/\s+/', ' ', $csp));
        $response->headers->set('Content-Security-Policy', $csp);
        
        // Debug: Log do CSP aplicado
        \Log::info('CSP aplicado: ' . $csp);
        
        // Forçar atualização do cache para garantir que o novo CSP seja aplicado
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        // Outros headers de segurança
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); 
        $response->headers->set('X-Content-Type-Options', 'nosniff'); 
        $response->headers->set('X-XSS-Protection', '1; mode=block'); 
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Strict-Transport-Security (HSTS) - Apenas em HTTPS (não em localhost)
        $host = $request->getHost();
        $isLocalhost = in_array($host, ['localhost', '127.0.0.1', '::1']) || strpos($host, 'localhost') !== false;
        if ($request->secure() && !$isLocalhost) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        // Permissions-Policy (anteriormente Feature-Policy)
        $permissionsPolicy = "geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()";
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        // Remove cabeçalhos PHP (se ainda existirem)
        @header_remove('X-Powered-By');
        @header_remove('Server');

        return $response;
    }
}
