<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContentFormHelper
{
    /**
     * Obtém o conteúdo de um campo específico de um formulário
     * 
     * @param string $tema Nome do tema
     * @param string $pagina Nome da página
     * @param string $campoNome Nome do campo (ex: texto_1, imagem_1, link_1)
     * @param string $atributo Atributo específico para imagens (src, alt) ou links (href, texto)
     * @return string|null Valor do campo ou null se não encontrado
     */
    public static function getCampo($tema, $pagina, $campoNome, $atributo = null)
    {
        // Validar parâmetros para evitar erros
        if (empty($tema) || empty($pagina) || empty($campoNome)) {
            return null;
        }
        
        try {
            $formulario = DB::table('content_forms')
                ->where('tema', $tema)
                ->where('pagina', $pagina)
                ->orderBy('updated_at', 'desc')
                ->first();
            
            if (!$formulario) {
                return null;
            }
            
            $configuracao = json_decode($formulario->configuracao, true);
            
            // Extrair seções (suporta formato antigo e novo)
            $secoes = [];
            if (isset($configuracao['secoes']) && is_array($configuracao['secoes'])) {
                $secoes = $configuracao['secoes'];
            } elseif (is_array($configuracao) && isset($configuracao[0]) && is_array($configuracao[0])) {
                $secoes = $configuracao;
            }
            
            if (empty($secoes)) {
                return null;
            }
            
            // Procurar o campo em todas as seções
            foreach ($secoes as $secao) {
                if (isset($secao['campos'])) {
                    foreach ($secao['campos'] as $campo) {
                        if (isset($campo['nome']) && $campo['nome'] === $campoNome && ($campo['ativo'] ?? '1') === '1') {
                            // Para imagens com atributo específico
                            if ($campo['tipo'] === 'imagem' && $atributo) {
                                $valor = $campo[$atributo] ?? null;
                                
                                // IMPORTANTE: Limpar código Blade se presente (pode acontecer se foi salvo incorretamente)
                                if (!empty($valor) && (strpos($valor, '{{') !== false || strpos($valor, '{!!') !== false)) {
                                    // Remover código Blade
                                    $valor = preg_replace('/\{\{\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*\}\}/i', '$1', $valor);
                                    $valor = preg_replace('/\{!!\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*!!\}/i', '$1', $valor);
                                    // Remover qualquer código Blade restante
                                    $valor = preg_replace('/\{\{[^}]+\}\}/', '', $valor);
                                    $valor = preg_replace('/\{!![^!]+!!\}/', '', $valor);
                                    $valor = trim($valor);
                                    
                                    Log::warning("ContentFormHelper::getCampo - Código Blade removido do valor:", [
                                        'tema' => $tema,
                                        'pagina' => $pagina,
                                        'campoNome' => $campoNome,
                                        'atributo' => $atributo,
                                        'valor_original' => $campo[$atributo] ?? null,
                                        'valor_limpo' => $valor
                                    ]);
                                }
                                
                                // IMPORTANTE: Se o valor for null ou vazio, logar para debug
                                if (empty($valor)) {
                                    Log::warning("ContentFormHelper::getCampo - Campo de imagem sem valor:", [
                                        'tema' => $tema,
                                        'pagina' => $pagina,
                                        'campoNome' => $campoNome,
                                        'atributo' => $atributo,
                                        'campo_completo' => $campo,
                                        'todas_chaves' => array_keys($campo)
                                    ]);
                                }
                                
                                // Log para debug (sempre logar quando vazio, não apenas em debug mode)
                                if (config('app.debug', false) && !empty($valor)) {
                                    Log::debug("ContentFormHelper::getCampo - Imagem encontrada:", [
                                        'tema' => $tema,
                                        'pagina' => $pagina,
                                        'campoNome' => $campoNome,
                                        'atributo' => $atributo,
                                        'valor' => $valor
                                    ]);
                                }
                                
                                // Retornar string vazia em vez de null para evitar src=""
                                return $valor ?? '';
                            }
                            
                            // Para links com atributo específico
                            if ($campo['tipo'] === 'link' && $atributo) {
                                return $campo[$atributo] ?? null;
                            }
                            
                            // Para outros tipos, retornar o valor
                            return $campo['valor'] ?? null;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Em caso de erro, retornar null silenciosamente
            Log::warning('Erro ao buscar campo no ContentFormHelper: ' . $e->getMessage());
            return null;
        }
        
        return null;
    }
    
    /**
     * Obtém todos os campos de uma seção específica
     * 
     * @param string $tema Nome do tema
     * @param string $pagina Nome da página
     * @param int $secaoIndex Índice da seção (começando em 1)
     * @return array Array de campos da seção
     */
    public static function getSecao($tema, $pagina, $secaoIndex = 1)
    {
        $formulario = DB::table('content_forms')
            ->where('tema', $tema)
            ->where('pagina', $pagina)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        if (!$formulario) {
            return [];
        }
        
        $configuracao = json_decode($formulario->configuracao, true);
        
        // Extrair seções (suporta formato antigo e novo)
        $secoes = [];
        if (isset($configuracao['secoes']) && is_array($configuracao['secoes'])) {
            $secoes = $configuracao['secoes'];
        } elseif (is_array($configuracao) && isset($configuracao[0]) && is_array($configuracao[0])) {
            $secoes = $configuracao;
        }
        
        if (empty($secoes) || !isset($secoes[$secaoIndex - 1])) {
            return [];
        }
        
        // Filtrar apenas campos ativos
        $secao = $secoes[$secaoIndex - 1];
        if (isset($secao['campos'])) {
            $secao['campos'] = array_filter($secao['campos'], function($campo) {
                return ($campo['ativo'] ?? '1') === '1';
            });
        }
        
        return $secao;
    }
    
    /**
     * Obtém todos os campos de um formulário
     * 
     * @param string $tema Nome do tema
     * @param string $pagina Nome da página
     * @return array Array de seções com seus campos
     */
    public static function getFormulario($tema, $pagina)
    {
        $formulario = DB::table('content_forms')
            ->where('tema', $tema)
            ->where('pagina', $pagina)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        if (!$formulario) {
            return [];
        }
        
        $configuracao = json_decode($formulario->configuracao, true);
        
        // Extrair seções (suporta formato antigo e novo)
        $secoes = [];
        if (isset($configuracao['secoes']) && is_array($configuracao['secoes'])) {
            $secoes = $configuracao['secoes'];
        } elseif (is_array($configuracao) && isset($configuracao[0]) && is_array($configuracao[0])) {
            $secoes = $configuracao;
        }
        
        if (empty($secoes)) {
            return [];
        }
        
        // Filtrar apenas campos ativos em todas as seções
        foreach ($secoes as &$secao) {
            if (isset($secao['campos'])) {
                $secao['campos'] = array_filter($secao['campos'], function($campo) {
                    return ($campo['ativo'] ?? '1') === '1';
                });
            }
        }
        
        return $secoes;
    }
    
    /**
     * Renderiza um campo de imagem
     * 
     * @param string $tema Nome do tema
     * @param string $pagina Nome da página
     * @param string $campoNome Nome do campo
     * @param string $classes Classes CSS adicionais
     * @return string HTML da imagem ou string vazia
     */
    public static function renderImagem($tema, $pagina, $campoNome, $classes = '')
    {
        try {
            $src = self::getCampo($tema, $pagina, $campoNome, 'src');
            $alt = self::getCampo($tema, $pagina, $campoNome, 'alt');
            
            if (!$src) {
                return '';
            }
            
            $altAttr = $alt ? ' alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '"' : '';
            $classesAttr = $classes ? ' class="' . htmlspecialchars($classes, ENT_QUOTES, 'UTF-8') . '"' : '';
            
            return '<img src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"' . $altAttr . $classesAttr . '>';
        } catch (\Exception $e) {
            Log::warning('Erro ao renderizar imagem no ContentFormHelper: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Renderiza um campo de link
     * 
     * @param string $tema Nome do tema
     * @param string $pagina Nome da página
     * @param string $campoNome Nome do campo
     * @param string $classes Classes CSS adicionais
     * @return string HTML do link ou string vazia
     */
    public static function renderLink($tema, $pagina, $campoNome, $classes = '')
    {
        try {
            $href = self::getCampo($tema, $pagina, $campoNome, 'href');
            $texto = self::getCampo($tema, $pagina, $campoNome, 'texto');
            
            if (!$href) {
                return '';
            }
            
            // IMPORTANTE: Se o texto contém HTML (tags aninhadas), preservar o HTML
            // Verificar se há tags HTML no texto
            $temHtml = strip_tags($texto) !== $texto;
            
            if ($temHtml) {
                // Preservar HTML interno completo (ex: <strong>Saiba Mais</strong>)
                $textoContent = $texto ?: '';
            } else {
                // Apenas texto simples, escapar normalmente
                $textoContent = $texto ? htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') : '';
            }
            
            $classesAttr = $classes ? ' class="' . htmlspecialchars($classes, ENT_QUOTES, 'UTF-8') . '"' : '';
            
            return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"' . $classesAttr . '>' . $textoContent . '</a>';
        } catch (\Exception $e) {
            Log::warning('Erro ao renderizar link no ContentFormHelper: ' . $e->getMessage());
            return '';
        }
    }
}

