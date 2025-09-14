<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use ZipArchive;

class TemasController extends Controller
{
    public function index()
    {
        $temas = $this->getTemasList();
        return view('dashboard.temas.index', compact('temas'));
    }

    public function store(Request $request)
    {
        // Debug: Log dos dados recebidos
        \Log::info('Upload de tema iniciado', [
            'nome_tema' => $request->input('nome_tema'),
            'arquivo_zip' => $request->hasFile('arquivo_zip') ? $request->file('arquivo_zip')->getClientOriginalName() : 'não enviado',
            'tamanho_arquivo_zip' => $request->hasFile('arquivo_zip') ? $request->file('arquivo_zip')->getSize() : 0,
            'arquivo_paginas' => $request->hasFile('arquivo_paginas') ? $request->file('arquivo_paginas')->getClientOriginalName() : 'não enviado',
            'tamanho_arquivo_paginas' => $request->hasFile('arquivo_paginas') ? $request->file('arquivo_paginas')->getSize() : 0,
            'tem_codigo_head' => !empty($request->input('codigo_head')),
            'tem_codigo_nav' => !empty($request->input('codigo_nav')),
            'tem_codigo_footer' => !empty($request->input('codigo_footer')),
            'tem_codigo_scripts' => !empty($request->input('codigo_scripts'))
        ]);

        $request->validate([
            'nome_tema' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]+$/',
            'arquivo_zip' => 'required|file|max:10485760', // 10MB = 10 * 1024 * 1024 bytes
            'arquivo_paginas' => 'nullable|file|max:10485760', // 10MB = 10 * 1024 * 1024 bytes
            'codigo_head' => 'nullable|string|max:10000',
            'codigo_nav' => 'nullable|string',
            'codigo_footer' => 'nullable|string|max:10000',
            'codigo_scripts' => 'nullable|string|max:10000'
        ], [
            'nome_tema.required' => 'O nome do tema é obrigatório.',
            'nome_tema.regex' => 'O nome do tema deve conter apenas letras, números, hífens e underscores.',
            'arquivo_zip.required' => 'O arquivo ZIP dos assets é obrigatório.',
            'arquivo_zip.max' => 'O arquivo ZIP dos assets não pode ser maior que 10MB.',
            'arquivo_paginas.max' => 'O arquivo ZIP das páginas não pode ser maior que 10MB.',
            'codigo_head.max' => 'O código do head não pode ter mais que 10.000 caracteres.',
            'codigo_footer.max' => 'O código do footer não pode ter mais que 10.000 caracteres.',
            'codigo_scripts.max' => 'O código dos scripts não pode ter mais que 10.000 caracteres.'
        ]);

        $nomeTema = $request->input('nome_tema');
        $arquivoZip = $request->file('arquivo_zip');
        $arquivoPaginas = $request->file('arquivo_paginas');
        
        // Verificar se os arquivos são ZIPs válidos
        $zip = new ZipArchive;
        
        // Validar ZIP dos assets
        $tempPathAssets = $arquivoZip->getPathname();
        if ($zip->open($tempPathAssets) !== TRUE) {
            return back()->withErrors(['arquivo_zip' => 'O arquivo ZIP dos assets não é válido ou está corrompido.']);
        }
        $zip->close();
        
        // Validar ZIP das páginas (se fornecido)
        if ($arquivoPaginas) {
            $tempPathPaginas = $arquivoPaginas->getPathname();
            if ($zip->open($tempPathPaginas) !== TRUE) {
                return back()->withErrors(['arquivo_paginas' => 'O arquivo ZIP das páginas não é válido ou está corrompido.']);
            }
            $zip->close();
        }
        
        
        // Verificar se o tema já existe
        $temaPath = public_path('temas/' . $nomeTema);
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        if (File::exists($temaPath) || File::exists($temaViewsPath)) {
            return back()->withErrors(['nome_tema' => 'Já existe um tema com este nome.']);
        }

