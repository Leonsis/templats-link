<?php

if (!function_exists('uploaded_assets')) {
    /**
     * Retorna a URL completa para um asset de imagem enviado
     * 
     * @param string|null $path Caminho relativo ou completo da imagem
     * @return string URL completa da imagem ou string vazia
     */
    function uploaded_assets($path = null)
    {
        if (empty($path)) {
            return '';
        }
        
        // IMPORTANTE: Limpar código Blade se presente (pode acontecer se o valor foi salvo incorretamente)
        // Remover {{ asset('...') }} ou {!! asset('...') !!}
        if (strpos($path, '{{') !== false || strpos($path, '{!!') !== false) {
            $path = preg_replace('/\{\{\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*\}\}/i', '$1', $path);
            $path = preg_replace('/\{!!\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*!!\}/i', '$1', $path);
            // Remover qualquer código Blade restante
            $path = preg_replace('/\{\{[^}]+\}\}/', '', $path);
            $path = preg_replace('/\{!![^!]+!!\}/', '', $path);
        }
        
        // Normalizar o caminho removendo barras duplicadas e espaços
        $path = trim($path);
        $path = preg_replace('#/+#', '/', $path);
        
        // Log inicial para debug (sempre logar na hospedagem para diagnóstico)
        $isDebug = config('app.debug', false);
        if ($isDebug) {
            \Log::debug("uploaded_assets INICIO: path={$path}");
        }
        
        // PRIORIDADE 1: Se é uma URL completa (http:// ou https://)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            // Limpar URLs malformadas (ex: http://https// ou https://https//)
            $url = preg_replace('#^https?://(https?://)+#i', 'https://', $path);
            $url = preg_replace('#^http://(https?://)+#i', 'https://', $url);
            
            // Normalizar barras duplicadas
            $url = preg_replace('#([^:])//+#', '$1/', $url);
            
            // Se a URL contém /storage/app/public/, converter para /storage/
            if (str_contains($url, '/storage/app/public/')) {
                $url = str_replace('/storage/app/public/', '/storage/', $url);
                if ($isDebug) {
                    \Log::debug("uploaded_assets URL completa convertida: {$url}");
                }
                return $url;
            }
            
            // Validar se a URL é bem formada antes de retornar
            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                if ($isDebug) {
                    \Log::warning("uploaded_assets URL malformada detectada, tentando corrigir: {$path}");
                }
                // Se a URL está malformada, tentar extrair o caminho e reconstruir
                $parsed = parse_url($url);
                if ($parsed && isset($parsed['host'])) {
                    $scheme = isset($parsed['scheme']) ? $parsed['scheme'] : 'https';
                    $host = $parsed['host'];
                    $pathPart = isset($parsed['path']) ? $parsed['path'] : '';
                    $url = $scheme . '://' . $host . $pathPart;
                }
            }
            
            // Se já é URL completa e não tem storage/app/public, retornar como está
            if ($isDebug) {
                \Log::debug("uploaded_assets URL completa retornada: {$url}");
            }
            return $url;
        }
        
        // PRIORIDADE 2: Se contém storage/app/public/ (caminho físico do banco)
        if (str_contains($path, 'storage/app/public/')) {
            // Extrair o caminho relativo (sem storage/app/public/)
            $relativePath = str_replace('storage/app/public/', '', $path);
            $relativePath = ltrim($relativePath, '/');
            
            if ($isDebug) {
                \Log::debug("uploaded_assets relativePath extraído: {$relativePath}");
            }
            
            // ESTRATÉGIA 1: Tentar usar Storage::disk('public')->url()
            // MAS verificar se a URL não contém localhost (indica APP_URL incorreto)
            try {
                $storageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($relativePath);
                
                // Verificar se a URL gerada é válida e não contém localhost
                if (!empty($storageUrl) && !str_contains($storageUrl, 'localhost')) {
                    if ($isDebug) {
                        \Log::debug("uploaded_assets Storage::url() sucesso: {$storageUrl}");
                    }
                    return $storageUrl;
                } else {
                    if ($isDebug) {
                        \Log::warning("uploaded_assets Storage::url() retornou localhost ou vazio, usando asset() como fallback: {$storageUrl}");
                    }
                }
            } catch (\Exception $e) {
                if ($isDebug) {
                    \Log::warning("uploaded_assets Storage::url() exceção: " . $e->getMessage());
                }
            }
            
            // ESTRATÉGIA 2: Usar asset() com /storage/ e garantir URL absoluta
            $assetUrl = asset('/storage/' . $relativePath);
            
            // Garantir que a URL é absoluta (com http:// ou https://)
            // Se asset() retornou URL relativa, converter para absoluta
            if (!str_starts_with($assetUrl, 'http://') && !str_starts_with($assetUrl, 'https://')) {
                $appUrl = rtrim(config('app.url', ''), '/');
                
                // Validar e limpar APP_URL para evitar duplicações
                $appUrl = preg_replace('#^https?://(https?://)+#i', 'https://', $appUrl);
                $appUrl = preg_replace('#([^:])//+#', '$1/', $appUrl);
                
                // Se asset() retornou caminho relativo, adicionar APP_URL
                if (str_starts_with($assetUrl, '/')) {
                    $url = $appUrl . $assetUrl;
                } else {
                    $url = $appUrl . '/' . $assetUrl;
                }
            } else {
                // Se asset() já retornou URL completa, validar e limpar
                $url = preg_replace('#^https?://(https?://)+#i', 'https://', $assetUrl);
                $url = preg_replace('#([^:])//+#', '$1/', $url);
            }
            
            // Verificar se o arquivo existe fisicamente antes de retornar
            $physicalPath = storage_path('app/public/' . $relativePath);
            $publicPath = public_path('storage/' . $relativePath);
            
            $fileExists = file_exists($physicalPath) || file_exists($publicPath);
            
            if ($isDebug) {
                \Log::debug("uploaded_assets asset() gerado: {$assetUrl}");
                \Log::debug("uploaded_assets URL final: {$url}");
                \Log::debug("uploaded_assets physicalPath: {$physicalPath} (existe: " . (file_exists($physicalPath) ? 'sim' : 'não') . ")");
                \Log::debug("uploaded_assets publicPath: {$publicPath} (existe: " . (file_exists($publicPath) ? 'sim' : 'não') . ")");
            }
            
            // Se o arquivo não existe, logar aviso mas ainda retornar a URL
            // (pode ser que o link simbólico não esteja criado)
            if (!$fileExists) {
                \Log::warning("uploaded_assets arquivo não encontrado: physicalPath={$physicalPath}, publicPath={$publicPath}");
            }
            
            return $url;
        }
        
        // PRIORIDADE 3: Se começa com /storage/, usar asset diretamente
        if (str_starts_with($path, '/storage/')) {
            $url = asset($path);
            if ($isDebug) {
                \Log::debug("uploaded_assets /storage/ direto: {$url}");
            }
            return $url;
        }
        
        // PRIORIDADE 4: Se começa com storage/ (sem barra inicial), adicionar barra e usar asset
        if (str_starts_with($path, 'storage/')) {
            $url = asset('/' . $path);
            if ($isDebug) {
                \Log::debug("uploaded_assets storage/ sem barra: {$url}");
            }
            return $url;
        }
        
        // PRIORIDADE 5: Se contém temas/ mas não começa com storage/, adicionar storage/
        if (str_contains($path, 'temas/') && !str_starts_with($path, 'storage/')) {
            $url = asset('/storage/' . ltrim($path, '/'));
            if ($isDebug) {
                \Log::debug("uploaded_assets temas/ sem storage/: {$url}");
            }
            return $url;
        }
        
        // Caso contrário, usar asset() diretamente
        $url = asset($path);
        if ($isDebug) {
            \Log::debug("uploaded_assets fallback asset(): {$url}");
        }
        return $url;
    }
}

