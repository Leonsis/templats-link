<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class LinkReplacerController extends Controller
{
    /**
     * Substituir links .html pelas rotas corretas do Laravel
     */
    public function replaceHtmlLinks($tema)
    {
        try {
            $temaViewsPath = resource_path('views/temas/' . $tema);
            
            if (!File::exists($temaViewsPath)) {
                return response()->json(['error' => 'Tema não encontrado'], 404);
            }

            // Mapeamento de páginas para rotas
            $mapeamentoRotas = $this->getMapeamentoRotas($tema);
            
            // Buscar todos os arquivos .blade.php do tema
            $arquivos = $this->getArquivosBlade($temaViewsPath);
            
            $resultado = [
                'tema' => $tema,
                'arquivos_processados' => 0,
                'links_substituidos' => 0,
                'detalhes' => []
            ];

            foreach ($arquivos as $arquivo) {
                $conteudoOriginal = File::get($arquivo);
                $conteudoNovo = $this->substituirLinksNoArquivo($conteudoOriginal, $mapeamentoRotas);
                
                if ($conteudoOriginal !== $conteudoNovo) {
                    File::put($arquivo, $conteudoNovo);
                    $resultado['arquivos_processados']++;
                    
                    // Contar quantos links foram substituídos
                    $linksSubstituidos = $this->contarLinksSubstituidos($conteudoOriginal, $conteudoNovo);
                    $resultado['links_substituidos'] += $linksSubstituidos;
                    
                    $resultado['detalhes'][] = [
                        'arquivo' => basename($arquivo),
                        'links_substituidos' => $linksSubstituidos
                    ];
                }
            }

            \Log::info("Links HTML substituídos para tema {$tema}: {$resultado['links_substituidos']} links em {$resultado['arquivos_processados']} arquivos");
            
            return response()->json($resultado);

        } catch (\Exception $e) {
            \Log::error("Erro ao substituir links HTML: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obter mapeamento de páginas para rotas
     */
    private function getMapeamentoRotas($tema)
    {
        $mapeamento = [
            // Rotas principais
            'index.html' => route('home'),
            'index' => route('home'),
            'home.html' => route('home'),
            'home' => route('home'),
            
            // Rotas especiais
            'about.html' => route('sobre'),
            'about' => route('sobre'),
            'contact.html' => route('contato'),
            'contact' => route('contato'),
            'contato.html' => route('contato'),
            'contato' => route('contato'),
        ];

        // Buscar rotas dinâmicas do banco de dados
        try {
            $rotasDinamicas = DB::table('rotas_dinamicas')
                ->where('tema', $tema)
                ->where('ativo', 1)
                ->get();

            foreach ($rotasDinamicas as $rotaDinamica) {
                $nomePagina = $rotaDinamica->pagina;
                $rota = $rotaDinamica->rota;
                
                // Adicionar variações com .html
                $mapeamento[$nomePagina . '.html'] = $rota;
                $mapeamento[$nomePagina] = $rota;
            }
        } catch (\Exception $e) {
            \Log::warning("Erro ao buscar rotas dinâmicas: " . $e->getMessage());
        }

        return $mapeamento;
    }

    /**
     * Buscar todos os arquivos .blade.php
     */
    private function getArquivosBlade($diretorio)
    {
        $arquivos = [];
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($diretorio, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $arquivo) {
            if ($arquivo->isFile() && $arquivo->getExtension() === 'php' && 
                str_ends_with($arquivo->getFilename(), '.blade.php')) {
                $arquivos[] = $arquivo->getPathname();
            }
        }

        return $arquivos;
    }

    /**
     * Substituir links em um arquivo
     */
    private function substituirLinksNoArquivo($conteudo, $mapeamento)
    {
        $conteudoNovo = $conteudo;

        foreach ($mapeamento as $paginaHtml => $rotaLaravel) {
            // Padrões para substituir
            $padroes = [
                // href="pagina.html"
                '/href=["\']' . preg_quote($paginaHtml, '/') . '["\']/i',
                // href='pagina.html'
                '/href=[\'"]' . preg_quote($paginaHtml, '/') . '[\'"]/i',
            ];

            foreach ($padroes as $padrao) {
                $conteudoNovo = preg_replace($padrao, 'href="' . $rotaLaravel . '"', $conteudoNovo);
            }
        }

        return $conteudoNovo;
    }

    /**
     * Contar quantos links foram substituídos
     */
    private function contarLinksSubstituidos($conteudoOriginal, $conteudoNovo)
    {
        // Contar ocorrências de .html no conteúdo original
        $ocorrenciasOriginal = preg_match_all('/\.html/i', $conteudoOriginal);
        $ocorrenciasNovo = preg_match_all('/\.html/i', $conteudoNovo);
        
        return $ocorrenciasOriginal - $ocorrenciasNovo;
    }

    /**
     * Substituir links para todos os temas
     */
    public function replaceAllThemes()
    {
        try {
            $temasPath = resource_path('views/temas');
            $temas = [];

            if (File::exists($temasPath)) {
                $diretorios = File::directories($temasPath);
                
                foreach ($diretorios as $diretorio) {
                    $nomeTema = basename($diretorio);
                    $resultado = $this->replaceHtmlLinks($nomeTema);
                    $temas[] = $resultado->getData();
                }
            }

            return response()->json([
                'total_temas' => count($temas),
                'temas' => $temas
            ]);

        } catch (\Exception $e) {
            \Log::error("Erro ao substituir links em todos os temas: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