        try {
            // Criar diretórios do tema
            File::makeDirectory($temaPath, 0755, true);
            
            // Sempre criar diretório para views do tema (replicando estrutura do main-Thema)
            File::makeDirectory($temaViewsPath, 0755, true);
            File::makeDirectory($temaViewsPath . '/inc', 0755, true);
            File::makeDirectory($temaViewsPath . '/layouts', 0755, true);

            // Processar ZIP dos assets
            $zipPathAssets = $temaPath . '/' . $arquivoZip->getClientOriginalName();
            $arquivoZip->move($temaPath, $arquivoZip->getClientOriginalName());

            $zip = new ZipArchive;
            if ($zip->open($zipPathAssets) === TRUE) {
                $zip->extractTo($temaPath);
                $zip->close();
                
                // Deletar o arquivo ZIP dos assets após descompactação
                File::delete($zipPathAssets);
            } else {
                // Se falhou ao abrir o ZIP dos assets, limpar os diretórios criados
                File::deleteDirectory($temaPath);
                File::deleteDirectory($temaViewsPath);
                return back()->withErrors(['arquivo_zip' => 'Erro ao processar o arquivo ZIP dos assets. Verifique se o arquivo não está corrompido.']);
            }
            
            // Processar ZIP das páginas (se fornecido)
            if ($arquivoPaginas) {
                $zipPathPaginas = $temaViewsPath . '/' . $arquivoPaginas->getClientOriginalName();
                $arquivoPaginas->move($temaViewsPath, $arquivoPaginas->getClientOriginalName());

                $zip = new ZipArchive;
                if ($zip->open($zipPathPaginas) === TRUE) {
                    $zip->extractTo($temaViewsPath);
                    $zip->close();
                    
                    // Deletar o arquivo ZIP das páginas após descompactação
                    File::delete($zipPathPaginas);
                } else {
                    // Se falhou ao abrir o ZIP das páginas, limpar os diretórios criados
                    File::deleteDirectory($temaPath);
                    File::deleteDirectory($temaViewsPath);
                    return back()->withErrors(['arquivo_paginas' => 'Erro ao processar o arquivo ZIP das páginas. Verifique se o arquivo não está corrompido.']);
                }
            }
            
            // Sempre criar arquivos blade (replicando estrutura do main-Thema)
            $this->criarArquivosBlade($temaViewsPath, $request);
            
            // Processar e modificar links nos arquivos do tema
            $this->processarLinksTema($temaPath, $temaViewsPath, $nomeTema);
            
            // Converter HTML para Blade e ajustar tema
            $this->converterHtmlParaBlade($temaViewsPath, $nomeTema);
            
            $mensagem = 'Tema "' . $nomeTema . '" instalado com sucesso! Assets processados, estrutura Blade criada, links atualizados e HTML convertido para Blade.';
            if ($arquivoPaginas) {
                $mensagem .= ' Páginas processadas.';
            }
            
            return redirect()->route('dashboard.temas')->with('success', $mensagem);
        } catch (\Exception $e) {
            // Em caso de erro, limpar os diretórios criados
            if (File::exists($temaPath)) {
                File::deleteDirectory($temaPath);
            }
            if (File::exists($temaViewsPath)) {
                File::deleteDirectory($temaViewsPath);
            }
            return back()->withErrors(['arquivo_zip' => 'Erro ao processar o tema: ' . $e->getMessage()]);
        }
    }

    public function preview($nomeTema)
    {
        $temaPath = public_path('temas/' . $nomeTema);
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        if (!File::exists($temaPath) && !File::exists($temaViewsPath)) {
            return back()->withErrors(['tema' => 'Tema não encontrado.']);
        }

        // Verificar se existem páginas do tema
        if (!File::exists($temaViewsPath)) {
            return back()->withErrors(['tema' => 'Este tema não possui páginas para preview.']);
        }

        // Listar arquivos Blade disponíveis no tema
        $arquivosBlade = File::glob($temaViewsPath . '/*.blade.php');
        
        // Filtrar apenas páginas (excluir inc e layouts)
        $paginas = collect($arquivosBlade)
            ->filter(function($arquivo) {
                $caminho = $arquivo;
                return !str_contains($caminho, '/inc/') && 
                       !str_contains($caminho, '/layouts/');
            })
            ->map(function($arquivo) {
                return basename($arquivo, '.blade.php');
            })
            ->values()
            ->toArray();
        
        if (empty($paginas)) {
            return back()->withErrors(['tema' => 'Nenhuma página encontrada neste tema.']);
        }

        // Pegar a primeira página como página principal
        $paginaPrincipal = $paginas[0];
        
        // Preparar dados para a view
        $dadosTema = [
            'nome' => $nomeTema,
            'pagina_principal' => $paginaPrincipal,
            'paginas_disponiveis' => $paginas,
            'assets_path' => '/temas/' . $nomeTema . '/assets'
        ];

        return view('dashboard.temas.preview', compact('dadosTema'));
    }

    public function previewPage($nomeTema, $pagina)
    {
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        $arquivoBlade = $temaViewsPath . '/' . $pagina . '.blade.php';
        
        if (!File::exists($arquivoBlade)) {
            return response()->json(['error' => 'Página não encontrada'], 404);
        }

        try {
            // Renderizar a view Blade
            $conteudo = view('temas.' . $nomeTema . '.' . $pagina)->render();
            
            return response($conteudo, 200, [
                'Content-Type' => 'text/html; charset=utf-8'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao renderizar a página: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($nomeTema)
    {
        $temaPath = public_path('temas/' . $nomeTema);
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        if (!File::exists($temaPath) && !File::exists($temaViewsPath)) {
            return back()->withErrors(['tema' => 'Tema não encontrado.']);
        }

        try {
            // Remover assets do tema
            if (File::exists($temaPath)) {
                File::deleteDirectory($temaPath);
            }
            
            // Remover views do tema
            if (File::exists($temaViewsPath)) {
                File::deleteDirectory($temaViewsPath);
            }
            
            return redirect()->route('dashboard.temas')->with('success', 'Tema "' . $nomeTema . '" removido com sucesso! Assets e views removidos.');
        } catch (\Exception $e) {
            return back()->withErrors(['tema' => 'Erro ao remover o tema: ' . $e->getMessage()]);
        }
    }

    private function getTemasList()
    {
        $temasPath = public_path('temas');
        $temas = [];

        if (File::exists($temasPath)) {
            $diretorios = File::directories($temasPath);
            
            foreach ($diretorios as $diretorio) {
                $nomeTema = basename($diretorio);
                $arquivos = File::allFiles($diretorio);
                
                // Verificar se existem páginas no tema
                $temaViewsPath = resource_path('views/temas/' . $nomeTema);
                $temPaginas = false;
                $arquivosPaginas = 0;
                
                $paginasDisponiveis = [];
                if (File::exists($temaViewsPath)) {
                    // Contar apenas arquivos na raiz do diretório do tema (excluindo inc e layouts)
                    $arquivosPaginas = collect(File::files($temaViewsPath))
                        ->filter(function($arquivo) {
                            $nome = $arquivo->getFilename();
                            // Contar apenas arquivos HTML e Blade na raiz
                            return str_ends_with($nome, '.html') || str_ends_with($nome, '.blade.php');
                        });
                    
                    $temPaginas = $arquivosPaginas->count() > 0;
                    
                    // Coletar nomes das páginas para o dropdown
                    $paginasDisponiveis = $arquivosPaginas->map(function($arquivo) {
                        return basename($arquivo->getFilename(), '.blade.php');
                    })->values()->toArray();
                }
                
                $temas[] = [
                    'nome' => $nomeTema,
                    'caminho' => $diretorio,
                    'arquivos' => count($arquivos),
                    'tamanho' => $this->getDirectorySize($diretorio),
                    'criado_em' => date('d/m/Y H:i', filemtime($diretorio)),
                    'tem_paginas' => $temPaginas,
                    'arquivos_paginas' => count($paginasDisponiveis),
                    'paginas_disponiveis' => $paginasDisponiveis
                ];
            }
        }

        return $temas;
    }

    private function getDirectorySize($directory)
    {
        $size = 0;
        foreach (File::allFiles($directory) as $file) {
            $size += $file->getSize();
        }
        
        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }

    private function criarArquivosBlade($temaViewsPath, $request)
    {
        $nomeTema = $request->input('nome_tema');
        
        // Sempre criar todos os arquivos do diretório inc (mesmo que vazios)
        // Criar arquivo head.blade.php
        $codigoHead = !empty($request->input('codigo_head')) ? $request->input('codigo_head') : '{{-- Arquivo head do tema ' . $nomeTema . ' --}}';
        File::put($temaViewsPath . '/inc/head.blade.php', $codigoHead);
        
        // Criar arquivo nav.blade.php
        $codigoNav = !empty($request->input('codigo_nav')) ? $request->input('codigo_nav') : '{{-- Arquivo nav do tema ' . $nomeTema . ' --}}';
        File::put($temaViewsPath . '/inc/nav.blade.php', $codigoNav);
        
        // Criar arquivo footer.blade.php
        $codigoFooter = !empty($request->input('codigo_footer')) ? $request->input('codigo_footer') : '{{-- Arquivo footer do tema ' . $nomeTema . ' --}}';
        File::put($temaViewsPath . '/inc/footer.blade.php', $codigoFooter);
        
        // Criar arquivo scripts.blade.php
        $codigoScripts = !empty($request->input('codigo_scripts')) ? $request->input('codigo_scripts') : '{{-- Arquivo scripts do tema ' . $nomeTema . ' --}}';
        File::put($temaViewsPath . '/inc/scripts.blade.php', $codigoScripts);
        
        // Sempre criar o arquivo de layout principal (baseado no main-Thema)
        $layoutContent = $this->gerarLayoutPrincipal($request);
        File::put($temaViewsPath . '/layouts/app.blade.php', $layoutContent);
    }

    private function gerarLayoutPrincipal($request)
    {
        $nomeTema = $request->input('nome_tema');
        
        $layout = '<!DOCTYPE html>
<html lang="pt-BR">
@include(\'temas.' . $nomeTema . '.inc.head\')
@php
    // Detectar página atual baseada na rota
    $currentPage = \'global\';
    if (request()->routeIs(\'home\')) {
        $currentPage = \'home\';
    } elseif (request()->routeIs(\'sobre\')) {
        $currentPage = \'sobre\';
    } elseif (request()->routeIs(\'contato\')) {
        $currentPage = \'contato\';
    } elseif (request()->routeIs(\'login\')) {
        $currentPage = \'login\';
    }
@endphp

<body>
    <!-- Google Tag Manager (noscript) -->
    @if(\App\Helpers\HeadHelper::getGtmBody($currentPage))
        {!! \App\Helpers\HeadHelper::getGtmBody($currentPage) !!}
    @endif
    
    @include(\'temas.' . $nomeTema . '.inc.nav\')

    <!-- Main Content -->
    <main>
        @yield(\'content\')
    </main>

    @include(\'temas.' . $nomeTema . '.inc.footer\')

    @include(\'temas.' . $nomeTema . '.inc.scripts\')
</body>
</html>';

        return $layout;
    }

    private function processarLinksTema($temaPath, $temaViewsPath, $nomeTema)
    {
        // Processar arquivos HTML/Blade nas views
        if (File::exists($temaViewsPath)) {
            $this->processarArquivosHtml($temaViewsPath, $nomeTema);
        }
        
        // Processar arquivos CSS
        $cssPath = $temaPath . '/assets/css';
        if (File::exists($cssPath)) {
            $this->processarArquivosCss($cssPath, $nomeTema);
        }
        
        // Processar arquivos JavaScript
        $jsPath = $temaPath . '/assets/js';
        if (File::exists($jsPath)) {
            $this->processarArquivosJs($jsPath, $nomeTema);
        }
    }

    private function processarArquivosHtml($diretorio, $nomeTema)
    {
        $arquivos = File::glob($diretorio . '/*.{html,blade.php}', GLOB_BRACE);
        
        foreach ($arquivos as $arquivo) {
            $conteudo = File::get($arquivo);
            $conteudoModificado = $this->substituirLinks($conteudo, $nomeTema);
            File::put($arquivo, $conteudoModificado);
        }
        
        // Processar subdiretórios recursivamente
        $subdiretorios = File::directories($diretorio);
        foreach ($subdiretorios as $subdiretorio) {
            $this->processarArquivosHtml($subdiretorio, $nomeTema);
        }
    }

    private function processarArquivosCss($diretorio, $nomeTema)
    {
        $arquivos = File::glob($diretorio . '/*.css');
        
        foreach ($arquivos as $arquivo) {
            $conteudo = File::get($arquivo);
            $conteudoModificado = $this->substituirLinksCss($conteudo, $nomeTema);
            File::put($arquivo, $conteudoModificado);
        }
        
        // Processar subdiretórios recursivamente
        $subdiretorios = File::directories($diretorio);
        foreach ($subdiretorios as $subdiretorio) {
            $this->processarArquivosCss($subdiretorio, $nomeTema);
        }
    }

    private function processarArquivosJs($diretorio, $nomeTema)
    {
        $arquivos = File::glob($diretorio . '/*.js');
        
        foreach ($arquivos as $arquivo) {
            $conteudo = File::get($arquivo);
            $conteudoModificado = $this->substituirLinksJs($conteudo, $nomeTema);
            File::put($arquivo, $conteudoModificado);
        }
        
        // Processar subdiretórios recursivamente
        $subdiretorios = File::directories($diretorio);
        foreach ($subdiretorios as $subdiretorio) {
            $this->processarArquivosJs($subdiretorio, $nomeTema);
        }
    }

    private function substituirLinks($conteudo, $nomeTema)
    {
        // Substituir links de CSS
        $conteudo = preg_replace(
            '/href=["\']assets\/([^"\']+)["\']/',
            'href="{{ asset(\'temas/' . $nomeTema . '/assets/$1\') }}"',
            $conteudo
        );
        
        // Substituir links de JavaScript
        $conteudo = preg_replace(
            '/src=["\']assets\/([^"\']+)["\']/',
            'src="{{ asset(\'temas/' . $nomeTema . '/assets/$1\') }}"',
            $conteudo
        );
        
        // Substituir links de imagens
        $conteudo = preg_replace(
            '/src=["\']assets\/([^"\']+)["\']/',
            'src="{{ asset(\'temas/' . $nomeTema . '/assets/$1\') }}"',
            $conteudo
        );
        
        // Substituir background-image em CSS inline
        $conteudo = preg_replace(
            '/background-image:\s*url\(["\']?assets\/([^"\']+)["\']?\)/',
            'background-image: url({{ asset(\'temas/' . $nomeTema . '/assets/$1\') }})',
            $conteudo
        );
        
        return $conteudo;
    }

    private function substituirLinksCss($conteudo, $nomeTema)
    {
        // Substituir URLs em CSS
        $conteudo = preg_replace(
            '/url\(["\']?\.\.\/\.\.\/assets\/([^"\']+)["\']?\)/',
            'url({{ asset(\'temas/' . $nomeTema . '/assets/$1\') }})',
            $conteudo
        );
        
        $conteudo = preg_replace(
            '/url\(["\']?\.\.\/assets\/([^"\']+)["\']?\)/',
            'url({{ asset(\'temas/' . $nomeTema . '/assets/$1\') }})',
            $conteudo
        );
        
        $conteudo = preg_replace(
            '/url\(["\']?assets\/([^"\']+)["\']?\)/',
            'url({{ asset(\'temas/' . $nomeTema . '/assets/$1\') }})',
            $conteudo
        );
        
        return $conteudo;
    }

    private function substituirLinksJs($conteudo, $nomeTema)
    {
        // Substituir URLs em JavaScript
        $conteudo = preg_replace(
            '/["\']assets\/([^"\']+)["\']/',
            '"{{ asset(\'temas/' . $nomeTema . '/assets/$1\') }}"',
            $conteudo
        );
        
        return $conteudo;
    }

    private function converterHtmlParaBlade($temaViewsPath, $nomeTema)
    {
        // Encontrar todos os arquivos HTML
        $arquivosHtml = File::glob($temaViewsPath . '/*.html');
        
        foreach ($arquivosHtml as $arquivoHtml) {
            $nomeArquivo = basename($arquivoHtml, '.html');
            $arquivoBlade = $temaViewsPath . '/' . $nomeArquivo . '.blade.php';
            
            // Ler conteúdo HTML
            $conteudoHtml = File::get($arquivoHtml);
            
            // Converter HTML para Blade
            $conteudoBlade = $this->converterHtmlParaBladeConteudo($conteudoHtml, $nomeTema);
            
            // Criar arquivo Blade
            File::put($arquivoBlade, $conteudoBlade);
            
            // Remover arquivo HTML original
            File::delete($arquivoHtml);
        }
    }

    private function converterHtmlParaBladeConteudo($conteudoHtml, $nomeTema)
    {
        // Converter para usar o layout do tema
        $conteudoBlade = $this->adicionarExtendsLayout($conteudoHtml, $nomeTema);
        
        // Converter elementos HTML para sintaxe Blade
        $conteudoBlade = $this->converterElementosParaBlade($conteudoBlade);
        
        // Ajustar links e assets
        $conteudoBlade = $this->ajustarAssetsParaBlade($conteudoBlade, $nomeTema);
        
        return $conteudoBlade;
    }

    private function adicionarExtendsLayout($conteudoHtml, $nomeTema)
    {
        // Extrair conteúdo entre <body> e </body>
        preg_match('/<body[^>]*>(.*?)<\/body>/s', $conteudoHtml, $matches);
        $conteudoBody = isset($matches[1]) ? $matches[1] : $conteudoHtml;
        
        // Limpar o conteúdo
        $conteudoBody = trim($conteudoBody);
        
        // Criar estrutura Blade
        $bladeContent = "@extends('temas.{$nomeTema}.layouts.app')\n\n";
        $bladeContent .= "@section('content')\n";
        $bladeContent .= $conteudoBody . "\n";
        $bladeContent .= "@endsection\n";
        
        return $bladeContent;
    }

    private function converterElementosParaBlade($conteudo)
    {
        // Converter links estáticos para rotas Laravel
        $conteudo = preg_replace(
            '/href=["\']index\.html["\']/',
            'href="{{ route(\'home\') }}"',
            $conteudo
        );
        
        $conteudo = preg_replace(
            '/href=["\']about\.html["\']/',
            'href="{{ route(\'sobre\') }}"',
            $conteudo
        );
        
        $conteudo = preg_replace(
            '/href=["\']contact\.html["\']/',
            'href="{{ route(\'contato\') }}"',
            $conteudo
        );
        
        // Converter formulários para usar CSRF
        $conteudo = preg_replace(
            '/<form([^>]*)>/',
            '<form$1>@csrf',
            $conteudo
        );
        
        // Converter inputs para usar old() values
        $conteudo = preg_replace(
            '/<input([^>]*name=["\']([^"\']+)["\'][^>]*)>/',
            '<input$1 value="{{ old(\'$2\') }}">',
            $conteudo
        );
        
        // Converter textareas para usar old() values
        $conteudo = preg_replace(
            '/<textarea([^>]*name=["\']([^"\']+)["\'][^>]*)>([^<]*)<\/textarea>/',
            '<textarea$1>{{ old(\'$2\', \'$3\') }}</textarea>',
            $conteudo
        );
        
        return $conteudo;
    }

    private function ajustarAssetsParaBlade($conteudo, $nomeTema)
    {
        // Garantir que todos os assets usem asset()
        $conteudo = preg_replace(
            '/src=["\']{{ asset\(["\']temas\/' . preg_quote($nomeTema) . '\/assets\/([^"\']+)["\']\) }}/',
            'src="{{ asset(\'temas/' . $nomeTema . '/assets/$1\') }}"',
            $conteudo
        );
        
        $conteudo = preg_replace(
            '/href=["\']{{ asset\(["\']temas\/' . preg_quote($nomeTema) . '\/assets\/([^"\']+)["\']\) }}/',
            'href="{{ asset(\'temas/' . $nomeTema . '/assets/$1\') }}"',
            $conteudo
        );
        
        return $conteudo;
    }
}