if (!function_exists('normalize_route_name')) {
    /**
     * Normaliza o nome de uma rota substituindo caracteres inválidos
     * Converte kebab-case para camelCase (ex: Mental-ice → MentalIce)
     * 
     * @param string $name Nome da rota a ser normalizado
     * @return string Nome da rota normalizado
     */
    function normalize_route_name($name)
    {
        // Se não contém hífens, retornar como está
        if (!str_contains($name, '-')) {
            return $name;
        }
        
        // Dividir por hífens e converter para camelCase
        $parts = explode('-', $name);
        $result = ucfirst($parts[0]); // Primeira parte com primeira letra maiúscula
        
        // Capitalizar primeira letra das partes restantes e juntar
        for ($i = 1; $i < count($parts); $i++) {
            $result .= ucfirst($parts[$i]);
        }
        
        return $result;
    }
}

if (!function_exists('theme_route_name')) {
    /**
     * Gera o nome de uma rota de tema normalizado
     * 
     * @param string $tema Nome do tema
     * @param string $nomeRota Nome da rota
     * @return string Nome completo da rota normalizado
     */
    function theme_route_name($tema, $nomeRota)
    {
        $temaNormalizado = normalize_route_name($tema);
        $nomeRotaNormalizado = normalize_route_name($nomeRota);
        return "tema.{$temaNormalizado}.{$nomeRotaNormalizado}";
    }
}
