<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SlugHelper
{
    /**
     * Normaliza um slug removendo caracteres inválidos e corrigindo problemas comuns
     * 
     * @param string $slug
     * @return string
     */
    public static function normalizarSlug($slug)
    {
        if (empty($slug)) {
            return '';
        }
        
        // Remover espaços extras
        $slug = trim($slug);
        
        // Mapeamento de correções específicas para slugs conhecidos com problemas
        $correcoesEspecificas = [
            // Slugs específicos mencionados no problema
            'a-import-ncia-da-contabilidade-para-atacadistas-um-guia-completo' => 'a-importancia-da-contabilidade-para-atacadistas-um-guia-completo',
            'import-ncia' => 'importancia',
            'mantenha-sua-empresa-atualizada-lei-para-atacadistas' => 'mantenha-sua-empresa-atualizada-lei-para-atacadistas',
            'entenda-o-regime-tribut-rio-de-lucro-real-com-a-prestacon-contabilidade' => 'entenda-o-regime-tributario-de-lucro-real-com-a-prestacon-contabilidade',
            'tribut-rio' => 'tributario',
        ];
        
        // Aplicar correções específicas primeiro (busca exata)
        foreach ($correcoesEspecificas as $errado => $correto) {
            if ($slug === $errado || str_contains($slug, $errado)) {
                $slug = str_replace($errado, $correto, $slug);
            }
        }
        
        // Mapeamento de caracteres problemáticos comuns
        $correcoes = [
            // Caracteres acentuados que podem ter sido mal codificados
            'importância' => 'importancia',
            'tributário' => 'tributario',
            // Outros caracteres problemáticos
            'ç' => 'c',
            'ã' => 'a',
            'õ' => 'o',
            'á' => 'a',
            'à' => 'a',
            'â' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'ú' => 'u',
            'ü' => 'u',
        ];
        
        // Aplicar correções específicas primeiro
        foreach ($correcoes as $errado => $correto) {
            $slug = str_replace($errado, $correto, $slug);
        }
        
        // Remover caracteres especiais inválidos (manter apenas letras, números, hífens e underscores)
        $slug = preg_replace('/[^a-z0-9\-_]/i', '', $slug);
        
        // Normalizar usando Str::slug do Laravel (remove acentos e converte para lowercase)
        $slug = Str::slug($slug);
        
        // Remover hífens duplicados
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Remover hífens no início e fim
        $slug = trim($slug, '-');
        
        return $slug;
    }
    
    /**
     * Busca um post por slug, tentando várias variações
     * 
     * @param string $slugNormalizado
     * @param string $slugOriginal (opcional, para busca mais ampla)
     * @return object|null
     */
    public static function buscarPostPorSlug($slugNormalizado, $slugOriginal = null)
    {
        // Primeiro, tentar buscar com o slug normalizado exato
        $post = DB::table('posts')
            ->where('ativo', 1)
            ->where('slug', $slugNormalizado)
            ->first();
        
        if ($post) {
            return $post;
        }
        
        // Se não encontrou, tentar buscar usando Str::slug do slug original
        if ($slugOriginal && $slugOriginal !== $slugNormalizado) {
            $slugOriginalNormalizado = Str::slug($slugOriginal);
            if ($slugOriginalNormalizado !== $slugNormalizado) {
                $post = DB::table('posts')
                    ->where('ativo', 1)
                    ->where('slug', $slugOriginalNormalizado)
                    ->first();
                
                if ($post) {
                    return $post;
                }
            }
        }
        
        // Tentar busca parcial (LIKE) para encontrar slugs similares
        // Isso ajuda quando há pequenas diferenças
        $slugBusca = str_replace('-', '%', $slugNormalizado);
        $post = DB::table('posts')
            ->where('ativo', 1)
            ->where('slug', 'like', '%' . $slugBusca . '%')
            ->first();
        
        if ($post) {
            return $post;
        }
        
        // Tentar buscar por similaridade (removendo partes problemáticas)
        // Ex: "a-import-ncia-da-contabilidade" pode corresponder a "a-importancia-da-contabilidade"
        $slugSimplificado = preg_replace('/[^a-z0-9]/i', '', $slugNormalizado);
        
        $posts = DB::table('posts')
            ->where('ativo', 1)
            ->get();
        
        foreach ($posts as $postCandidate) {
            $slugCandidateSimplificado = preg_replace('/[^a-z0-9]/i', '', $postCandidate->slug);
            if ($slugSimplificado === $slugCandidateSimplificado) {
                return $postCandidate;
            }
        }
        
        return null;
    }
    
    /**
     * Tenta encontrar um post correspondente mesmo com slug incorreto
     * Usa busca por similaridade no título
     * 
     * @param string $slug
     * @return object|null
     */
    public static function buscarPostPorSimilaridade($slug)
    {
        // Extrair palavras-chave do slug
        $palavras = explode('-', $slug);
        $palavras = array_filter($palavras, function($palavra) {
            return strlen($palavra) > 3; // Ignorar palavras muito curtas
        });
        
        if (empty($palavras)) {
            return null;
        }
        
        // Buscar posts que contenham essas palavras no título ou slug
        $query = DB::table('posts')
            ->where('ativo', 1);
        
        foreach ($palavras as $palavra) {
            $query->where(function($q) use ($palavra) {
                $q->where('titulo', 'like', '%' . $palavra . '%')
                  ->orWhere('slug', 'like', '%' . $palavra . '%');
            });
        }
        
        return $query->first();
    }
}

