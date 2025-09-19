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

        // CSP (Content Security Policy) - Atualizado com todas as fontes necessárias
        $csp = "default-src 'self'; "
             . "script-src 'self' https://ajax.googleapis.com https://d3e54v103j8qbb.cloudfront.net https://www.googletagmanager.com https://www.youtube.com https://s.ytimg.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com 'unsafe-inline'; "
             . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
             . "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com; "
             . "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com; "
             . "child-src 'self' https://www.youtube.com https://www.youtube-nocookie.com; "
             . "media-src 'self' https://www.youtube.com https://*.googlevideo.com; "
             . "img-src 'self' data: https://i.ytimg.com https://yt3.ggpht.com https://d3e54v103j8qbb.cloudfront.net; "
             . "connect-src 'self' https://www.google-analytics.com https://www.youtube.com https://s.ytimg.com https://www.google.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;";
        
        $csp = trim(preg_replace('/\s+/', ' ', $csp));
        $response->headers->set('Content-Security-Policy', $csp);

        // Outros headers de segurança
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); 
        $response->headers->set('X-Content-Type-Options', 'nosniff'); 
        $response->headers->set('X-XSS-Protection', '1; mode=block'); 
        $response->headers->set('Referrer-Policy', 'no-referrer');

        // Remove cabeçalhos PHP (se ainda existirem)
        @header_remove('X-Powered-By');
        @header_remove('Server');

        return $response;
    }
}
