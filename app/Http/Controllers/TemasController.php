<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
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

        // Preparar regras de validação dinâmicas para páginas HTML
        $validationRules = [
            'nome_tema' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]+$/',
            'arquivo_zip' => 'required|file|max:10485760', // 10MB = 10 * 1024 * 1024 bytes
            'arquivo_paginas' => 'nullable|file|max:10485760', // 10MB = 10 * 1024 * 1024 bytes
            'codigo_head' => 'nullable|string|max:10000',
            'codigo_nav' => 'nullable|string',
            'codigo_footer' => 'nullable|string|max:10000',
            'codigo_scripts' => 'nullable|string|max:10000',
            'tem_paginas_html_diferente' => 'nullable|boolean',
            'numero_paginas_html' => 'nullable|integer|min:1|max:10'
        ];
        
        // Adicionar validações dinâmicas para páginas HTML
        if ($request->input('tem_paginas_html_diferente')) {
            $numeroPaginas = (int) $request->input('numero_paginas_html', 0);
            for ($i = 1; $i <= $numeroPaginas; $i++) {
                $validationRules["nome_pagina_{$i}"] = 'required|string|max:100';
                $validationRules["codigo_html_{$i}"] = 'required|string|max:50000';
            }
        }
        
        $request->validate($validationRules, [
            'nome_tema.required' => 'O nome do tema é obrigatório.',
            'nome_tema.regex' => 'O nome do tema deve conter apenas letras, números, hífens e underscores.',
            'arquivo_zip.required' => 'O arquivo ZIP dos assets é obrigatório.',
            'arquivo_zip.max' => 'O arquivo ZIP dos assets não pode ser maior que 10MB.',
            'arquivo_paginas.max' => 'O arquivo ZIP das páginas não pode ser maior que 10MB.',
            'codigo_head.max' => 'O código do head não pode ter mais que 10.000 caracteres.',
            'codigo_footer.max' => 'O código do footer não pode ter mais que 10.000 caracteres.',
            'codigo_scripts.max' => 'O código dos scripts não pode ter mais que 10.000 caracteres.',
            'numero_paginas_html.required' => 'Selecione o número de páginas com HTML diferente.',
            'numero_paginas_html.min' => 'O número mínimo de páginas é 1.',
            'numero_paginas_html.max' => 'O número máximo de páginas é 10.'
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
            
            // Processar páginas com HTML diferente (se especificadas)
            if ($request->input('tem_paginas_html_diferente')) {
                $this->criarPaginasHtmlDiferente($temaViewsPath, $request);
            }
            
            // Processar e modificar links nos arquivos do tema
            $this->processarLinksTema($temaPath, $temaViewsPath, $nomeTema);
            
            // Converter HTML para Blade e ajustar tema
            $this->converterHtmlParaBlade($temaViewsPath, $nomeTema);
            
            // Linkar formulários dinamicamente ao tema
            $this->linkarFormulariosAoTema($temaViewsPath, $nomeTema);
            
            // SEMPRE criar rotas dinâmicas (independente de ter arquivo de páginas)
            $this->criarRotasDinamicas($nomeTema, $temaViewsPath);
            
            // Substituir links .html pelas rotas corretas
            $this->substituirLinksHtml($nomeTema);
            
            // Registrar tema no banco de dados
            $this->registrarTemaNoBanco($nomeTema, $request);
            
            // Criar configurações iniciais para as páginas do tema
            $this->criarConfiguracoesPaginasTema($nomeTema, $temaViewsPath);
            
            // Manter main-Thema como ativo (não selecionar automaticamente o tema instalado)
            \Log::info("Tema {$nomeTema} instalado com sucesso, mantendo main-Thema como ativo");
            
            $mensagem = 'Tema "' . $nomeTema . '" instalado com sucesso! Assets processados, estrutura Blade criada, links .html substituídos pelas rotas corretas, HTML convertido para Blade, formulários linkados dinamicamente, rotas dinâmicas criadas. O tema main-Thema permanece ativo.';
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
        // Tratar main-Thema de forma especial
        if ($nomeTema === 'main-Thema') {
            $temaViewsPath = resource_path('views/main-Thema');
        } else {
            $temaPath = public_path('temas/' . $nomeTema);
            $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        }
        
        if (!File::exists($temaViewsPath)) {
            return back()->withErrors(['tema' => 'Tema não encontrado.']);
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
        // Tratar main-Thema de forma especial
        if ($nomeTema === 'main-Thema') {
            $temaViewsPath = resource_path('views/main-Thema');
            $viewPath = 'main-Thema.' . $pagina;
        } else {
            $temaViewsPath = resource_path('views/temas/' . $nomeTema);
            
            // Primeiro, tentar com o nome original da página
            $arquivoBlade = $temaViewsPath . '/' . $pagina . '.blade.php';
            $viewPath = 'temas.' . $nomeTema . '.' . $pagina;
            
            // Se não existir, tentar com o mapeamento
            if (!File::exists($arquivoBlade)) {
                $mapeamento = [
                    'home' => 'index',
                    'sobre' => 'about',
                    'contato' => 'contact'
                ];
                $arquivoReal = $mapeamento[$pagina] ?? $pagina;
                
                $arquivoBlade = $temaViewsPath . '/' . $arquivoReal . '.blade.php';
                $viewPath = 'temas.' . $nomeTema . '.' . $arquivoReal;
            }
        }
        
        if ($nomeTema !== 'main-Thema') {
            if (!File::exists($arquivoBlade)) {
                return response()->json(['error' => 'Página não encontrada'], 404);
            }
        } else {
            // Para main-Thema, verificar se a página existe
            $arquivoBlade = $temaViewsPath . '/' . $pagina . '.blade.php';
            if (!File::exists($arquivoBlade)) {
                return response()->json(['error' => 'Página não encontrada'], 404);
            }
        }

        try {
            // Renderizar a view Blade
            $conteudo = view($viewPath)->render();
            
            return response($conteudo, 200, [
                'Content-Type' => 'text/html; charset=utf-8'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao renderizar a página: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($nomeTema)
    {
        // Não permitir remover o main-Thema
        if ($nomeTema === 'main-Thema') {
            return back()->withErrors(['tema' => 'Não é possível remover o tema padrão do sistema.']);
        }
        
        $temaPath = public_path('temas/' . $nomeTema);
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        if (!File::exists($temaPath) && !File::exists($temaViewsPath)) {
            return back()->withErrors(['tema' => 'Tema não encontrado.']);
        }

        try {
            \Log::info("Iniciando deleção completa do tema: {$nomeTema}");
            
            // 1. Verificar se o tema deletado é o tema ativo
            $temaAtivo = \App\Helpers\ThemeHelper::getActiveTheme();
            $isTemaAtivo = ($temaAtivo === $nomeTema);
            
            // 2. Remover configurações do tema da tabela head_configs
            $configsRemovidas = \DB::table('head_configs')->where('tema', $nomeTema)->delete();
            \Log::info("Configurações removidas da tabela head_configs: {$configsRemovidas}");
            
            // 3. Remover rotas dinâmicas do banco
            $rotasRemovidas = \DB::table('rotas_dinamicas')->where('tema', $nomeTema)->delete();
            \Log::info("Rotas dinâmicas removidas do banco: {$rotasRemovidas}");
            
            // 4. Remover registro do tema da tabela temas
            $temaRemovido = \DB::table('temas')->where('slug', $nomeTema)->delete();
            \Log::info("Registro do tema removido da tabela temas: {$temaRemovido}");
            
            // 5. Remover entradas do arquivo de rotas dinâmicas
            $this->removerTemaDoArquivoRotas($nomeTema);
            
            // 6. Remover assets do tema
            if (File::exists($temaPath)) {
                File::deleteDirectory($temaPath);
                \Log::info("Assets removidos: {$temaPath}");
            }
            
            // 7. Remover views do tema
            if (File::exists($temaViewsPath)) {
                File::deleteDirectory($temaViewsPath);
                \Log::info("Views removidas: {$temaViewsPath}");
            }
            
            // 8. Se o tema deletado era o ativo, redefinir para main-Thema
            if ($isTemaAtivo) {
                $this->redefinirTemaAtivoParaMain();
            }
            
            // 9. Limpar cache do sistema
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            \Log::info("Cache do sistema limpo");
            
            \Log::info("Deleção completa do tema {$nomeTema} finalizada com sucesso");
            
            $mensagem = 'Tema "' . $nomeTema . '" removido completamente! ';
            $mensagem .= 'Assets, views, configurações, rotas dinâmicas e registros do banco removidos.';
            if ($isTemaAtivo) {
                $mensagem .= ' Tema ativo redefinido para main-Thema.';
            }
            
            return redirect()->route('dashboard.temas')->with('success', $mensagem);
        } catch (\Exception $e) {
            \Log::error("Erro ao remover tema {$nomeTema}: " . $e->getMessage());
            return back()->withErrors(['tema' => 'Erro ao remover o tema: ' . $e->getMessage()]);
        }
    }

    /**
     * Renderizar página dinâmica de tema
     */
    public function renderizarPaginaDinamica($tema, $pagina)
    {
        try {
            // Verificar se a rota dinâmica existe e está ativa
            $rotaDinamica = \DB::table('rotas_dinamicas')
                ->where('tema', $tema)
                ->where('pagina', $pagina)
                ->where('ativo', 1)
                ->first();

            if (!$rotaDinamica) {
                abort(404, 'Página não encontrada');
            }

            // Verificar se o tema existe (não precisa estar ativo para rotas dinâmicas)
            $temaExiste = \App\Helpers\ThemeHelper::themeExists($tema);
            if (!$temaExiste) {
                abort(404, 'Tema não encontrado');
            }

            // Verificar se o arquivo da página existe
            $temaViewsPath = resource_path('views/temas/' . $tema);
            $arquivoBlade = $temaViewsPath . '/' . $pagina . '.blade.php';
            
            if (!File::exists($arquivoBlade)) {
                abort(404, 'Arquivo da página não encontrado');
            }

            // Renderizar a página
            $viewPath = 'temas.' . $tema . '.' . $pagina;
            return view($viewPath);

        } catch (\Exception $e) {
            \Log::error("Erro ao renderizar página dinâmica {$tema}/{$pagina}: " . $e->getMessage());
            abort(500, 'Erro interno do servidor');
        }
    }

    public function select($nomeTema)
    {
        try {
            // Log da tentativa de seleção
            \Log::info("Tentativa de seleção do tema: {$nomeTema}");
            
            // Verificar se o tema existe
            if ($nomeTema === 'main-Thema') {
                // Para main-Thema, verificar se existe em views/main-Thema
                $temaViewsPath = resource_path('views/main-Thema');
                if (!File::exists($temaViewsPath)) {
                    \Log::error("Tema main-Thema não encontrado em: {$temaViewsPath}");
                    return back()->withErrors(['tema' => 'Tema main-Thema não encontrado!']);
                }
            } else {
                // Para outros temas, verificar em public/temas e views/temas
                $temaPath = public_path('temas/' . $nomeTema);
                $temaViewsPath = resource_path('views/temas/' . $nomeTema);
                
                if (!File::exists($temaPath)) {
                    \Log::error("Assets do tema não encontrados em: {$temaPath}");
                    return back()->withErrors(['tema' => 'Assets do tema não encontrados!']);
                }
                
                if (!File::exists($temaViewsPath)) {
                    \Log::error("Views do tema não encontradas em: {$temaViewsPath}");
                    return back()->withErrors(['tema' => 'Views do tema não encontradas!']);
                }
            }
            
            // Salvar o tema selecionado em um arquivo de configuração
            $configPath = config_path('tema_principal.php');
            $configContent = "<?php\n\nreturn [\n    'tema_principal' => '{$nomeTema}',\n    'selecionado_em' => '" . now() . "',\n];\n";
            
            // Tentar salvar o arquivo com diferentes abordagens
            $saved = false;
            
            // Primeira tentativa: salvar diretamente
            if (File::put($configPath, $configContent)) {
                $saved = true;
            } else {
                // Segunda tentativa: criar arquivo temporário e mover
                $tempPath = storage_path('app/temp_tema_principal.php');
                if (File::put($tempPath, $configContent)) {
                    if (File::move($tempPath, $configPath)) {
                        $saved = true;
                    } else {
                        File::delete($tempPath);
                    }
                }
            }
            
            if (!$saved) {
                \Log::error("Erro ao salvar configuração do tema em: {$configPath}");
                return back()->withErrors(['tema' => 'Erro ao salvar configuração do tema! Verifique as permissões da pasta config.']);
            }
            
            \Log::info("Configuração do tema salva com sucesso: {$nomeTema}");
            
            // Se não for main-Thema, verificar se os formulários estão linkados
            if ($nomeTema !== 'main-Thema') {
                $this->verificarELinkarFormularios($temaViewsPath, $nomeTema);
            }
            
            // Limpar cache para garantir que as mudanças sejam aplicadas
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            
            \Log::info("Tema selecionado com sucesso: {$nomeTema}");
            
            return redirect()->route('dashboard.temas')->with('success', 'Tema "' . $nomeTema . '" selecionado como tema principal do sistema!');
            
        } catch (\Exception $e) {
            \Log::error("Erro ao selecionar tema {$nomeTema}: " . $e->getMessage());
            return back()->withErrors(['tema' => 'Erro ao selecionar o tema: ' . $e->getMessage()]);
        }
    }

    public function editHome()
    {
        // Redirecionar para a página home do site
        return redirect()->route('home');
    }

    public function editAbout()
    {
        // Redirecionar para a página sobre do site
        return redirect()->route('sobre');
    }

    public function editContact()
    {
        // Redirecionar para a página contato do site
        return redirect()->route('contato');
    }

    private function getTemasList()
    {
        $temasPath = public_path('temas');
        $temas = [];
        
        // Verificar qual tema está ativo
        $temaAtivo = null;
        $configPath = config_path('tema_principal.php');
        if (File::exists($configPath)) {
            $config = include $configPath;
            $temaAtivo = $config['tema_principal'] ?? null;
        }

        // Adicionar main-Thema à lista
        $mainThemaPath = resource_path('views/main-Thema');
        if (File::exists($mainThemaPath)) {
            $mainThemaAssetsPath = public_path('assets'); // Assumindo que main-Thema usa assets globais
            
            // Contar páginas do main-Thema
            $arquivosPaginas = collect(File::files($mainThemaPath))
                ->filter(function($arquivo) {
                    $nome = $arquivo->getFilename();
                    // Contar apenas arquivos HTML e Blade na raiz (excluindo auth e layouts)
                    return (str_ends_with($nome, '.html') || str_ends_with($nome, '.blade.php')) 
                           && !str_contains($arquivo->getPathname(), 'auth') 
                           && !str_contains($arquivo->getPathname(), 'layouts');
                });
            
            $paginasDisponiveis = $arquivosPaginas->map(function($arquivo) {
                return basename($arquivo->getFilename(), '.blade.php');
            })->values()->toArray();
            
            $temas[] = [
                'nome' => 'main-Thema',
                'caminho' => $mainThemaPath,
                'arquivos' => File::exists($mainThemaAssetsPath) ? count(File::allFiles($mainThemaAssetsPath)) : 0,
                'tamanho' => File::exists($mainThemaAssetsPath) ? $this->getDirectorySize($mainThemaAssetsPath) : '0 B',
                'criado_em' => date('d/m/Y H:i', filemtime($mainThemaPath)),
                'tem_paginas' => count($paginasDisponiveis) > 0,
                'arquivos_paginas' => count($paginasDisponiveis),
                'paginas_disponiveis' => $paginasDisponiveis,
                'ativo' => 'main-Thema' === $temaAtivo,
                'is_main' => true
            ];
        }

        // Adicionar temas instalados
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
                        $nome = basename($arquivo->getFilename(), '.blade.php');
                        // Mapear nomes em inglês para português
                        $mapeamento = [
                            'index' => 'home',
                            'about' => 'sobre',
                            'contact' => 'contato'
                        ];
                        return $mapeamento[$nome] ?? $nome;
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
                    'paginas_disponiveis' => $paginasDisponiveis,
                    'ativo' => $nomeTema === $temaAtivo,
                    'is_main' => false
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
        
        $layout = '@php
    // Detectar página atual baseada na rota
    $currentPage = \'global\';
    
    // Rotas principais
    if (request()->routeIs(\'home\')) {
        $currentPage = \'home\';
    } elseif (request()->routeIs(\'sobre\')) {
        $currentPage = \'sobre\';
    } elseif (request()->routeIs(\'contato\')) {
        $currentPage = \'contato\';
    } elseif (request()->routeIs(\'login\')) {
        $currentPage = \'login\';
    }
    
    // Rotas dinâmicas do tema
    $routeName = request()->route() ? request()->route()->getName() : \'\';
    if (str_starts_with($routeName, \'tema.' . $nomeTema . '.\')) {
        $currentPage = str_replace(\'tema.' . $nomeTema . '.\', \'\', $routeName);
    }
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
@include(\'temas.' . $nomeTema . '.inc.head\')

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
        
        // Substituir links de imagens (assets/)
        $conteudo = preg_replace(
            '/src=["\']assets\/([^"\']+)["\']/',
            'src="{{ asset(\'temas/' . $nomeTema . '/assets/$1\') }}"',
            $conteudo
        );
        
        // Substituir links de imagens (images/) - CORREÇÃO
        $conteudo = preg_replace(
            '/src=["\']images\/([^"\']+)["\']/',
            'src="{{ asset(\'temas/' . $nomeTema . '/assets/images/$1\') }}"',
            $conteudo
        );
        
        // Substituir background-image em CSS inline (assets/)
        $conteudo = preg_replace(
            '/background-image:\s*url\(["\']?assets\/([^"\']+)["\']?\)/',
            'background-image: url({{ asset(\'temas/' . $nomeTema . '/assets/$1\') }})',
            $conteudo
        );
        
        // Substituir background-image em CSS inline (images/) - CORREÇÃO
        $conteudo = preg_replace(
            '/background-image:\s*url\(["\']?images\/([^"\']+)["\']?\)/',
            'background-image: url({{ asset(\'temas/' . $nomeTema . '/assets/images/$1\') }})',
            $conteudo
        );
        
        return $conteudo;
    }

    private function substituirLinksCss($conteudo, $nomeTema)
    {
        // Substituir URLs em CSS (assets/)
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
        
        // Substituir URLs em CSS (images/) - CORREÇÃO
        $conteudo = preg_replace(
            '/url\(["\']?\.\.\/\.\.\/images\/([^"\']+)["\']?\)/',
            'url({{ asset(\'temas/' . $nomeTema . '/assets/images/$1\') }})',
            $conteudo
        );
        
        $conteudo = preg_replace(
            '/url\(["\']?\.\.\/images\/([^"\']+)["\']?\)/',
            'url({{ asset(\'temas/' . $nomeTema . '/assets/images/$1\') }})',
            $conteudo
        );
        
        $conteudo = preg_replace(
            '/url\(["\']?images\/([^"\']+)["\']?\)/',
            'url({{ asset(\'temas/' . $nomeTema . '/assets/images/$1\') }})',
            $conteudo
        );
        
        return $conteudo;
    }

    private function substituirLinksJs($conteudo, $nomeTema)
    {
        // Substituir URLs em JavaScript (assets/)
        $conteudo = preg_replace(
            '/["\']assets\/([^"\']+)["\']/',
            '"{{ asset(\'temas/' . $nomeTema . '/assets/$1\') }}"',
            $conteudo
        );
        
        // Substituir URLs em JavaScript (images/) - CORREÇÃO
        $conteudo = preg_replace(
            '/["\']images\/([^"\']+)["\']/',
            '"{{ asset(\'temas/' . $nomeTema . '/assets/images/$1\') }}"',
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
        
        // Converter formulários para usar CSRF e corrigir method/action
        $conteudo = $this->corrigirFormulariosExistentes($conteudo);
        
        // NOVO: Detectar inputs soltos e criar formulário automaticamente
        $conteudo = $this->criarFormularioParaInputsSolto($conteudo);
        
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

    /**
     * Criar configurações de meta tags dinâmicas para páginas do tema
     */
    private function criarConfiguracoesMetaTags($nomeTema, $arquivosBlade)
    {
        $temaAtivo = $nomeTema;
        
        // Lista de páginas padrão para criar configurações
        $paginasPadrao = [
            'home' => [
                'meta_title' => 'Início - ' . ucfirst($nomeTema),
                'meta_description' => 'Bem-vindo ao ' . ucfirst($nomeTema) . '. Página inicial com informações sobre nossos serviços.',
                'meta_keywords' => strtolower($nomeTema) . ', início, home, página principal'
            ],
            'contato' => [
                'meta_title' => 'Contato - ' . ucfirst($nomeTema),
                'meta_description' => 'Entre em contato conosco. Estamos prontos para atender suas necessidades.',
                'meta_keywords' => strtolower($nomeTema) . ', contato, telefone, email, suporte'
            ],
            'sobre' => [
                'meta_title' => 'Sobre - ' . ucfirst($nomeTema),
                'meta_description' => 'Conheça mais sobre o ' . ucfirst($nomeTema) . ' e nossa história.',
                'meta_keywords' => strtolower($nomeTema) . ', sobre, empresa, história'
            ]
        ];

        // Detectar páginas existentes nos arquivos blade
        $paginasDetectadas = [];
        foreach ($arquivosBlade as $arquivo) {
            $nomeArquivo = str_replace('.blade.php', '', $arquivo->getFilename());
            $paginasDetectadas[] = $nomeArquivo;
        }

        // Criar configurações para páginas detectadas
        foreach ($paginasDetectadas as $pagina) {
            // Verificar se já existe configuração para esta página
            $configExistente = DB::table('head_configs')
                ->where('pagina', $pagina)
                ->where('tema', $temaAtivo)
                ->first();

            if (!$configExistente) {
                // Usar configuração padrão se disponível, senão criar genérica
                $configPadrao = $paginasPadrao[$pagina] ?? [
                    'meta_title' => ucfirst($pagina) . ' - ' . ucfirst($nomeTema),
                    'meta_description' => 'Página ' . ucfirst($pagina) . ' do tema ' . ucfirst($nomeTema) . '. Configure as meta tags específicas desta página.',
                    'meta_keywords' => strtolower($pagina) . ', ' . strtolower($nomeTema) . ', página'
                ];

                DB::table('head_configs')->insert([
                    'pagina' => $pagina,
                    'tema' => $temaAtivo,
                    'meta_title' => $configPadrao['meta_title'],
                    'meta_description' => $configPadrao['meta_description'],
                    'meta_keywords' => $configPadrao['meta_keywords'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                \Log::info("Configuração de meta tags criada para página: {$pagina} do tema: {$temaAtivo}");
            }
        }

        // Criar configuração global se não existir
        $configGlobalExistente = DB::table('head_configs')
            ->where('pagina', 'global')
            ->where('tema', $temaAtivo)
            ->first();

        if (!$configGlobalExistente) {
            DB::table('head_configs')->insert([
                'pagina' => 'global',
                'tema' => $temaAtivo,
                'meta_title' => ucfirst($nomeTema) . ' - Site Profissional',
                'meta_description' => 'Site profissional do tema ' . ucfirst($nomeTema) . '. Descubra nossos serviços e entre em contato.',
                'meta_keywords' => strtolower($nomeTema) . ', site, profissional, serviços',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \Log::info("Configuração global de meta tags criada para tema: {$temaAtivo}");
        }
    }

    /**
     * Corrigir formulários existentes
     */
    private function corrigirFormulariosExistentes($conteudo)
    {
        // Corrigir method="get" para method="POST"
        $conteudo = preg_replace(
            '/<form([^>]*method=["\']get["\'][^>]*)>/i',
            '<form$1 method="POST">',
            $conteudo
        );
        
        // Adicionar action se não existir
        $conteudo = preg_replace_callback(
            '/<form([^>]*)>/i',
            function($matches) {
                $atributos = $matches[1];
                
                // Se não tem action, adicionar
                if (!preg_match('/action=["\'][^"\']*["\']/', $atributos)) {
                    $atributos .= ' action="{{ route(\'contato.enviar\') }}"';
                }
                
                // Se não tem @csrf, adicionar
                $formularioCompleto = '<form' . $atributos . '>@csrf';
                
                return $formularioCompleto;
            },
            $conteudo
        );
        
        // Remover @csrf duplicados
        $conteudo = preg_replace('/@csrf@csrf/', '@csrf', $conteudo);
        
        // Corrigir values duplicados nos inputs
        $conteudo = preg_replace(
            '/value="{{ old\([^)]+\) }}" value="{{ old\([^)]+\) }}"/',
            'value="{{ old(\'$1\') }}"',
            $conteudo
        );
        
        // Remover values duplicados mais complexos
        $conteudo = preg_replace(
            '/value="{{ old\([^)]+\) }}"\s*value="{{ old\([^)]+\) }}"/',
            'value="{{ old(\'$1\') }}"',
            $conteudo
        );
        
        // Remover values duplicados simples
        $conteudo = preg_replace(
            '/(value="[^"]*")\s*\1/',
            '$1',
            $conteudo
        );
        
        return $conteudo;
    }

    /**
     * Criar formulário automaticamente para inputs soltos
     */
    private function criarFormularioParaInputsSolto($conteudo)
    {
        // Verificar se já existe um formulário na página
        if (preg_match('/<form[^>]*>/i', $conteudo)) {
            return $conteudo; // Já tem formulário, não fazer nada
        }
        
        // Detectar se há inputs que precisam de um formulário
        $temInputs = preg_match('/<input[^>]*>/i', $conteudo);
        $temTextareas = preg_match('/<textarea[^>]*>/i', $conteudo);
        $temBotaoSubmit = preg_match('/<button[^>]*type=["\']submit["\'][^>]*>/i', $conteudo);
        
        if (!$temInputs && !$temTextareas) {
            return $conteudo; // Não há inputs, não precisa de formulário
        }
        
        // Estratégia 1: Procurar por grupos de inputs com classe "input-area"
        $pattern1 = '/(<div[^>]*class="[^"]*input-area[^"]*"[^>]*>.*?<\/div>)/s';
        $conteudo = $this->processarGrupoInputs($conteudo, $pattern1);
        
        // Estratégia 2: Procurar por inputs soltos sem div wrapper
        if (!preg_match('/<form[^>]*>/i', $conteudo)) {
            $pattern2 = '/(<input[^>]*>.*?<button[^>]*type=["\']submit["\'][^>]*>.*?<\/button>)/s';
            $conteudo = $this->processarGrupoInputs($conteudo, $pattern2);
        }
        
        // Estratégia 3: Procurar por qualquer grupo de inputs próximos
        if (!preg_match('/<form[^>]*>/i', $conteudo)) {
            $pattern3 = '/(<input[^>]*>.*?<textarea[^>]*>.*?<\/textarea>.*?<button[^>]*type=["\']submit["\'][^>]*>.*?<\/button>)/s';
            $conteudo = $this->processarGrupoInputs($conteudo, $pattern3);
        }
        
        // Estratégia 4: Para casos específicos como o tema finazze (inputs em divs separadas)
        if (!preg_match('/<form[^>]*>/i', $conteudo)) {
            $conteudo = $this->processarInputsEmDivsSeparadas($conteudo);
        }
        
        return $conteudo;
    }
    
    /**
     * Processar grupo de inputs e criar formulário
     */
    private function processarGrupoInputs($conteudo, $pattern)
    {
        if (preg_match_all($pattern, $conteudo, $matches, PREG_OFFSET_CAPTURE)) {
            // Processar do último para o primeiro para não afetar os offsets
            for ($i = count($matches[0]) - 1; $i >= 0; $i--) {
                $match = $matches[0][$i];
                $inicioOffset = $match[1];
                $conteudoMatch = $match[0];
                $fimOffset = $inicioOffset + strlen($conteudoMatch);
                
                // Verificar se já está dentro de um formulário
                $conteudoAntes = substr($conteudo, 0, $inicioOffset);
                $conteudoDepois = substr($conteudo, $fimOffset);
                
                if (preg_match('/<form[^>]*>.*$/s', $conteudoAntes) && 
                    preg_match('/^.*<\/form>/s', $conteudoDepois)) {
                    continue; // Já está dentro de um formulário
                }
                
                // Adicionar atributos name aos inputs que não têm
                $conteudoMatch = $this->adicionarAtributosName($conteudoMatch);
                
                // Criar o formulário completo
                $formularioCompleto = '<form method="POST" action="{{ route(\'contato.enviar\') }}">@csrf' . "\n" . 
                                     $conteudoMatch . "\n" . 
                                     '</form>';
                
                // Substituir o conteúdo original pelo formulário
                $conteudo = substr_replace($conteudo, $formularioCompleto, $inicioOffset, strlen($conteudoMatch));
                
                \Log::info("Formulário criado automaticamente para grupo de inputs");
                break; // Processar apenas o primeiro grupo encontrado
            }
        }
        
        return $conteudo;
    }
    
    /**
     * Adicionar atributos name aos inputs que não têm
     */
    private function adicionarAtributosName($conteudo)
    {
        // Adicionar name aos inputs que não têm
        $conteudo = preg_replace_callback(
            '/<input([^>]*type=["\']text["\'][^>]*)>/i',
            function($matches) {
                $atributos = $matches[1];
                if (!preg_match('/name=["\'][^"\']*["\']/', $atributos)) {
                    $atributos .= ' name="nome"';
                }
                return '<input' . $atributos . '>';
            },
            $conteudo
        );
        
        $conteudo = preg_replace_callback(
            '/<input([^>]*type=["\']email["\'][^>]*)>/i',
            function($matches) {
                $atributos = $matches[1];
                if (!preg_match('/name=["\'][^"\']*["\']/', $atributos)) {
                    $atributos .= ' name="email"';
                }
                return '<input' . $atributos . '>';
            },
            $conteudo
        );
        
        $conteudo = preg_replace_callback(
            '/<input([^>]*type=["\']number["\'][^>]*)>/i',
            function($matches) {
                $atributos = $matches[1];
                if (!preg_match('/name=["\'][^"\']*["\']/', $atributos)) {
                    $atributos .= ' name="telefone"';
                }
                return '<input' . $atributos . '>';
            },
            $conteudo
        );
        
        $conteudo = preg_replace_callback(
            '/<textarea([^>]*)>/i',
            function($matches) {
                $atributos = $matches[1];
                if (!preg_match('/name=["\'][^"\']*["\']/', $atributos)) {
                    $atributos .= ' name="mensagem"';
                }
                return '<textarea' . $atributos . '>';
            },
            $conteudo
        );
        
        return $conteudo;
    }
    
    /**
     * Processar inputs que estão em divs separadas (caso específico do tema finazze)
     */
    private function processarInputsEmDivsSeparadas($conteudo)
    {
        // Estratégia mais simples: encontrar a div.row que contém inputs e botão submit
        // e envolver apenas essa seção em um formulário
        
        // Procurar por div.row que contém inputs e botão submit
        $pattern = '/(<div class="row">.*?<button[^>]*type=["\']submit["\'][^>]*>.*?<\/button>.*?<\/div>)/s';
        
        if (preg_match($pattern, $conteudo, $matches, PREG_OFFSET_CAPTURE)) {
            $inicioOffset = $matches[0][1];
            $conteudoFormulario = $matches[0][0];
            
            // Adicionar atributos name aos inputs
            $conteudoFormulario = $this->adicionarAtributosName($conteudoFormulario);
            
            // Criar o formulário completo
            $formularioCompleto = '<form method="POST" action="{{ route(\'contato.enviar\') }}">@csrf' . "\n" . 
                                 $conteudoFormulario . "\n" . 
                                 '</form>';
            
            // Substituir o conteúdo original pelo formulário
            $conteudo = substr_replace($conteudo, $formularioCompleto, $inicioOffset, strlen($matches[0][0]));
            
            \Log::info("Formulário criado automaticamente para inputs em divs separadas");
        }
        
        return $conteudo;
    }
    
    /**
     * Encontrar o início do contexto (div pai)
     */
    private function encontrarInicioContexto($conteudoAntes)
    {
        // Procurar por divs que podem ser o container do formulário
        $patterns = [
            '/<div[^>]*class="[^"]*row[^"]*"[^>]*>$/s',
            '/<div[^>]*class="[^"]*contact-header-area[^"]*"[^>]*>$/s',
            '/<div[^>]*class="[^"]*col-lg-[^"]*"[^>]*>$/s'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $conteudoAntes, $matches, PREG_OFFSET_CAPTURE)) {
                return $matches[0][1];
            }
        }
        
        return 0; // Se não encontrar, usar o início
    }
    
    /**
     * Encontrar o fim do contexto (div pai)
     */
    private function encontrarFimContexto($conteudoDepois)
    {
        // Procurar por fechamento de divs que podem ser o container do formulário
        $patterns = [
            '/^<\/div>/s',
            '/^<\/div>\s*<\/div>/s'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $conteudoDepois, $matches, PREG_OFFSET_CAPTURE)) {
                return strlen($matches[0][0]);
            }
        }
        
        return 0; // Se não encontrar, usar o fim
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

    /**
     * Selecionar automaticamente o tema instalado como ativo
     */
    private function selecionarTemaAutomaticamente($nomeTema)
    {
        try {
            // Salvar o tema selecionado em um arquivo de configuração
            $configPath = config_path('tema_principal.php');
            $configContent = "<?php\n\nreturn [\n    'tema_principal' => '{$nomeTema}',\n    'selecionado_em' => '" . now() . "',\n];\n";
            
            if (File::put($configPath, $configContent)) {
                \Log::info("Tema {$nomeTema} selecionado automaticamente como ativo");
            } else {
                \Log::warning("Erro ao selecionar tema {$nomeTema} automaticamente");
            }
        } catch (\Exception $e) {
            \Log::error("Erro ao selecionar tema automaticamente: " . $e->getMessage());
        }
    }

    /**
     * Registrar tema no banco de dados
     */
    private function registrarTemaNoBanco($nomeTema, $request)
    {
        try {
            // Verificar se o tema já existe
            $temaExistente = \DB::table('temas')
                ->where('slug', $nomeTema)
                ->first();

            if (!$temaExistente) {
                // Criar slug baseado no nome do tema
                $slug = strtolower(str_replace([' ', '_', '-'], '-', $nomeTema));
                
                // Registrar tema no banco
                \DB::table('temas')->insert([
                    'nome' => ucfirst(str_replace(['-', '_'], ' ', $nomeTema)),
                    'slug' => $slug,
                    'preview_path' => null,
                    'arquivo_path' => $nomeTema,
                    'ativo' => 0, // Não ativar automaticamente
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                \Log::info("Tema {$nomeTema} registrado no banco de dados");
            } else {
                \Log::info("Tema {$nomeTema} já existe no banco de dados");
            }
        } catch (\Exception $e) {
            \Log::error("Erro ao registrar tema {$nomeTema} no banco: " . $e->getMessage());
        }
    }

    /**
     * Criar rotas dinamicamente para TODAS as páginas do tema
     */
    private function criarRotasDinamicas($nomeTema, $temaViewsPath)
    {
        try {
            // Obter todas as páginas do tema
            $paginas = collect(File::files($temaViewsPath))
                ->filter(function($arquivo) {
                    $nome = $arquivo->getFilename();
                    return str_ends_with($nome, '.blade.php') && 
                           !str_contains($arquivo->getPathname(), 'inc') &&
                           !str_contains($arquivo->getPathname(), 'layouts');
                })
                ->map(function($arquivo) {
                    // Normalizar nome da página para minúsculas
                    return strtolower(basename($arquivo->getFilename(), '.blade.php'));
                });

            // Mapeamento de rotas especiais (páginas que já têm rotas fixas)
            // Incluir variações em maiúsculas e minúsculas
            $rotasEspeciais = [
                'index' => '/',        // Página inicial
                'about' => '/sobre',   // Página sobre
                'contact' => '/contato', // Página contato
                'sobre' => '/sobre',   // Página sobre (nome em português)
                'contato' => '/contato', // Página contato (nome em português)
                'home' => '/',         // Página home também mapeia para /
                // Variações em maiúsculas
                'Index' => '/',
                'About' => '/sobre',
                'Contact' => '/contato',
                'Sobre' => '/sobre',
                'Contato' => '/contato',
                'Home' => '/'
            ];

            // Separar páginas especiais das demais
            $paginasEspeciais = $paginas->filter(function($pagina) use ($rotasEspeciais) {
                return array_key_exists($pagina, $rotasEspeciais);
            });

            $paginasNormais = $paginas->filter(function($pagina) use ($rotasEspeciais) {
                return !array_key_exists($pagina, $rotasEspeciais);
            });

            // Criar rotas dinâmicas APENAS para páginas que NÃO conflitam com rotas principais
            if ($paginas->count() > 0) {
                // Filtrar páginas que conflitam com rotas principais
                $rotasPrincipais = ['/', '/sobre', '/contato', '/login'];
                $paginasSemConflito = $paginas->filter(function($pagina) use ($rotasEspeciais, $rotasPrincipais) {
                    $rota = $rotasEspeciais[$pagina] ?? '/' . $pagina;
                    return !in_array($rota, $rotasPrincipais);
                });
                
                // Criar rotas dinâmicas no banco de dados (apenas páginas sem conflito)
                $this->criarRotasDinamicasNoBanco($nomeTema, $paginasSemConflito, $rotasEspeciais);
                
                // Criar arquivo de rotas dinâmicas (para compatibilidade)
                $this->criarArquivoRotasDinamicas($nomeTema, $paginasSemConflito, $rotasEspeciais);
                
                // Recarregar rotas dinâmicas
                $this->recarregarRotasDinamicas();
                
                \Log::info("Rotas dinâmicas criadas para tema {$nomeTema}: " . $paginasSemConflito->implode(', '));
                \Log::info("Páginas com conflito (não criadas): " . $paginas->diff($paginasSemConflito)->implode(', '));
            }

        } catch (\Exception $e) {
            \Log::error("Erro ao criar rotas dinâmicas: " . $e->getMessage());
        }
    }

    /**
     * Criar rotas dinâmicas no banco de dados
     */
    private function criarRotasDinamicasNoBanco($nomeTema, $paginas, $rotasEspeciais = [])
    {
        try {
            foreach ($paginas as $pagina) {
                // Normalizar nome da página para minúsculas
                $paginaNormalizada = strtolower($pagina);
                
                // Verificar se a rota já existe
                $rotaExistente = \DB::table('rotas_dinamicas')
                    ->where('tema', $nomeTema)
                    ->where('pagina', $paginaNormalizada)
                    ->first();

                if (!$rotaExistente) {
                    // Determinar a rota baseada no tipo de página
                    $rota = isset($rotasEspeciais[$pagina]) ? $rotasEspeciais[$pagina] : '/' . $paginaNormalizada;
                    $nomeRota = $paginaNormalizada;
                    
                    // Para páginas especiais, usar um nome de rota diferente
                    if (isset($rotasEspeciais[$pagina])) {
                        $nomeRota = $paginaNormalizada . '_tema';
                    }

                    // Criar nova rota dinâmica
                    \DB::table('rotas_dinamicas')->insert([
                        'tema' => $nomeTema,
                        'pagina' => $paginaNormalizada,
                        'rota' => $rota,
                        'nome_rota' => $nomeRota,
                        'controller' => 'TemasController',
                        'metodo' => 'renderizarPaginaDinamica',
                        'ativo' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error("Erro ao criar rotas dinâmicas no banco: " . $e->getMessage());
        }
    }

    /**
     * Criar arquivo de rotas dinâmicas
     */
    private function criarArquivoRotasDinamicas($nomeTema, $paginas, $rotasEspeciais = [])
    {
        $rotasPath = base_path('routes/temas_dinamicas.php');
        
        // Ler rotas existentes se o arquivo existir
        $rotasExistentes = [];
        if (File::exists($rotasPath)) {
            try {
            $conteudo = File::get($rotasPath);
                // Verificar se o arquivo contém apenas PHP válido
                if (strpos($conteudo, '<?php') === 0 && strpos($conteudo, 'return') !== false) {
                    $rotasExistentes = include $rotasPath;
                } else {
                    // Se o arquivo não é válido, recriar
                    $rotasExistentes = [];
                }
            } catch (\Exception $e) {
                \Log::error("Erro ao ler arquivo de rotas dinâmicas: " . $e->getMessage());
                $rotasExistentes = [];
            }
        }

        // Adicionar novas rotas para o tema
        foreach ($paginas as $pagina) {
            // Normalizar nome da página para minúsculas
            $paginaNormalizada = strtolower($pagina);
            
            // Determinar a rota baseada no tipo de página
            $rota = isset($rotasEspeciais[$pagina]) ? $rotasEspeciais[$pagina] : '/' . $paginaNormalizada;
            $nomeRota = $paginaNormalizada;
            
            // Para páginas especiais, usar um nome de rota diferente
            if (isset($rotasEspeciais[$pagina])) {
                $nomeRota = $paginaNormalizada . '_tema';
            }
            
            // Evitar duplicatas
            if (!isset($rotasExistentes[$nomeTema][$nomeRota])) {
                $rotasExistentes[$nomeTema][$nomeRota] = [
                    'rota' => $rota,
                    'pagina' => $paginaNormalizada,
                    'criado_em' => now()->toDateTimeString()
                ];
            }
        }

        // Salvar arquivo de rotas
        try {
        $conteudo = "<?php\n\n// Rotas dinâmicas para temas\nreturn " . var_export($rotasExistentes, true) . ";\n";
        File::put($rotasPath, $conteudo);
            \Log::info("Arquivo de rotas dinâmicas salvo com sucesso para tema {$nomeTema}");
        } catch (\Exception $e) {
            \Log::error("Erro ao salvar arquivo de rotas dinâmicas: " . $e->getMessage());
        }
    }

    /**
     * Recarregar rotas dinâmicas
     */
    private function recarregarRotasDinamicas()
    {
        try {
            // Executar comando para recarregar rotas dinâmicas
            \Artisan::call('routes:reload-dynamic');
            \Log::info("Rotas dinâmicas recarregadas com sucesso");
        } catch (\Exception $e) {
            \Log::error("Erro ao recarregar rotas dinâmicas: " . $e->getMessage());
        }
    }

    /**
     * Remover tema do arquivo de rotas dinâmicas
     */
    private function removerTemaDoArquivoRotas($nomeTema)
    {
        try {
            $rotasPath = base_path('routes/temas_dinamicas.php');
            
            if (!File::exists($rotasPath)) {
                \Log::info("Arquivo de rotas dinâmicas não existe: {$rotasPath}");
                return;
            }
            
            // Ler rotas existentes
            $rotasExistentes = [];
            try {
                $conteudo = File::get($rotasPath);
                if (strpos($conteudo, '<?php') === 0 && strpos($conteudo, 'return') !== false) {
                    $rotasExistentes = include $rotasPath;
                }
            } catch (\Exception $e) {
                \Log::error("Erro ao ler arquivo de rotas dinâmicas: " . $e->getMessage());
                return;
            }
            
            // Remover o tema das rotas
            if (isset($rotasExistentes[$nomeTema])) {
                unset($rotasExistentes[$nomeTema]);
                \Log::info("Tema {$nomeTema} removido do arquivo de rotas dinâmicas");
            }
            
            // Salvar arquivo atualizado
            $conteudo = "<?php\n\n// Rotas dinâmicas para temas\nreturn " . var_export($rotasExistentes, true) . ";\n";
            File::put($rotasPath, $conteudo);
            
            \Log::info("Arquivo de rotas dinâmicas atualizado após remoção do tema {$nomeTema}");
        } catch (\Exception $e) {
            \Log::error("Erro ao remover tema {$nomeTema} do arquivo de rotas: " . $e->getMessage());
        }
    }
    
    /**
     * Criar páginas com HTML diferente
     */
    private function criarPaginasHtmlDiferente($temaViewsPath, $request)
    {
        try {
            $numeroPaginas = (int) $request->input('numero_paginas_html', 0);
            
            if ($numeroPaginas <= 0) {
                return;
            }
            
            \Log::info("Criando {$numeroPaginas} páginas com HTML diferente");
            
            for ($i = 1; $i <= $numeroPaginas; $i++) {
                $nomePagina = $request->input("nome_pagina_{$i}");
                $codigoHtml = $request->input("codigo_html_{$i}");
                
                if (empty($nomePagina) || empty($codigoHtml)) {
                    continue;
                }
                
                // Normalizar nome da página
                $nomePagina = strtolower(trim($nomePagina));
                $nomePagina = preg_replace('/[^a-z0-9_-]/', '', $nomePagina);
                
                if (empty($nomePagina)) {
                    continue;
                }
                
                // Converter HTML para Blade
                $codigoBlade = $this->converterHtmlParaBlade($codigoHtml, $request->input('nome_tema'));
                
                // Criar arquivo Blade
                $arquivoBlade = $temaViewsPath . '/' . $nomePagina . '.blade.php';
                File::put($arquivoBlade, $codigoBlade);
                
                \Log::info("Página '{$nomePagina}' criada com HTML diferente");
            }
            
            \Log::info("Páginas com HTML diferente criadas com sucesso");
        } catch (\Exception $e) {
            \Log::error("Erro ao criar páginas com HTML diferente: " . $e->getMessage());
        }
    }

    /**
     * Redefinir tema ativo para main-Thema
     */
    private function redefinirTemaAtivoParaMain()
    {
        try {
            $configPath = config_path('tema_principal.php');
            $configContent = "<?php\n\nreturn [\n    'tema_principal' => 'main-Thema',\n    'selecionado_em' => '" . now() . "',\n];\n";
            
            if (File::put($configPath, $configContent)) {
                \Log::info("Tema ativo redefinido para main-Thema");
            } else {
                \Log::error("Erro ao redefinir tema ativo para main-Thema");
            }
        } catch (\Exception $e) {
            \Log::error("Erro ao redefinir tema ativo: " . $e->getMessage());
        }
    }

    /**
     * Substituir links .html pelas rotas corretas do Laravel
     */
    private function substituirLinksHtml($nomeTema)
    {
        try {
            $temaViewsPath = resource_path('views/temas/' . $nomeTema);
            
            if (!File::exists($temaViewsPath)) {
                \Log::warning("Diretório do tema não encontrado: {$temaViewsPath}");
                return;
            }

            // Mapeamento de páginas para rotas
            $mapeamentoRotas = $this->getMapeamentoRotas($nomeTema);
            
            // Buscar todos os arquivos .blade.php do tema
            $arquivos = $this->getArquivosBlade($temaViewsPath);
            
            $totalLinksSubstituidos = 0;
            $arquivosProcessados = 0;

            foreach ($arquivos as $arquivo) {
                $conteudoOriginal = File::get($arquivo);
                $conteudoNovo = $this->substituirLinksNoArquivo($conteudoOriginal, $mapeamentoRotas);
                
                if ($conteudoOriginal !== $conteudoNovo) {
                    File::put($arquivo, $conteudoNovo);
                    $arquivosProcessados++;
                    
                    // Contar quantos links foram substituídos
                    $linksSubstituidos = $this->contarLinksSubstituidos($conteudoOriginal, $conteudoNovo);
                    $totalLinksSubstituidos += $linksSubstituidos;
                }
            }

            \Log::info("Links HTML substituídos para tema {$nomeTema}: {$totalLinksSubstituidos} links em {$arquivosProcessados} arquivos");

        } catch (\Exception $e) {
            \Log::error("Erro ao substituir links HTML para tema {$nomeTema}: " . $e->getMessage());
        }
    }

    /**
     * Obter mapeamento de páginas para rotas
     */
    private function getMapeamentoRotas($nomeTema)
    {
        $mapeamento = [];
        
        // Mapeamento de rotas principais (com verificação de existência)
        try {
            if (\Route::has('home')) {
                $mapeamento['index.html'] = route('home');
                $mapeamento['index'] = route('home');
                $mapeamento['home.html'] = route('home');
                $mapeamento['home'] = route('home');
            }
            
            if (\Route::has('sobre')) {
                $mapeamento['about.html'] = route('sobre');
                $mapeamento['about'] = route('sobre');
            }
            
            if (\Route::has('contato')) {
                $mapeamento['contact.html'] = route('contato');
                $mapeamento['contact'] = route('contato');
                $mapeamento['contato.html'] = route('contato');
                $mapeamento['contato'] = route('contato');
            }
        } catch (\Exception $e) {
            \Log::warning("Erro ao mapear rotas principais: " . $e->getMessage());
        }

        // Buscar rotas dinâmicas do banco de dados
        try {
            $rotasDinamicas = \DB::table('rotas_dinamicas')
                ->where('tema', $nomeTema)
                ->where('ativo', 1)
                ->get();

            foreach ($rotasDinamicas as $rotaDinamica) {
                $nomePagina = $rotaDinamica->pagina;
                $nomeRota = $rotaDinamica->nome_rota;
                $rota = $rotaDinamica->rota;
                
                // Tentar usar helper route() para rotas dinâmicas, se não existir usar URL direta
                try {
                    $rotaFinal = "{{ route('tema.{$nomeTema}.{$nomeRota}') }}";
                } catch (\Exception $e) {
                    // Se a rota não existe (conflito com rota principal), usar URL direta
                    $rotaFinal = $rota;
                }
                
                // Adicionar variações com .html
                $mapeamento[$nomePagina . '.html'] = $rotaFinal;
                $mapeamento[$nomePagina] = $rotaFinal;
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
                // href="/pagina" (sem .html)
                '/href=["\']\/' . preg_quote(str_replace('.html', '', $paginaHtml), '/') . '["\']/i',
                // href='/pagina' (sem .html)
                '/href=[\'"]\/' . preg_quote(str_replace('.html', '', $paginaHtml), '/') . '[\'"]/i',
                // href="http://localhost/pagina" (URL completa)
                '/href=["\']http:\/\/localhost\/' . preg_quote(str_replace('.html', '', $paginaHtml), '/') . '["\']/i',
                // href='http://localhost/pagina' (URL completa)
                '/href=[\'"]http:\/\/localhost\/' . preg_quote(str_replace('.html', '', $paginaHtml), '/') . '[\'"]/i',
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
     * Linkar formulários dinamicamente ao tema
     */
    private function linkarFormulariosAoTema($temaViewsPath, $nomeTema)
    {
        try {
            // Modificar arquivo head.blade.php
            $this->modificarArquivoHead($temaViewsPath, $nomeTema);
            
            // Modificar arquivo nav.blade.php
            $this->modificarArquivoNav($temaViewsPath, $nomeTema);
            
            // Modificar arquivo footer.blade.php
            $this->modificarArquivoFooter($temaViewsPath, $nomeTema);
            
            // Criar configurações de meta tags dinâmicas para "Páginas do Tema"
            $arquivosBlade = File::allFiles($temaViewsPath);
            $this->criarConfiguracoesMetaTags($nomeTema, $arquivosBlade);
            
            \Log::info("Formulários linkados dinamicamente ao tema {$nomeTema}");
            
        } catch (\Exception $e) {
            \Log::error("Erro ao linkar formulários ao tema {$nomeTema}: " . $e->getMessage());
        }
    }

    /**
     * Modificar arquivo head.blade.php para usar configurações dinâmicas
     */
    private function modificarArquivoHead($temaViewsPath, $nomeTema)
    {
        $headPath = $temaViewsPath . '/inc/head.blade.php';
        
        if (File::exists($headPath)) {
            $conteudo = File::get($headPath);
            
            // Verificar se já foi modificado
            if (strpos($conteudo, 'HeadHelper') !== false) {
                \Log::info("Arquivo head.blade.php já foi modificado para o tema {$nomeTema}");
                return;
            }
            
            // Adicionar title dinâmico se não existir
            if (strpos($conteudo, '<title>') === false) {
                $titleTag = '<title>{{ \\App\\Helpers\\HeadHelper::getMetaTitle($currentPage ?? \'global\', \'' . $nomeTema . '\') }}</title>';
                $conteudo = str_replace('<head>', '<head>' . "\n    " . $titleTag, $conteudo);
            } else {
                // Substituir title estático por dinâmico
                $conteudo = preg_replace(
                    '/<title>.*?<\/title>/s',
                    '<title>{{ \\App\\Helpers\\HeadHelper::getMetaTitle($currentPage ?? \'global\', \'' . $nomeTema . '\') }}</title>',
                    $conteudo
                );
            }
            
            // Adicionar meta tags dinâmicas após o title
            $metaTags = '
    <meta name="description" content="{{ \\App\\Helpers\\HeadHelper::getMetaDescription($currentPage ?? \'global\', \'' . $nomeTema . '\') }}">
    <meta name="keywords" content="{{ \\App\\Helpers\\HeadHelper::getMetaKeywords($currentPage ?? \'global\', \'' . $nomeTema . '\') }}">';
            
            $conteudo = preg_replace(
                '/(<title>.*?<\/title>)/s',
                '$1' . $metaTags,
                $conteudo
            );
            
            // Substituir favicon estático por dinâmico
            $conteudo = preg_replace(
                '/<link rel="shortcut icon"[^>]*>/',
                '@if(\\App\\Helpers\\HeadHelper::getFavicon($currentPage ?? \'global\'))
        <link rel="shortcut icon" href="{{ \\App\\Helpers\\HeadHelper::getFavicon($currentPage ?? \'global\') }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset(\'temas/' . $nomeTema . '/assets/img/logo/fav-logo1.png\') }}" type="image/x-icon">
    @endif',
                $conteudo
            );
            
            // Adicionar GTM head
            $gtmHead = '
    <!--===== GTM HEAD =======-->
    @if(\\App\\Helpers\\HeadHelper::getGtmHead($currentPage ?? \'global\'))
        {!! \\App\\Helpers\\HeadHelper::getGtmHead($currentPage ?? \'global\') !!}
    @endif';
            
            $conteudo = preg_replace(
                '/(<link rel="shortcut icon"[^>]*>)/s',
                '$1' . $gtmHead,
                $conteudo
            );
            
            File::put($headPath, $conteudo);
        }
    }

    /**
     * Modificar arquivo nav.blade.php para usar logo dinâmico
     */
    private function modificarArquivoNav($temaViewsPath, $nomeTema)
    {
        $navPath = $temaViewsPath . '/inc/nav.blade.php';
        
        if (File::exists($navPath)) {
            $conteudo = File::get($navPath);
            
            // Substituir logo estático por dinâmico
            $conteudo = preg_replace(
                '/<img src="[^"]*logo[^"]*"[^>]*>/',
                '@if(\\App\\Helpers\\HeadHelper::getLogo())
                              <img src="{{ \\App\\Helpers\\HeadHelper::getLogo() }}" alt="{{ \\App\\Helpers\\HeadHelper::getMetaTitle(\'global\') }}">
                          @else
                              <img src="{{ asset(\'temas/' . $nomeTema . '/assets/img/logo/logo1.png\') }}" alt="' . $nomeTema . '">
                          @endif',
                $conteudo
            );
            
            // Substituir links estáticos por rotas Laravel
            $conteudo = preg_replace('/href="index\.html"/', 'href="{{ route(\'home\') }}"', $conteudo);
            $conteudo = preg_replace('/href="about\.html"/', 'href="{{ route(\'sobre\') }}"', $conteudo);
            $conteudo = preg_replace('/href="contact\.html"/', 'href="{{ route(\'contato\') }}"', $conteudo);
            
            File::put($navPath, $conteudo);
        }
    }

    /**
     * Modificar arquivo footer.blade.php para usar configurações dinâmicas
     */
    private function modificarArquivoFooter($temaViewsPath, $nomeTema)
    {
        $footerPath = $temaViewsPath . '/inc/footer.blade.php';
        
        if (File::exists($footerPath)) {
            $conteudo = File::get($footerPath);
            
            // Substituir logo footer estático por dinâmico
            $conteudo = preg_replace(
                '/<img src="[^"]*logo[^"]*"[^>]*>/',
                '@if(\\App\\Helpers\\HeadHelper::getLogoFooter())
              <img src="{{ \\App\\Helpers\\HeadHelper::getLogoFooter() }}" alt="{{ \\App\\Helpers\\HeadHelper::getMetaTitle(\'global\') }}">
          @else
              <img src="{{ asset(\'temas/' . $nomeTema . '/assets/img/logo/logo1.png\') }}" alt="' . $nomeTema . '">
          @endif',
                $conteudo
            );
            
            // Substituir descrição estática por dinâmica
            $conteudo = preg_replace(
                '/<p>.*?<\/p>/s',
                '<p>{{ \\App\\Helpers\\HeadHelper::getDescricaoFooter() ?: \'We are committed to providing with the highest level of service expertise business and finance if you have any.\' }}</p>',
                $conteudo,
                1 // Apenas a primeira ocorrência
            );
            
            // Substituir redes sociais estáticas por dinâmicas
            $redesSociais = '
            @php $redesSociais = \\App\\Helpers\\HeadHelper::getRedesSociais(); @endphp
            @if($redesSociais[\'facebook\'])
                <li><a href="{{ $redesSociais[\'facebook\'] }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
            @endif
            @if($redesSociais[\'linkedin\'])
                <li><a href="{{ $redesSociais[\'linkedin\'] }}" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
            @endif
            @if($redesSociais[\'instagram\'])
                <li><a href="{{ $redesSociais[\'instagram\'] }}" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
            @endif
            @if($redesSociais[\'youtube\'])
                <li><a href="{{ $redesSociais[\'youtube\'] }}" target="_blank" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
            @endif';
            
            $conteudo = preg_replace(
                '/<ul>\s*<li><a href="[^"]*"><i class="fa-brands fa-facebook-f"><\/i><\/a><\/li>.*?<\/ul>/s',
                '<ul>' . $redesSociais . '</ul>',
                $conteudo
            );
            
            // Substituir copyright estático por dinâmico
            $conteudo = preg_replace(
                '/<p>© Copyright[^<]*<\/p>/',
                '<p>{{ \\App\\Helpers\\HeadHelper::getCopyrightFooter() ?: \'© Copyright 2025 - ' . $nomeTema . '. All Right Reserved\' }}</p>',
                $conteudo
            );
            
            File::put($footerPath, $conteudo);
        }
    }

    /**
     * Verificar se os formulários estão linkados e linkar se necessário
     */
    private function verificarELinkarFormularios($temaViewsPath, $nomeTema)
    {
        try {
            $headPath = $temaViewsPath . '/inc/head.blade.php';
            
            // Verificar se o arquivo head já está linkado (contém HeadHelper)
            if (File::exists($headPath)) {
                $conteudo = File::get($headPath);
                
                // Se não contém HeadHelper, significa que não está linkado
                if (strpos($conteudo, 'HeadHelper') === false) {
                    \Log::info("Formulários não estão linkados ao tema {$nomeTema}. Linkando automaticamente...");
                    $this->linkarFormulariosAoTema($temaViewsPath, $nomeTema);
                } else {
                    \Log::info("Formulários já estão linkados ao tema {$nomeTema}");
                }
            }
            
        } catch (\Exception $e) {
            \Log::error("Erro ao verificar linkagem dos formulários para o tema {$nomeTema}: " . $e->getMessage());
        }
    }
    
    /**
     * Criar configurações iniciais para as páginas do tema
     */
    private function criarConfiguracoesPaginasTema($nomeTema, $temaViewsPath)
    {
        try {
            // Obter todas as páginas do tema
            $paginas = collect(File::files($temaViewsPath))
                ->filter(function($arquivo) {
                    $nome = $arquivo->getFilename();
                    return str_ends_with($nome, '.blade.php') && 
                           !str_contains($arquivo->getPathname(), 'inc') &&
                           !str_contains($arquivo->getPathname(), 'layouts') &&
                           !str_contains($arquivo->getPathname(), 'auth');
                })
                ->map(function($arquivo) {
                    return strtolower(basename($arquivo->getFilename(), '.blade.php'));
                });
            
            foreach ($paginas as $pagina) {
                // Verificar se já existe configuração para esta página
                $configExistente = \DB::table('head_configs')
                    ->where('pagina', $pagina)
                    ->where('tema', $nomeTema)
                    ->first();
                
                if (!$configExistente) {
                    // Criar configuração inicial vazia
                    \DB::table('head_configs')->insert([
                        'pagina' => $pagina,
                        'tema' => $nomeTema,
                        'meta_title' => '',
                        'meta_description' => '',
                        'meta_keywords' => '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    \Log::info("Configuração inicial criada para página: {$pagina} do tema {$nomeTema}");
                }
            }
            
            \Log::info("Configurações iniciais criadas para {$paginas->count()} páginas do tema {$nomeTema}");
            
        } catch (\Exception $e) {
            \Log::error("Erro ao criar configurações das páginas do tema: " . $e->getMessage());
        }
    }
}
