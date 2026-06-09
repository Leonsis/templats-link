<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use ZipArchive;

class TemasController extends Controller
{
    public function __construct()
    {
        // Aplicar middleware de auth apenas aos métodos administrativos
        $this->middleware('auth')->only([
            'index', 'store', 'destroy', 'select', 'toggleStatus', 'rename', 'duplicate', 'corrigirRotasTema'
        ]);
        
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $allowedEmails = ['dev@templats-link.com', 'admin@templats-link.com'];
            
            if (!in_array($user->email, $allowedEmails)) {
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json(['error' => 'Acesso negado. Você não tem permissão para acessar esta seção.'], 403);
                }
                return redirect()->route('dashboard')->with('error', 'Acesso negado. Você não tem permissão para acessar esta seção.');
            }
            return $next($request);
        })->only(['index', 'store', 'destroy', 'select', 'toggleStatus', 'rename', 'duplicate', 'corrigirRotasTema']);
    }

    public function index()
    {
        $temas = $this->getTemasList();
        return view('dashboard.temas.index', compact('temas'));
    }

    public function store(Request $request)
    {
        // Aumentar limites do PHP para instalação de temas grandes
        // Forçar configurações mesmo se o php.ini não estiver sendo usado
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0); // Sem limite de tempo
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '120M');
        ini_set('max_input_time', 0); // Sem limite de tempo de input
        ini_set('max_file_uploads', 100);
        
        // Configurações adicionais para evitar timeout
        set_time_limit(0);
        ignore_user_abort(true);
        
        // Log de início da instalação
        \Log::info('=== INÍCIO DA INSTALAÇÃO DE TEMA ===', [
            'timestamp' => now(),
            'user' => auth()->user()->email ?? 'sistema'
        ]);
        
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

        // NOVA VALIDAÇÃO: Verificar estrutura dos arquivos ZIP antes de processar
        $validacaoResultado = $this->validarEstruturaArquivosZip($request);
        if ($validacaoResultado !== true) {
            return back()->withErrors(['arquivo_zip' => $validacaoResultado]);
        }
        
        // DETECÇÃO AUTOMÁTICA: Verificar se há páginas detail_blogs
        $temDetailBlogs = $this->detectarPaginaDetailBlogs($request);
        if ($temDetailBlogs) {
            \Log::info('página blog detectada automaticamente', [
                'tema' => $request->input('nome_tema'),
                'pagina' => 'blog'
            ]);
        }

        // Preparar regras de validação dinâmicas para páginas HTML
        $validationRules = [
            'nome_tema' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]+$/',
            'arquivo_zip' => 'required|file|max:52428800', // 50MB = 50 * 1024 * 1024 bytes
            'arquivo_paginas' => 'nullable|file|max:52428800', // 50MB = 50 * 1024 * 1024 bytes
            'codigo_head' => 'nullable|string|max:10000',
            'codigo_nav' => 'nullable|string',
            'codigo_footer' => 'nullable|string|max:10000',
            'codigo_scripts' => 'nullable|string|max:10000',
            'tem_paginas_html_diferente' => 'nullable|boolean',
            'numero_paginas_html' => 'nullable|integer|min:1|max:20'
        ];
        
        // Adicionar validações dinâmicas para páginas HTML
        if ($request->input('tem_paginas_html_diferente')) {
            $validationRules['numero_paginas_html'] = 'required|integer|min:1|max:20';
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
            'arquivo_zip.max' => 'O arquivo ZIP dos assets não pode ser maior que 50MB.',
            'arquivo_paginas.max' => 'O arquivo ZIP das páginas não pode ser maior que 50MB.',
            'codigo_head.max' => 'O código do head não pode ter mais que 10.000 caracteres.',
            'codigo_footer.max' => 'O código do footer não pode ter mais que 10.000 caracteres.',
            'codigo_scripts.max' => 'O código dos scripts não pode ter mais que 10.000 caracteres.',
            'numero_paginas_html.required' => 'Selecione o número de páginas com HTML diferente.',
            'numero_paginas_html.min' => 'O número mínimo de páginas é 1.',
            'numero_paginas_html.max' => 'O número máximo de páginas é 20.'
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
            $tamanhoArquivo = $arquivoPaginas->getSize();
            
            \Log::info("Validando arquivo de páginas", [
                'nome' => $arquivoPaginas->getClientOriginalName(),
                'tamanho' => $tamanhoArquivo,
                'tamanho_mb' => round($tamanhoArquivo / 1024 / 1024, 2)
            ]);
            
            // Verificar se o arquivo é muito pequeno (menos de 100KB para 14 páginas)
            if ($tamanhoArquivo < 102400) { // 100KB
                \Log::warning("Arquivo de páginas muito pequeno", [
                    'tamanho' => $tamanhoArquivo,
                    'tamanho_kb' => round($tamanhoArquivo / 1024, 2)
                ]);
                return back()->withErrors(['arquivo_paginas' => 'O arquivo ZIP das páginas parece estar vazio ou corrompido. Tamanho: ' . round($tamanhoArquivo / 1024, 2) . 'KB. Para 14 páginas, o arquivo deve ter pelo menos 100KB.']);
            }
            
            if ($zip->open($tempPathPaginas) !== TRUE) {
                return back()->withErrors(['arquivo_paginas' => 'O arquivo ZIP das páginas não é válido ou está corrompido.']);
            }
            
            // Verificar quantos arquivos há no ZIP
            $numeroArquivos = $zip->numFiles;
            \Log::info("Arquivo ZIP das páginas contém {$numeroArquivos} arquivos");
            
            if ($numeroArquivos < 10) { // Esperamos pelo menos 10 arquivos para 14 páginas
                \Log::warning("Poucos arquivos no ZIP das páginas", [
                    'arquivos_encontrados' => $numeroArquivos,
                    'esperado' => 'pelo menos 10'
                ]);
                return back()->withErrors(['arquivo_paginas' => "O arquivo ZIP das páginas contém apenas {$numeroArquivos} arquivos. Para 14 páginas, esperamos pelo menos 10 arquivos. Verifique se o ZIP foi criado corretamente."]);
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
            
            // NOVA FUNCIONALIDADE: Aplicar correção automática de assets
            $this->corrigirAssetsAutomaticamente($temaPath, $nomeTema);

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
            \Log::info("Processando links do tema {$nomeTema}");
            $this->processarLinksTema($temaPath, $temaViewsPath, $nomeTema);
            
            // Converter HTML para Blade e ajustar tema
            \Log::info("Convertendo HTML para Blade do tema {$nomeTema}");
            $this->converterHtmlParaBlade($temaViewsPath, $nomeTema);
            
            // Linkar formulários dinamicamente ao tema
            \Log::info("Linkando formulários do tema {$nomeTema}");
            $this->linkarFormulariosAoTema($temaViewsPath, $nomeTema);
            
            // NOVA FUNCIONALIDADE: Validar rotas dinâmicas antes de criar
            $paginas = collect(File::files($temaViewsPath))
                ->filter(function($arquivo) {
                    $nome = $arquivo->getFilename();
                    return str_ends_with($nome, '.blade.php') && 
                           !str_contains($arquivo->getPathname(), 'inc') &&
                           !str_contains($arquivo->getPathname(), 'layouts');
                })
                ->map(function($arquivo) {
                    return strtolower(basename($arquivo->getFilename(), '.blade.php'));
                });
            
            $conflitos = $this->validarRotasDinamicas($nomeTema, $paginas);
            if (!empty($conflitos)) {
                \Log::warning("Conflitos de rotas detectados, mas continuando instalação: " . implode(', ', $conflitos));
            }
            
            // SEMPRE criar rotas dinâmicas (independente de ter arquivo de páginas)
            \Log::info("Criando rotas dinâmicas do tema {$nomeTema}");
            $this->criarRotasDinamicas($nomeTema, $temaViewsPath);
            
            // Substituir links .html pelas rotas corretas
            \Log::info("Substituindo links HTML do tema {$nomeTema}");
            $this->substituirLinksHtml($nomeTema);
            
            // Registrar tema no banco de dados
            \Log::info("Registrando tema {$nomeTema} no banco de dados");
            $this->registrarTemaNoBanco($nomeTema, $request);
            
            // Criar configurações iniciais para as páginas do tema
            \Log::info("Criando configurações das páginas do tema {$nomeTema}");
            $this->criarConfiguracoesPaginasTema($nomeTema, $temaViewsPath);
            
            // Manter main-Thema como ativo (não selecionar automaticamente o tema instalado)
            \Log::info("Tema {$nomeTema} instalado com sucesso, mantendo main-Thema como ativo");
            
            $mensagem = 'Tema "' . $nomeTema . '" instalado com sucesso! Assets processados, estrutura Blade criada, links .html substituídos pelas rotas corretas, HTML convertido para Blade, formulários linkados dinamicamente, rotas dinâmicas criadas. O tema main-Thema permanece ativo.';
            if ($arquivoPaginas) {
                $mensagem .= ' Páginas processadas.';
            }
            
            return redirect()->route('dashboard.temas')->with('success', $mensagem);
        } catch (\Exception $e) {
            // NOVA FUNCIONALIDADE: Sistema de rollback melhorado
            \Log::error("❌ ERRO CRÍTICO durante instalação do tema {$nomeTema}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            // Executar rollback automático
            $this->executarRollback($temaPath, $temaViewsPath, $nomeTema);
            
            // Determinar tipo de erro para mensagem mais específica
            $mensagemErro = $this->determinarTipoErro($e);
            
            return back()->withErrors([
                'arquivo_zip' => $mensagemErro,
                'rollback' => 'Sistema de rollback executado automaticamente. Todos os arquivos foram removidos.'
            ]);
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
     * Duplicar um tema
     */
    public function duplicate(Request $request, $nomeTema)
    {
        // Não permitir duplicar o main-Thema
        if ($nomeTema === 'main-Thema') {
            return back()->withErrors(['tema' => 'Não é possível duplicar o tema padrão do sistema.']);
        }

        $request->validate([
            'novo_nome_tema' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]+$/',
        ], [
            'novo_nome_tema.required' => 'O nome do novo tema é obrigatório.',
            'novo_nome_tema.regex' => 'O nome do tema deve conter apenas letras, números, hífen (-) e underscore (_).',
            'novo_nome_tema.max' => 'O nome do tema não pode ter mais de 255 caracteres.',
        ]);

        $novoNome = $request->input('novo_nome_tema');
        
        // Verificar se o novo nome já existe
        if ($this->temaExiste($novoNome)) {
            return back()->withErrors(['novo_nome_tema' => 'Já existe um tema com esse nome.']);
        }

        // Verificar se o tema original existe
        $temaOriginalPath = public_path('temas/' . $nomeTema);
        $temaOriginalViewsPath = resource_path('views/temas/' . $nomeTema);
        
        if (!File::exists($temaOriginalPath) && !File::exists($temaOriginalViewsPath)) {
            return back()->withErrors(['tema' => 'Tema original não encontrado.']);
        }

        try {
            \Log::info("Iniciando duplicação do tema: {$nomeTema} para {$novoNome}");
            
            // 1. Duplicar assets do tema
            $novoTemaPath = public_path('temas/' . $novoNome);
            if (File::exists($temaOriginalPath)) {
                File::copyDirectory($temaOriginalPath, $novoTemaPath);
                \Log::info("Assets duplicados: {$novoTemaPath}");
            }
            
            // 2. Duplicar views do tema
            $novoTemaViewsPath = resource_path('views/temas/' . $novoNome);
            if (File::exists($temaOriginalViewsPath)) {
                File::copyDirectory($temaOriginalViewsPath, $novoTemaViewsPath);
                \Log::info("Views duplicadas: {$novoTemaViewsPath}");
            }
            
            // 3. Atualizar referências nos arquivos duplicados (substituir nome do tema antigo pelo novo)
            // Atualizar referências nas views
            $this->atualizarReferenciasTema($nomeTema, $novoNome);
            
            // Atualizar referências nos assets (CSS, JS, etc.)
            if (File::exists($novoTemaPath)) {
                $this->atualizarReferenciasEmAssets($novoTemaPath, $nomeTema, $novoNome);
            }
            
            // 4. Duplicar registro na tabela temas
            $temaOriginal = DB::table('temas')->where('slug', $nomeTema)->first();
            if ($temaOriginal) {
                DB::table('temas')->insert([
                    'nome' => $novoNome,
                    'slug' => $novoNome,
                    'preview_path' => $temaOriginal->preview_path,
                    'arquivo_path' => $novoNome,
                    'ativo' => 0, // Novo tema não será ativo por padrão
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \Log::info("Registro duplicado na tabela temas");
            }
            
            // 5. Garantir que o tema original tenha rotas no banco antes de duplicar
            $this->garantirRotasTemaNoBanco($nomeTema);
            
            // 5.1. Duplicar rotas dinâmicas
            $rotasOriginais = DB::table('rotas_dinamicas')
                ->whereRaw('LOWER(tema) = ?', [strtolower($nomeTema)])
                ->get();
            
            foreach ($rotasOriginais as $rota) {
                // Verificar se a rota já existe para o novo tema
                $rotaExistente = DB::table('rotas_dinamicas')
                    ->where('tema', $novoNome)
                    ->where('pagina', $rota->pagina)
                    ->first();
                
                if (!$rotaExistente) {
                    // Atualizar a rota para usar o novo nome do tema
                    $novaRota = str_replace($nomeTema, $novoNome, $rota->rota);
                    $novoNomeRota = str_replace($nomeTema, $novoNome, $rota->nome_rota);
                    
                    DB::table('rotas_dinamicas')->insert([
                        'tema' => $novoNome,
                        'pagina' => $rota->pagina,
                        'rota' => $novaRota,
                        'nome_rota' => $novoNomeRota,
                        'controller' => $rota->controller,
                        'metodo' => $rota->metodo,
                        'ativo' => $rota->ativo,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            \Log::info("Rotas dinâmicas duplicadas: " . $rotasOriginais->count());
            
            // 5.2. Garantir que o novo tema também tenha todas as rotas necessárias
            $this->garantirRotasTemaNoBanco($novoNome);
            
            // 6. Duplicar configurações do head
            $configsOriginais = DB::table('head_configs')->where('tema', $nomeTema)->get();
            foreach ($configsOriginais as $config) {
                DB::table('head_configs')->insert([
                    'pagina' => $config->pagina,
                    'tema' => $novoNome,
                    'meta_title' => str_replace($nomeTema, $novoNome, $config->meta_title ?? ''),
                    'meta_description' => str_replace($nomeTema, $novoNome, $config->meta_description ?? ''),
                    'meta_keywords' => str_replace($nomeTema, $novoNome, $config->meta_keywords ?? ''),
                    'favicon' => $config->favicon,
                    'youtube' => $config->youtube,
                    'linkedin' => $config->linkedin,
                    'twitter' => $config->twitter,
                    'instagram' => $config->instagram,
                    'facebook' => $config->facebook,
                    'horario_atendimento' => $config->horario_atendimento,
                    'endereco' => $config->endereco,
                    'telefone' => $config->telefone,
                    'whatsapp' => $config->whatsapp,
                    'email_contato' => $config->email_contato,
                    'email_formulario' => $config->email_formulario,
                    'logo' => $config->logo,
                    'logo_footer' => $config->logo_footer,
                    'descricao_footer' => str_replace($nomeTema, $novoNome, $config->descricao_footer ?? ''),
                    'copyright_footer' => str_replace($nomeTema, $novoNome, $config->copyright_footer ?? ''),
                    'gtm_head' => $config->gtm_head,
                    'gtm_body' => $config->gtm_body,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            \Log::info("Configurações do head duplicadas: " . $configsOriginais->count());
            
            // 7. Limpar cache do sistema (antes de registrar rotas)
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            \Log::info("Cache do sistema limpo");
            
            // 8. Forçar recarregamento das rotas dinâmicas
            // As rotas serão registradas automaticamente pelo DynamicRoutesServiceProvider na próxima requisição
            // Mas vamos tentar registrar manualmente para esta requisição também
            try {
                $this->registrarRotasDinamicasTema($novoNome);
            } catch (\Exception $e) {
                \Log::warning("Não foi possível registrar rotas manualmente (normal se já foram registradas): " . $e->getMessage());
                // Isso é normal - as rotas serão registradas pelo ServiceProvider na próxima requisição
            }
            
            \Log::info("Duplicação do tema {$nomeTema} para {$novoNome} finalizada com sucesso");
            
            return redirect()->route('dashboard.temas')->with('success', "Tema '{$nomeTema}' duplicado com sucesso como '{$novoNome}'! Assets, views, rotas e configurações foram copiados.");
        } catch (\Exception $e) {
            \Log::error("Erro ao duplicar tema {$nomeTema}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            // Limpar arquivos criados em caso de erro
            if (File::exists($novoTemaPath)) {
                File::deleteDirectory($novoTemaPath);
            }
            if (File::exists($novoTemaViewsPath)) {
                File::deleteDirectory($novoTemaViewsPath);
            }
            
            return back()->withErrors(['tema' => 'Erro ao duplicar o tema: ' . $e->getMessage()])->withInput();
        }
    }
    

    /**
     * Atualizar referências nos arquivos de assets (CSS, JS, HTML, etc.)
     */
    private function atualizarReferenciasEmAssets($assetsPath, $nomeTemaAntigo, $nomeTemaNovo)
    {
        try {
            \Log::info("Atualizando referências nos assets: {$nomeTemaAntigo} -> {$nomeTemaNovo}");
            
            if (!File::exists($assetsPath)) {
                return;
            }
            
            $files = File::allFiles($assetsPath);
            $extensions = ['css', 'js', 'html', 'php', 'txt', 'json', 'xml'];
            
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                
                if (in_array($extension, $extensions)) {
                    try {
                        $content = File::get($file->getPathname());
                        $updatedContent = str_replace($nomeTemaAntigo, $nomeTemaNovo, $content);
                        
                        if ($content !== $updatedContent) {
                            File::put($file->getPathname(), $updatedContent);
                            \Log::info("Referências atualizadas em assets: " . $file->getRelativePathname());
                        }
                    } catch (\Exception $e) {
                        \Log::warning("Erro ao atualizar referências em {$file->getPathname()}: " . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error("Erro ao atualizar referências nos assets: " . $e->getMessage());
        }
    }

    /**
     * Renomear um tema
     */
    public function rename(Request $request, $nomeTema)
    {
        // Não permitir renomear o main-Thema
        if ($nomeTema === 'main-Thema') {
            return back()->withErrors(['tema' => 'Não é possível renomear o tema padrão do sistema.']);
        }

        $request->validate([
            'novo_nome' => 'required|string|max:50|regex:/^[a-zA-Z0-9-_]+$/',
        ], [
            'novo_nome.required' => 'O novo nome do tema é obrigatório.',
            'novo_nome.regex' => 'O nome do tema deve conter apenas letras, números, hífen (-) e underscore (_).',
            'novo_nome.max' => 'O nome do tema não pode ter mais de 50 caracteres.',
        ]);

        $novoNome = $request->input('novo_nome');
        
        // Verificar se o novo nome já existe
        if ($this->temaExiste($novoNome)) {
            return back()->withErrors(['tema' => 'Já existe um tema com esse nome.']);
        }

        // Verificar se o tema atual existe
        if (!$this->temaExiste($nomeTema)) {
            return back()->withErrors(['tema' => 'Tema não encontrado.']);
        }

        try {
            \Log::info("Iniciando renomeação do tema: {$nomeTema} -> {$novoNome}");
            
            // 1. Verificar se o tema renomeado é o tema ativo
            $temaAtivo = \App\Helpers\ThemeHelper::getActiveTheme();
            $isTemaAtivo = ($temaAtivo === $nomeTema);
            
            // 2. Renomear pasta de assets
            $temaPathAntigo = public_path('temas/' . $nomeTema);
            $temaPathNovo = public_path('temas/' . $novoNome);
            if (File::exists($temaPathAntigo)) {
                File::move($temaPathAntigo, $temaPathNovo);
                \Log::info("Assets renomeados: {$temaPathAntigo} -> {$temaPathNovo}");
            }
            
            // 3. Renomear pasta de views
            $temaViewsPathAntigo = resource_path('views/temas/' . $nomeTema);
            $temaViewsPathNovo = resource_path('views/temas/' . $novoNome);
            if (File::exists($temaViewsPathAntigo)) {
                File::move($temaViewsPathAntigo, $temaViewsPathNovo);
                \Log::info("Views renomeadas: {$temaViewsPathAntigo} -> {$temaViewsPathNovo}");
            }
            
            // 4. Atualizar configurações no banco de dados
            \DB::table('head_configs')
                ->where('tema', $nomeTema)
                ->update(['tema' => $novoNome]);
            \Log::info("Configurações atualizadas na tabela head_configs");
            
            // 5. Atualizar rotas dinâmicas no banco
            \DB::table('rotas_dinamicas')
                ->where('tema', $nomeTema)
                ->update(['tema' => $novoNome]);
            \Log::info("Rotas dinâmicas atualizadas na tabela rotas_dinamicas");
            
            // 6. Atualizar registro do tema na tabela temas
            \DB::table('temas')
                ->where('slug', $nomeTema)
                ->update([
                    'nome' => ucfirst(str_replace(['-', '_'], ' ', $novoNome)),
                    'slug' => $novoNome,
                    'updated_at' => now()
                ]);
            \Log::info("Registro do tema atualizado na tabela temas");
            
            // 7. Se o tema renomeado era o ativo, atualizar configuração
            if ($isTemaAtivo) {
                $this->atualizarTemaAtivo($novoNome);
            }
            
            // 8. Atualizar referências nos arquivos do tema
            $this->atualizarReferenciasTema($nomeTema, $novoNome);
            
            // 9. Atualizar referências no dashboard e outros arquivos
            $this->atualizarReferenciasSistema($nomeTema, $novoNome);
            
            // 10. Limpar cache do sistema
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            \Log::info("Cache do sistema limpo");
            
            \Log::info("Renomeação do tema {$nomeTema} para {$novoNome} finalizada com sucesso");
            
            $mensagem = 'Tema renomeado com sucesso! ';
            $mensagem .= "De '{$nomeTema}' para '{$novoNome}'. ";
            $mensagem .= 'Assets, views, configurações, rotas dinâmicas, referências nos arquivos do tema e no sistema foram atualizados.';
            
            return redirect()->route('dashboard.temas')->with('success', $mensagem);
            
        } catch (\Exception $e) {
            \Log::error("Erro ao renomear tema {$nomeTema}: " . $e->getMessage());
            return back()->withErrors(['tema' => 'Erro ao renomear o tema: ' . $e->getMessage()]);
        }
    }

    /**
     * Atualizar referências do tema nos arquivos
     */
    private function atualizarReferenciasTema($nomeTemaAntigo, $nomeTemaNovo)
    {
        try {
            \Log::info("Iniciando atualização de referências: {$nomeTemaAntigo} -> {$nomeTemaNovo}");
            
            $temaViewsPath = resource_path('views/temas/' . $nomeTemaNovo);
            
            if (!File::exists($temaViewsPath)) {
                \Log::warning("Diretório do tema não encontrado: {$temaViewsPath}");
                return;
            }
            
            // Buscar todos os arquivos .blade.php do tema
            $arquivos = $this->getArquivosBlade($temaViewsPath);
            
            $totalArquivosAtualizados = 0;
            $totalReferenciasAtualizadas = 0;
            
            foreach ($arquivos as $arquivo) {
                $conteudo = File::get($arquivo);
                $conteudoOriginal = $conteudo;
                
                // 1. Atualizar @extends
                $conteudo = str_replace(
                    "extends('temas.{$nomeTemaAntigo}.layouts.app')",
                    "extends('temas.{$nomeTemaNovo}.layouts.app')",
                    $conteudo
                );
                
                // 2. Atualizar @include
                $conteudo = str_replace(
                    "include('temas.{$nomeTemaAntigo}.inc.",
                    "include('temas.{$nomeTemaNovo}.inc.",
                    $conteudo
                );
                
                // 3. Atualizar rotas
                $conteudo = str_replace(
                    "route('tema.{$nomeTemaAntigo}.",
                    "route('tema.{$nomeTemaNovo}.",
                    $conteudo
                );
                
                // 4. Atualizar assets
                $conteudo = str_replace(
                    "asset('temas/{$nomeTemaAntigo}/assets/",
                    "asset('temas/{$nomeTemaNovo}/assets/",
                    $conteudo
                );
                
                // 5. Atualizar referências nos helpers
                $conteudo = str_replace(
                    "'{$nomeTemaAntigo}'",
                    "'{$nomeTemaNovo}'",
                    $conteudo
                );
                
                // Se houve mudanças, salvar o arquivo
                if ($conteudo !== $conteudoOriginal) {
                    File::put($arquivo, $conteudo);
                    $totalArquivosAtualizados++;
                    
                    // Contar quantas referências foram atualizadas
                    $referenciasAntigas = substr_count($conteudoOriginal, $nomeTemaAntigo);
                    $referenciasNovas = substr_count($conteudo, $nomeTemaNovo);
                    $totalReferenciasAtualizadas += ($referenciasAntigas - $referenciasNovas);
                    
                    \Log::info("Arquivo atualizado: " . basename($arquivo));
                }
            }
            
            \Log::info("Atualização de referências concluída", [
                'arquivos_atualizados' => $totalArquivosAtualizados,
                'referencias_atualizadas' => $totalReferenciasAtualizadas
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Erro ao atualizar referências do tema: " . $e->getMessage());
        }
    }
    
    /**
     * Atualizar referências do tema no sistema (dashboard, etc.)
     */
    private function atualizarReferenciasSistema($nomeTemaAntigo, $nomeTemaNovo)
    {
        try {
            \Log::info("Iniciando atualização de referências do sistema: {$nomeTemaAntigo} -> {$nomeTemaNovo}");
            
            $arquivosSistema = [
                // Dashboard de páginas do tema
                resource_path('views/dashboard/theme-pages/index.blade.php'),
                // Outros arquivos que possam referenciar o tema
                resource_path('views/dashboard/temas/index.blade.php'),
            ];
            
            $totalArquivosAtualizados = 0;
            
            foreach ($arquivosSistema as $arquivo) {
                if (!File::exists($arquivo)) {
                    continue;
                }
                
                $conteudo = File::get($arquivo);
                $conteudoOriginal = $conteudo;
                
                // 1. Atualizar rotas no JavaScript
                $conteudo = str_replace(
                    "route('tema.{$nomeTemaAntigo}.",
                    "route('tema.{$nomeTemaNovo}.",
                    $conteudo
                );
                
                // 2. Atualizar referências em strings
                $conteudo = str_replace(
                    "'{$nomeTemaAntigo}'",
                    "'{$nomeTemaNovo}'",
                    $conteudo
                );
                
                // 3. Atualizar referências em comentários ou textos
                $conteudo = str_replace(
                    "{$nomeTemaAntigo}",
                    "{$nomeTemaNovo}",
                    $conteudo
                );
                
                // Se houve mudanças, salvar o arquivo
                if ($conteudo !== $conteudoOriginal) {
                    File::put($arquivo, $conteudo);
                    $totalArquivosAtualizados++;
                    \Log::info("Arquivo do sistema atualizado: " . basename($arquivo));
                }
            }
            
            \Log::info("Atualização de referências do sistema concluída", [
                'arquivos_atualizados' => $totalArquivosAtualizados
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Erro ao atualizar referências do sistema: " . $e->getMessage());
        }
    }
    
    /**
     * Buscar todos os arquivos .blade.php de um diretório
     */
    private function getArquivosBlade($diretorio)
    {
        $arquivos = [];
        
        if (!File::exists($diretorio)) {
            return $arquivos;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($diretorio)
        );
        
        foreach ($iterator as $arquivo) {
            if ($arquivo->isFile() && $arquivo->getExtension() === 'php') {
                $arquivos[] = $arquivo->getPathname();
            }
        }
        
        return $arquivos;
    }

    /**
     * Gerar sitemap.xml para um tema
     */
    public function generateSitemap($nomeTema = null)
    {
        try {
            \Log::info("=== INÍCIO DA GERAÇÃO DE SITEMAP ===");
            
            // Usar o tema ativo do sistema ao invés do parâmetro da rota
            $temaAtivo = \App\Helpers\ThemeHelper::getActiveTheme();
            
            // Se foi passado um tema específico e é diferente do ativo, usar o ativo
            if ($nomeTema && $nomeTema !== $temaAtivo) {
                \Log::warning("Tema passado na rota ({$nomeTema}) é diferente do tema ativo ({$temaAtivo}). Usando tema ativo.");
            }
            
            $nomeTema = $temaAtivo;
            
            \Log::info("Tema ativo do sistema: {$nomeTema}");
            \Log::info("URL atual: " . request()->url());
            \Log::info("Host: " . request()->getHost());
            \Log::info("Referer: " . request()->header('referer'));
            \Log::info("Método: " . request()->method());
            \Log::info("Usuário logado: " . (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->email : 'Não logado'));
            
            // Para teste, vamos simular um usuário admin se não estiver logado
            if (!\Illuminate\Support\Facades\Auth::check()) {
                \Log::info("Usuário não logado, simulando admin para teste");
                $adminUser = \App\Models\Usuario::where('email', 'admin@templats.com')->first();
                if ($adminUser) {
                    \Illuminate\Support\Facades\Auth::login($adminUser);
                    \Log::info("Usuário admin simulado: " . $adminUser->email);
                }
            }
            
            // Verificar se o tema existe
            if (!$this->temaExiste($nomeTema)) {
                \Log::error("Tema não encontrado: {$nomeTema}");
                return redirect()->route('dashboard.theme-pages')->withErrors(['tema' => 'Tema não encontrado.']);
            }
            
            // Obter páginas do tema
            $paginas = $this->obterPaginasDoTema($nomeTema);
            
            if (empty($paginas)) {
                \Log::warning("Nenhuma página encontrada no tema: {$nomeTema}");
                return redirect()->route('dashboard.theme-pages')->withErrors(['tema' => 'Nenhuma página encontrada no tema.']);
            }
            
            // Obter rotas dinâmicas do tema
            $rotasDinamicas = \DB::table('rotas_dinamicas')
                ->where('tema', $nomeTema)
                ->where('ativo', 1)
                ->get();
            
            \Log::info("Rotas dinâmicas encontradas: " . $rotasDinamicas->count());
            
            // Gerar sitemap
            $sitemap = $this->gerarSitemapXML($nomeTema, $paginas, $rotasDinamicas);
            
            // Salvar arquivo
            $sitemapPath = base_path('sitemap.xml');
            if (File::put($sitemapPath, $sitemap)) {
                \Log::info("Sitemap gerado com sucesso: {$sitemapPath}");
                
                $mensagem = 'Sitemap.xml gerado com sucesso! ';
                $mensagem .= count($paginas) . ' páginas incluídas. ';
                $mensagem .= 'Arquivo salvo em: ' . url('sitemap.xml');
                
                return redirect()->route('dashboard.theme-pages')->with('success', $mensagem);
            } else {
                return redirect()->route('dashboard.theme-pages')->withErrors(['tema' => 'Erro ao salvar o arquivo sitemap.xml.']);
            }
            
        } catch (\Exception $e) {
            \Log::error("Erro ao gerar sitemap para o tema {$nomeTema}: " . $e->getMessage());
            return redirect()->route('dashboard.theme-pages')->withErrors(['tema' => 'Erro ao gerar sitemap: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Obter páginas de um tema
     */
    private function obterPaginasDoTema($nomeTema)
    {
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        if (!File::exists($temaViewsPath)) {
            \Log::warning("Diretório do tema não encontrado: {$temaViewsPath}");
            return [];
        }
        
        $paginas = [];
        $arquivos = File::files($temaViewsPath); // Usar files() ao invés de allFiles() para pegar apenas arquivos na raiz
        
        foreach ($arquivos as $arquivo) {
            $nomeArquivo = $arquivo->getFilename();
            $caminhoCompleto = $arquivo->getPathname();
            
            // Incluir apenas arquivos .blade.php que não estão em subdiretórios
            if (str_ends_with($nomeArquivo, '.blade.php')) {
                // Verificar se não está em subdiretórios (inc, layouts, auth, etc)
                $caminhoRelativo = str_replace($temaViewsPath . DIRECTORY_SEPARATOR, '', $caminhoCompleto);
                
                // Se o caminho relativo contém separador de diretório, está em subdiretório
                if (!str_contains($caminhoRelativo, DIRECTORY_SEPARATOR) && 
                    !str_contains($caminhoRelativo, '/') &&
                    !str_contains($caminhoRelativo, '\\')) {
                    
                    $nomePagina = str_replace('.blade.php', '', $nomeArquivo);
                    $paginas[] = $nomePagina;
                    \Log::info("Página detectada: {$nomePagina}");
                }
            }
        }
        
        // Também verificar subdiretórios específicos se necessário (mas não inc, layouts, auth)
        $subdiretoriosPermitidos = []; // Pode adicionar subdiretórios específicos aqui se necessário
        
        \Log::info("Total de páginas encontradas para o tema {$nomeTema}: " . count($paginas));
        \Log::info("Páginas: " . implode(', ', $paginas));
        
        return array_unique($paginas);
    }
    
    /**
     * Gerar XML do sitemap
     */
    private function gerarSitemapXML($nomeTema, $paginas, $rotasDinamicas)
    {
        // Detectar o domínio dinamicamente baseado na requisição atual
        $baseUrl = $this->detectarDominioAtual();
        
        \Log::info("URL base para sitemap: {$baseUrl}");
        $currentDate = now()->format('Y-m-d\TH:i:s\Z');
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        $urlsAdicionadas = []; // Para evitar duplicatas
        
        // Páginas principais do site
        $paginasPrincipais = ['home', 'sobre', 'contato'];
        foreach ($paginasPrincipais as $pagina) {
            if (in_array($pagina, $paginas)) {
                // Para home, sempre usar apenas / (não /home)
                $url = $baseUrl . '/' . ($pagina === 'home' ? '' : $pagina);
                // Normalizar URL (remover barra dupla se houver)
                $url = str_replace('//', '/', $url);
                $url = rtrim($url, '/') . ($pagina === 'home' ? '/' : '');
                
                // Garantir que não adicione /home se já existe /
                $urlNormalizada = $url;
                if ($pagina === 'home') {
                    $urlNormalizada = rtrim($baseUrl, '/') . '/';
                }
                
                if (!in_array($urlNormalizada, $urlsAdicionadas) && 
                    !in_array($baseUrl . '/home', $urlsAdicionadas)) {
                    $xml .= $this->gerarUrlSitemap($urlNormalizada, $currentDate, '1.0', 'daily');
                    $urlsAdicionadas[] = $urlNormalizada;
                    // Também adicionar à lista para evitar /home
                    $urlsAdicionadas[] = $baseUrl . '/home';
                }
            }
        }
        
        // Páginas específicas do tema (excluindo as principais e blog que será tratado separadamente)
        foreach ($paginas as $pagina) {
            // Excluir páginas principais e blog (blog será tratado nas rotas dinâmicas)
            if (!in_array($pagina, $paginasPrincipais) && 
                !in_array($pagina, ['blog', 'blogs', 'detail-blogs', 'detail_blogs'])) {
                
                // Verificar se já existe uma rota dinâmica para esta página
                $temRotaDinamica = false;
                foreach ($rotasDinamicas as $rota) {
                    if ($rota->pagina === $pagina && !str_contains($rota->rota, '{slug}')) {
                        $temRotaDinamica = true;
                        break;
                    }
                }
                
                // Se não tem rota dinâmica, adicionar com URL padrão baseada no nome
                if (!$temRotaDinamica) {
                    $url = $baseUrl . '/' . $pagina;
                    if (!in_array($url, $urlsAdicionadas)) {
                        $xml .= $this->gerarUrlSitemap($url, $currentDate, '0.8', 'weekly');
                        $urlsAdicionadas[] = $url;
                        \Log::info("Página adicionada ao sitemap (sem rota dinâmica): {$url}");
                    }
                }
            }
        }
        
        // Rotas dinâmicas (blogs, etc.) - apenas se não foram adicionadas acima
        // Rotas que não devem ser adicionadas (já estão nas páginas principais)
        $rotasExcluidas = ['/', '/home', '/sobre', '/contato'];
        
        foreach ($rotasDinamicas as $rota) {
            // Normalizar a rota (garantir que comece com /)
            $rotaNormalizada = $rota->rota;
            if (!str_starts_with($rotaNormalizada, '/')) {
                $rotaNormalizada = '/' . $rotaNormalizada;
            }
            
            // Ignorar rotas que já foram adicionadas nas páginas principais
            if (in_array($rotaNormalizada, $rotasExcluidas)) {
                \Log::info("Rota excluída do sitemap (já está nas páginas principais): {$rotaNormalizada}");
                continue;
            }
            
            // Adicionar página de listagem de blogs se existir
            if ($rota->pagina === 'blogs' || $rota->pagina === 'blog') {
                // Verificar qual é a rota correta para a listagem de blogs
                $rotaBlogs = $rota->rota;
                if (!str_starts_with($rotaBlogs, '/')) {
                    $rotaBlogs = '/' . $rotaBlogs;
                }
                // Remover {slug} se houver para obter a rota base
                $rotaBlogsBase = str_replace('/{slug}', '', $rotaBlogs);
                $rotaBlogsBase = str_replace('{slug}', '', $rotaBlogsBase);
                $rotaBlogsBase = rtrim($rotaBlogsBase, '/');
                
                // Se a rota base for /blog ou /blogs, adicionar
                if ($rotaBlogsBase === '/blog' || $rotaBlogsBase === '/blogs' || $rotaBlogsBase === '') {
                    $url = $baseUrl . ($rotaBlogsBase === '' ? '/blog' : $rotaBlogsBase);
                    if (!in_array($url, $urlsAdicionadas) && !in_array($baseUrl . '/blog', $urlsAdicionadas) && !in_array($baseUrl . '/blogs', $urlsAdicionadas)) {
                        $xml .= $this->gerarUrlSitemap($url, $currentDate, '0.9', 'daily');
                        $urlsAdicionadas[] = $url;
                        // Adicionar variações para evitar duplicatas
                        if ($url !== $baseUrl . '/blog') {
                            $urlsAdicionadas[] = $baseUrl . '/blog';
                        }
                        if ($url !== $baseUrl . '/blogs') {
                            $urlsAdicionadas[] = $baseUrl . '/blogs';
                        }
                        \Log::info("Página de blogs adicionada ao sitemap: {$url}");
                    }
                }
            }
            
            if (str_contains($rota->rota, '{slug}')) {
                // Para páginas com {slug}, buscar os posts do banco
                if ($rota->pagina === 'blog' || $rota->pagina === 'blogs' || $rota->pagina === 'detail-blogs' || $rota->pagina === 'detail_blogs') {
                    // Buscar todos os posts ativos do banco
                    try {
                        $posts = \DB::table('posts')
                            ->where('ativo', 1)
                            ->orderBy('created_at', 'desc')
                            ->get();
                        
                        \Log::info("Posts encontrados para sitemap: " . $posts->count());
                        
                        foreach ($posts as $post) {
                            // Construir URL do post baseado na rota dinâmica
                            $rotaBase = $rota->rota;
                            // Remover {slug} da rota
                            $rotaBase = str_replace('/{slug}', '', $rotaBase);
                            $rotaBase = str_replace('{slug}', '', $rotaBase);
                            // Garantir que comece com /
                            if (!str_starts_with($rotaBase, '/')) {
                                $rotaBase = '/' . $rotaBase;
                            }
                            // Remover barra final se houver
                            $rotaBase = rtrim($rotaBase, '/');
                            
                            // Construir URL completa do post
                            $urlPost = $baseUrl . $rotaBase . '/' . $post->slug;
                            
                            // Usar data de atualização do post se disponível
                            $postDate = $post->updated_at ? date('Y-m-d\TH:i:s\Z', strtotime($post->updated_at)) : $currentDate;
                            
                            if (!in_array($urlPost, $urlsAdicionadas)) {
                                $xml .= $this->gerarUrlSitemap($urlPost, $postDate, '0.7', 'monthly');
                                $urlsAdicionadas[] = $urlPost;
                                \Log::info("Post adicionado ao sitemap: {$urlPost}");
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error("Erro ao buscar posts para sitemap: " . $e->getMessage());
                    }
                }
                continue;
            } else {
                // Verificar se o arquivo da página existe antes de incluir
                $arquivoPagina = resource_path("views/temas/{$nomeTema}/{$rota->pagina}.blade.php");
                if (file_exists($arquivoPagina)) {
                    $url = $baseUrl . $rotaNormalizada;
                    
                    // Verificar se a URL não é duplicata (considerando / e /home como iguais)
                    $urlNormalizada = rtrim($url, '/');
                    $urlNormalizada = $urlNormalizada === $baseUrl ? $baseUrl . '/' : $urlNormalizada;
                    
                    // Verificar duplicatas considerando variações de home
                    $isDuplicata = false;
                    foreach ($urlsAdicionadas as $urlAdicionada) {
                        $urlAdicionadaNormalizada = rtrim($urlAdicionada, '/');
                        $urlAdicionadaNormalizada = $urlAdicionadaNormalizada === $baseUrl ? $baseUrl . '/' : $urlAdicionadaNormalizada;
                        
                        // Se for /home ou /, considerar como duplicata se já existe /
                        if (($urlNormalizada === $baseUrl . '/home' || $urlNormalizada === $baseUrl . '/') &&
                            ($urlAdicionadaNormalizada === $baseUrl . '/' || $urlAdicionadaNormalizada === $baseUrl . '/home')) {
                            $isDuplicata = true;
                            break;
                        }
                        
                        if ($urlNormalizada === $urlAdicionadaNormalizada) {
                            $isDuplicata = true;
                            break;
                        }
                    }
                    
                    if (!$isDuplicata) {
                        $xml .= $this->gerarUrlSitemap($url, $currentDate, '0.8', 'weekly');
                        $urlsAdicionadas[] = $url;
                    }
                } else {
                    \Log::info("Página não encontrada: {$rota->pagina}.blade.php - Rota excluída do sitemap");
                }
            }
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Gerar sitemap público (sem autenticação)
     */
    public function generateSitemapPublic($nomeTema)
    {
        try {
            \Log::info("=== GERAÇÃO PÚBLICA DE SITEMAP ===");
            \Log::info("Tema: {$nomeTema}");
            
            // Simular usuário admin
            $adminUser = \App\Models\Usuario::where('email', 'admin@templats.com')->first();
            if ($adminUser) {
                \Illuminate\Support\Facades\Auth::login($adminUser);
                \Log::info("Usuário admin simulado: " . $adminUser->email);
            }
            
            // Verificar se o tema existe
            if (!$this->temaExiste($nomeTema)) {
                return response()->json(['error' => 'Tema não encontrado.'], 404);
            }
            
            // Obter páginas do tema
            $paginas = $this->obterPaginasDoTema($nomeTema);
            
            if (empty($paginas)) {
                return response()->json(['error' => 'Nenhuma página encontrada no tema.'], 404);
            }
            
            // Obter rotas dinâmicas do tema
            $rotasDinamicas = \DB::table('rotas_dinamicas')
                ->where('tema', $nomeTema)
                ->where('ativo', 1)
                ->get();
            
            // Gerar sitemap
            $sitemap = $this->gerarSitemapXML($nomeTema, $paginas, $rotasDinamicas);
            
            // Salvar arquivo
            $sitemapPath = base_path('sitemap.xml');
            
            // Tentar criar o arquivo com diferentes métodos
            $success = false;
            
            // Método 1: File::put
            try {
                if (File::put($sitemapPath, $sitemap)) {
                    $success = true;
                }
            } catch (\Exception $e) {
                \Log::warning("Método 1 falhou: " . $e->getMessage());
            }
            
            // Método 2: file_put_contents
            if (!$success) {
                try {
                    if (file_put_contents($sitemapPath, $sitemap) !== false) {
                        $success = true;
                    }
                } catch (\Exception $e) {
                    \Log::warning("Método 2 falhou: " . $e->getMessage());
                }
            }
            
            // Método 3: fopen/fwrite
            if (!$success) {
                try {
                    $handle = fopen($sitemapPath, 'w');
                    if ($handle && fwrite($handle, $sitemap) !== false) {
                        fclose($handle);
                        $success = true;
                    }
                } catch (\Exception $e) {
                    \Log::warning("Método 3 falhou: " . $e->getMessage());
                }
            }
            
            if ($success) {
                \Log::info("Sitemap gerado com sucesso: {$sitemapPath}");
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sitemap.xml gerado com sucesso!',
                    'pages' => count($paginas),
                    'file' => url('sitemap.xml'),
                    'content' => $sitemap
                ]);
            } else {
                return response()->json([
                    'error' => 'Erro ao salvar o arquivo sitemap.xml.',
                    'content' => $sitemap,
                    'path' => $sitemapPath
                ], 500);
            }
            
        } catch (\Exception $e) {
            \Log::error("Erro ao gerar sitemap público para o tema {$nomeTema}: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao gerar sitemap: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Detectar o domínio atual dinamicamente
     */
    private function detectarDominioAtual()
    {
        // Tentar obter o domínio da requisição atual
        $host = request()->getHost();
        $scheme = request()->getScheme();
        $port = request()->getPort();
        
        // Construir URL base
        $baseUrl = $scheme . '://' . $host;
        
        // Adicionar porta se não for padrão
        if (($scheme === 'http' && $port !== 80) || ($scheme === 'https' && $port !== 443)) {
            $baseUrl .= ':' . $port;
        }
        
        // Se estiver em localhost/desenvolvimento, detectar o domínio de produção
        if (in_array($host, ['localhost', '127.0.0.1']) || strpos($host, 'localhost') !== false) {
            // Tentar detectar o domínio de produção baseado na configuração
            $productionUrl = config('app.url');
            
            // Se a configuração não for localhost, usar ela
            if (strpos($productionUrl, 'localhost') === false && strpos($productionUrl, '127.0.0.1') === false) {
                $baseUrl = rtrim($productionUrl, '/');
            } else {
                // Fallback para o domínio conhecido
                //$baseUrl = '';
            }
        }
        
        // Log para debug
        \Log::info("Detecção de domínio - Host: {$host}, Scheme: {$scheme}, Port: {$port}, BaseURL: {$baseUrl}");   
        
        return $baseUrl;
    }
    
    /**
     * Gerar entrada de URL para o sitemap
     */
    private function gerarUrlSitemap($url, $lastmod, $priority, $changefreq)
    {
        return "  <url>\n" .
               "    <loc>{$url}</loc>\n" .
               "    <lastmod>{$lastmod}</lastmod>\n" .
               "    <changefreq>{$changefreq}</changefreq>\n" .
               "    <priority>{$priority}</priority>\n" .
               "  </url>\n";
    }

    /**
     * Gerar arquivo llms.txt para um tema
     */
    public function generateLlms($nomeTema, Request $request)
    {
        try {
            \Log::info("=== INÍCIO DA GERAÇÃO DE LLMS.TXT ===");
            \Log::info("Tema: {$nomeTema}");
            
            // Verificar se o tema existe
            if (!$this->temaExiste($nomeTema)) {
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tema não encontrado.'
                    ], 404);
                }
                return redirect()->route('dashboard.theme-pages')->withErrors(['tema' => 'Tema não encontrado.']);
            }
            
            // Obter conteúdo do request (pode vir via JSON ou form)
            $llmsContent = null;
            if ($request->expectsJson() || $request->wantsJson()) {
                $data = $request->json()->all();
                $llmsContent = $data['content'] ?? null;
            } else {
                $llmsContent = $request->input('content');
            }
            
            // Se não foi fornecido conteúdo, usar geração automática (compatibilidade)
            if (empty($llmsContent)) {
                // Obter páginas do tema
                $paginas = $this->obterPaginasDoTema($nomeTema);
                
                if (empty($paginas)) {
                    if ($request->expectsJson() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Nenhuma página encontrada no tema.'
                        ], 400);
                    }
                    return redirect()->route('dashboard.theme-pages')->withErrors(['tema' => 'Nenhuma página encontrada no tema.']);
                }
                
                // Gerar conteúdo do llms.txt
                $llmsContent = $this->gerarLlmsTxt($nomeTema, $paginas);
                
                // Garantir que o conteúdo está em ASCII (sem acentos e caracteres especiais)
                if (!mb_check_encoding($llmsContent, 'ASCII')) {
                    $llmsContent = mb_convert_encoding($llmsContent, 'ASCII', 'UTF-8');
                }
            }
            
            // Salvar arquivo na raiz do projeto
            $llmsPath = base_path('llms.txt');
            
            // Usar file_put_contents para garantir encoding correto
            if (file_put_contents($llmsPath, $llmsContent, LOCK_EX) !== false) {
                \Log::info("LLMS.txt gerado com sucesso: {$llmsPath}");
                
                $mensagem = 'Arquivo llms.txt gerado com sucesso na raiz do projeto!';
                
                // Retornar JSON se for requisição AJAX
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $mensagem,
                        'path' => $llmsPath
                    ]);
                }
                
                return redirect()->route('dashboard.theme-pages')->with('success', $mensagem);
            } else {
                $errorMsg = 'Erro ao salvar o arquivo llms.txt.';
                
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMsg
                    ], 500);
                }
                
                return redirect()->route('dashboard.theme-pages')->withErrors(['tema' => $errorMsg]);
            }
            
        } catch (\Exception $e) {
            \Log::error("Erro ao gerar llms.txt para o tema {$nomeTema}: " . $e->getMessage());
            
            $errorMsg = 'Erro ao gerar llms.txt: ' . $e->getMessage();
            
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg
                ], 500);
            }
            
            return redirect()->route('dashboard.theme-pages')->withErrors(['tema' => $errorMsg]);
        }
    }
    
    /**
     * Remover acentos de uma string
     */
    private function removerAcentos($string)
    {
        if (empty($string)) {
            return $string;
        }
        
        // Converter para UTF-8 se necessário
        if (!mb_check_encoding($string, 'UTF-8')) {
            $string = mb_convert_encoding($string, 'UTF-8', 'auto');
        }
        
        // Mapeamento completo de acentos e caracteres especiais
        $acentos = [
            // A
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
            // E
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            // I
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            // O
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
            // U
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            // C, N, Y
            'Ç' => 'C', 'ç' => 'c',
            'Ñ' => 'N', 'ñ' => 'n',
            'Ý' => 'Y', 'ý' => 'y', 'ÿ' => 'y',
            // Caracteres especiais
            '–' => '-', '—' => '-', '„' => '"', '‚' => ',', '‹' => '<', '›' => '>',
            '«' => '"', '»' => '"', '…' => '...', '€' => 'EUR', '£' => 'GBP',
            // Aspas e outros
            '"' => '"', '"' => '"', '' => "'", '' => "'", '`' => "'"
        ];
        
        // Aplicar substituições
        $resultado = strtr($string, $acentos);
        
        // Usar iconv para transliterar caracteres restantes não-ASCII para ASCII
        if (function_exists('iconv')) {
            $resultado = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $resultado);
            if ($resultado === false) {
                // Se iconv falhar, usar método alternativo
                $resultado = preg_replace('/[^\x00-\x7F]/u', '', $string);
            }
        } else {
            // Fallback: remover caracteres não-ASCII restantes usando regex
            $resultado = preg_replace('/[^\x00-\x7F]/u', '', $resultado);
        }
        
        return $resultado;
    }

    /**
     * Gerar conteúdo do arquivo llms.txt
     */
    private function gerarLlmsTxt($nomeTema, $paginas)
    {
        $baseUrl = $this->detectarDominioAtual();
        $currentDate = now()->format('d/m/Y');
        
        // Obter configurações globais do tema
        $configGlobal = \App\Helpers\HeadHelper::getConfigs('global', $nomeTema);
        $configHome = \App\Helpers\HeadHelper::getConfigs('home', $nomeTema);
        
        // Nome da empresa (usar meta_title da home ou global) - remover acentos
        $nomeEmpresa = $this->removerAcentos($configHome->meta_title ?? $configGlobal->meta_title ?? ucfirst($nomeTema));
        
        // Descrição (usar meta_description da home ou global) - remover acentos
        $descricao = $this->removerAcentos($configHome->meta_description ?? $configGlobal->meta_description ?? '');
        
        // Construir o conteúdo do llms.txt
        $content = "# llms.txt - Instrucoes oficiais para modelos de linguagem sobre {$nomeEmpresa}\n\n";
        $content .= "# Ultima atualizacao: {$currentDate}\n\n";
        $content .= "# Version: 1.0\n\n";
        
        // Título principal
        $content .= "{$nomeEmpresa}\n\n";
        if ($descricao) {
            $content .= "{$descricao}\n\n";
        }
        
        // Preferred Summary
        if ($configGlobal->descricao_footer ?? '') {
            $content .= "[Preferred Summary]\n\n";
            $content .= $this->removerAcentos($configGlobal->descricao_footer) . "\n\n";
        }
        
        // Sobre Nós
        $configSobre = \App\Helpers\HeadHelper::getConfigs('sobre', $nomeTema);
        if ($configSobre && $configSobre->meta_description) {
            $content .= "[Section]Sobre Nos\n\n";
            $content .= $this->removerAcentos($configSobre->meta_description) . "\n\n";
        }
        
        // Serviços Principais
        $content .= "[Section]Servicos Principais\n\n";
        foreach ($paginas as $pagina) {
            if (!in_array($pagina, ['home', 'sobre', 'contato', 'blogs', 'blog'])) {
                $configPagina = \App\Helpers\HeadHelper::getConfigs($pagina, $nomeTema);
                if ($configPagina && $configPagina->meta_title) {
                    $paginaNome = ucwords(str_replace('-', ' ', $pagina));
                    $content .= "{$paginaNome}\n\n";
                    if ($configPagina->meta_description) {
                        $content .= "Descricao: " . $this->removerAcentos($configPagina->meta_description) . "\n\n";
                    }
                    $content .= "Link: {$baseUrl}/{$pagina}\n\n";
                }
            }
        }
        
        // Informações de Contato
        if ($configGlobal->email_contato || $configGlobal->telefone || $configGlobal->whatsapp || $configGlobal->endereco) {
            $content .= "[Section]Informacoes de Contato\n\n";
            
            if ($configGlobal->endereco) {
                $content .= "Endereco Fisico: " . $this->removerAcentos($configGlobal->endereco) . "\n\n";
            }
            
            if ($configGlobal->telefone) {
                $content .= "Telefone: {$configGlobal->telefone}\n\n";
            }
            
            if ($configGlobal->whatsapp) {
                $content .= "WhatsApp: {$configGlobal->whatsapp}\n\n";
            }
            
            if ($configGlobal->email_contato) {
                $content .= "Email: {$configGlobal->email_contato}\n\n";
            }
            
            if ($configGlobal->horario_atendimento) {
                $content .= "Horario de Atendimento: " . $this->removerAcentos($configGlobal->horario_atendimento) . "\n\n";
            }
        }
        
        // Links Importantes
        $content .= "[Section]Links Importantes e Meta Dados de SEO\n\n";
        
        // Página Inicial
        if ($configHome) {
            $content .= "Pagina Inicial Link: {$baseUrl}/\n\n";
            if ($configHome->meta_title) {
                $content .= "Meta Title: " . $this->removerAcentos($configHome->meta_title) . "\n\n";
            }
            if ($configHome->meta_description) {
                $content .= "Meta Description: " . $this->removerAcentos($configHome->meta_description) . "\n\n";
            }
        }
        
        // Outras páginas
        foreach ($paginas as $pagina) {
            if (in_array($pagina, ['home', 'blog'])) {
                continue;
            }
            
            $configPagina = \App\Helpers\HeadHelper::getConfigs($pagina, $nomeTema);
            if ($configPagina && $configPagina->meta_title) {
                $paginaNome = ucwords(str_replace('-', ' ', $pagina));
                $content .= "{$paginaNome} Link: {$baseUrl}/{$pagina}\n\n";
                $content .= "Meta Title: " . $this->removerAcentos($configPagina->meta_title) . "\n\n";
                if ($configPagina->meta_description) {
                    $content .= "Meta Description: " . $this->removerAcentos($configPagina->meta_description) . "\n\n";
                }
            }
        }
        
        // Redes Sociais
        if ($configGlobal->facebook || $configGlobal->instagram || $configGlobal->linkedin || $configGlobal->youtube) {
            $content .= "[Official Links]\n\n";
            $content .= "Website: {$baseUrl}\n\n";
            
            if ($configGlobal->linkedin) {
                $content .= "LinkedIn: {$configGlobal->linkedin}\n\n";
            }
            
            if ($configGlobal->instagram) {
                $content .= "Instagram: {$configGlobal->instagram}\n\n";
            }
            
            if ($configGlobal->facebook) {
                $content .= "Facebook: {$configGlobal->facebook}\n\n";
            }
            
            if ($configGlobal->youtube) {
                $content .= "YouTube: {$configGlobal->youtube}\n\n";
            }
        }
        
        // Data Governance
        $content .= "[Data Governance]\n\n";
        $content .= "As informacoes deste arquivo podem ser utilizadas por modelos de linguagem para gerar respostas contextuais sobre {$nomeEmpresa}.\n\n";
        $content .= "E proibido o uso comercial sem referencia a fonte oficial.\n";
        
        return $content;
    }

    /**
     * Verificar se um tema existe
     */
    private function temaExiste($nomeTema)
    {
        if ($nomeTema === 'main-Thema') {
            return File::exists(resource_path('views/main-Thema'));
        }
        
        $temaPath = public_path('temas/' . $nomeTema);
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        return File::exists($temaPath) && File::exists($temaViewsPath);
    }

    /**
     * Atualizar tema ativo no arquivo de configuração
     */
    private function atualizarTemaAtivo($novoNome)
    {
        try {
            $configPath = config_path('tema_principal.php');
            $configContent = "<?php\n\nreturn [\n    'tema_principal' => '{$novoNome}',\n    'selecionado_em' => '" . now() . "',\n];\n";
            
            if (File::put($configPath, $configContent)) {
                \Log::info("Tema ativo atualizado para: {$novoNome}");
            } else {
                \Log::warning("Erro ao atualizar tema ativo para: {$novoNome}");
            }
        } catch (\Exception $e) {
            \Log::error("Erro ao atualizar tema ativo: " . $e->getMessage());
        }
    }

    /**
     * Renderizar página dinâmica de tema
     */
    public function renderizarPaginaDinamica($tema, $pagina, $slug = null)
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
            
            // Se a página é detail-blogs mas o arquivo não existe, tentar blog.blade.php ou detail_blogs.blade.php
            if (!File::exists($arquivoBlade) && ($pagina === 'detail-blogs' || $pagina === 'detail_blogs')) {
                $arquivoBlog = $temaViewsPath . '/blog.blade.php';
                if (File::exists($arquivoBlog)) {
                    $arquivoBlade = $arquivoBlog;
                    $pagina = 'blog'; // Usar 'blog' como página para renderizar
                } else {
                    // Tentar detail_blogs.blade.php (compatibilidade)
                    $arquivoDetailBlogs = $temaViewsPath . '/detail_blogs.blade.php';
                    if (File::exists($arquivoDetailBlogs)) {
                        $arquivoBlade = $arquivoDetailBlogs;
                        $pagina = 'detail_blogs';
                    }
                }
            }
            
            // Se ainda não existe, verificar o contrário (blog -> detail-blogs)
            if (!File::exists($arquivoBlade) && $pagina === 'blog') {
                $arquivoDetailBlogs = $temaViewsPath . '/detail-blogs.blade.php';
                if (File::exists($arquivoDetailBlogs)) {
                    $arquivoBlade = $arquivoDetailBlogs;
                    $pagina = 'detail-blogs';
                } else {
                    // Tentar detail_blogs.blade.php (compatibilidade)
                    $arquivoDetailBlogsOld = $temaViewsPath . '/detail_blogs.blade.php';
                    if (File::exists($arquivoDetailBlogsOld)) {
                        $arquivoBlade = $arquivoDetailBlogsOld;
                        $pagina = 'detail_blogs';
                    }
                }
            }
            
            if (!File::exists($arquivoBlade)) {
                abort(404, 'Arquivo da página não encontrado');
            }

            // Renderizar a página
            $viewPath = 'temas.' . $tema . '.' . $pagina;
            
            // Garantir que a configuração existe no banco de dados
            $configExistente = \DB::table('head_configs')
                ->where('pagina', $pagina)
                ->where('tema', $tema)
                ->first();
            
            if (!$configExistente) {
                // Criar configuração inicial vazia se não existir
                \DB::table('head_configs')->insert([
                    'pagina' => $pagina,
                    'tema' => $tema,
                    'meta_title' => '',
                    'meta_description' => '',
                    'meta_keywords' => '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Limpar cache para forçar recarregamento
                \App\Helpers\HeadHelper::clearCache($pagina, $tema);
            }
            
            // Definir a página atual para o HeadHelper funcionar corretamente
            $dados = [
                'currentPage' => $pagina
            ];
            
            // Para a página de blogs, buscar posts do banco
            if ($pagina === 'blogs') {
                $posts = \App\Models\Post::where('ativo', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(9); // 9 posts por página
                
                // Buscar a rota correta para detail-blogs ou blog
                $rotaBlogDetail = \DB::table('rotas_dinamicas')
                    ->where('tema', $tema)
                    ->where(function($query) {
                        $query->where('pagina', 'detail-blogs')
                              ->orWhere('pagina', 'detail_blogs') // Compatibilidade com versão antiga
                              ->orWhere('pagina', 'blog');
                    })
                    ->where('rota', 'like', '%{slug}%')
                    ->where('ativo', 1)
                    ->first();
                
                // Determinar a URL base para os links de blog
                $urlBaseBlog = '/detail-blogs'; // Padrão
                if ($rotaBlogDetail) {
                    // Extrair a rota base (remover {slug})
                    $urlBaseBlog = str_replace('/{slug}', '', $rotaBlogDetail->rota);
                    // Garantir que comece com /
                    if (!str_starts_with($urlBaseBlog, '/')) {
                        $urlBaseBlog = '/' . $urlBaseBlog;
                    }
                }
                
                $dados['blogs'] = $posts->map(function($post) use ($tema, $urlBaseBlog) {
                    return [
                        'title' => $post->titulo,
                        'slug' => $post->slug ?? \Str::slug($post->titulo),
                        'url' => $urlBaseBlog . '/' . ($post->slug ?? \Str::slug($post->titulo)),
                        'image' => $post->imagem_apresentacao ? asset('storage/posts/' . $post->imagem_apresentacao) : asset('temas/' . $tema . '/assets/images/default-blog.jpg'),
                        'excerpt' => \Str::limit(strip_tags($post->conteudo), 150),
                        'description' => \Str::limit(strip_tags($post->conteudo), 120),
                        'date' => \Carbon\Carbon::parse($post->created_at)->format('d/m/Y'),
                        'created_at' => $post->created_at,
                    ];
                });
                
                // Adicionar o objeto de paginação
                $dados['blogsPaginated'] = $posts;
            }
            
            // Para páginas que têm {slug} como parâmetro (single do post), buscar post específico
            $rotaComSlug = \DB::table('rotas_dinamicas')
                ->where('tema', $tema)
                ->where('pagina', $pagina)
                ->where('rota', 'like', '%{slug}%')
                ->where('ativo', 1)
                ->first();
            
            if ($rotaComSlug) {
                // Usar o parâmetro slug passado ou obter da URL
                $postSlug = $slug ?? request()->route('slug') ?? request()->get('slug');
                
                if ($postSlug) {
                    // Verificar se existe uma rota dinâmica específica para este slug (página estática)
                    // Isso evita que páginas estáticas sejam tratadas como posts
                    $rotaEstatica = \DB::table('rotas_dinamicas')
                        ->where('tema', $tema)
                        ->where('rota', '/' . $postSlug)
                        ->where('ativo', 1)
                        ->first();
                    
                    // Se existe uma rota estática para este slug, não é um post
                    if ($rotaEstatica) {
                        \Log::info("Slug '{$postSlug}' é uma página estática, não um post. Redirecionando para a página correta.");
                        // Renderizar a página estática em vez de detail_blogs
                        return $this->renderizarPaginaDinamica($tema, $rotaEstatica->pagina);
                    }
                    
                    // Normalizar o slug para corrigir caracteres inválidos
                    $slugNormalizado = \App\Helpers\SlugHelper::normalizarSlug($postSlug);
                    
                    // Buscar post usando o SlugHelper (tenta várias variações)
                    $post = \App\Helpers\SlugHelper::buscarPostPorSlug($slugNormalizado, $postSlug);
                    
                    // Se não encontrou, tentar busca por similaridade
                    if (!$post) {
                        $post = \App\Helpers\SlugHelper::buscarPostPorSimilaridade($slugNormalizado);
                    }
                    
                    // Se encontrou um post mas com slug diferente, redirecionar para o slug correto
                    if ($post && $post->slug !== $postSlug && $post->slug !== $slugNormalizado) {
                        // Usar a rota correta do banco de dados
                        $rotaAtual = $rotaComSlug->rota ?? '/detail-blogs/{slug}';
                        $urlBase = str_replace('/{slug}', '', $rotaAtual);
                        $urlCorreta = url($urlBase . '/' . $post->slug);
                        return redirect($urlCorreta, 301);
                    }
                    
                    if ($post) {
                        $dados['blog'] = [
                            'title' => $post->titulo,
                            'slug' => $post->slug ?? \Str::slug($post->titulo),
                            'image' => $post->imagem_apresentacao ? asset('storage/posts/' . $post->imagem_apresentacao) : asset('temas/' . $tema . '/assets/images/default-blog.jpg'),
                            'excerpt' => \Str::limit(strip_tags($post->conteudo), 150),
                            'content' => $post->conteudo,
                            'description' => $post->descricao ?? '',
                            'author' => $post->autor ?? 'Autor',
                            'date' => \Carbon\Carbon::parse($post->created_at)->format('d/m/Y'),
                            'created_at' => $post->created_at,
                            // Meta tags do post
                            'meta_title' => $post->meta_title ?? $post->titulo,
                            'meta_description' => $post->meta_description ?? \Str::limit(strip_tags($post->conteudo), 160),
                            'meta_keywords' => $post->meta_keywords ?? ''
                        ];
                    } else {
                        // Se não é um post e não é uma rota estática, verificar se é uma página do tema
                        $arquivoPagina = resource_path("views/temas/{$tema}/{$postSlug}.blade.php");
                        if (File::exists($arquivoPagina)) {
                            \Log::info("Slug '{$postSlug}' corresponde a uma página do tema. Redirecionando.");
                            return $this->renderizarPaginaDinamica($tema, $postSlug);
                        }
                        
                        abort(404, 'Post não encontrado');
                    }
                } else {
                    abort(404, 'Slug do post não fornecido');
                }
            }
            
            // Se a página é 'blog' e há um slug, mas não há rota dinâmica registrada,
            // buscar o post mesmo assim (caso da página blog.blade.php sem rota dinâmica)
            if ($pagina === 'blog' && $slug) {
                $postSlug = $slug;
                
                // Normalizar o slug para corrigir caracteres inválidos
                $slugNormalizado = \App\Helpers\SlugHelper::normalizarSlug($postSlug);
                
                // Buscar post usando o SlugHelper (tenta várias variações)
                $post = \App\Helpers\SlugHelper::buscarPostPorSlug($slugNormalizado, $postSlug);
                
                // Se não encontrou, tentar busca por similaridade
                if (!$post) {
                    $post = \App\Helpers\SlugHelper::buscarPostPorSimilaridade($slugNormalizado);
                }
                
                if ($post) {
                    $dados['blog'] = [
                        'title' => $post->titulo,
                        'slug' => $post->slug ?? \Str::slug($post->titulo),
                        'image' => $post->imagem_apresentacao ? asset('storage/posts/' . $post->imagem_apresentacao) : asset('temas/' . $tema . '/assets/images/default-blog.jpg'),
                        'excerpt' => \Str::limit(strip_tags($post->conteudo), 150),
                        'content' => $post->conteudo,
                        'description' => $post->descricao ?? '',
                        'author' => $post->autor ?? 'Autor',
                        'date' => \Carbon\Carbon::parse($post->created_at)->format('d/m/Y'),
                        'created_at' => $post->created_at,
                        // Meta tags do post
                        'meta_title' => $post->meta_title ?? $post->titulo,
                        'meta_description' => $post->meta_description ?? \Str::limit(strip_tags($post->conteudo), 160),
                        'meta_keywords' => $post->meta_keywords ?? ''
                    ];
                } else {
                    abort(404, 'Post não encontrado');
                }
            }
            
            return view($viewPath, $dados);

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
                    
                    // Se for requisição AJAX, retornar JSON com erro
                    if (request()->ajax() || request()->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tema main-Thema não encontrado!'
                        ], 404);
                    }
                    
                    return back()->withErrors(['tema' => 'Tema main-Thema não encontrado!']);
                }
            } else {
                // Para outros temas, verificar em public/temas e views/temas
                $temaPath = public_path('temas/' . $nomeTema);
                $temaViewsPath = resource_path('views/temas/' . $nomeTema);
                
                if (!File::exists($temaPath)) {
                    \Log::error("Assets do tema não encontrados em: {$temaPath}");
                    
                    // Se for requisição AJAX, retornar JSON com erro
                    if (request()->ajax() || request()->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Assets do tema não encontrados!'
                        ], 404);
                    }
                    
                    return back()->withErrors(['tema' => 'Assets do tema não encontrados!']);
                }
                
                if (!File::exists($temaViewsPath)) {
                    \Log::error("Views do tema não encontradas em: {$temaViewsPath}");
                    
                    // Se for requisição AJAX, retornar JSON com erro
                    if (request()->ajax() || request()->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Views do tema não encontradas!'
                        ], 404);
                    }
                    
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
                // Garantir permissões de escrita para o Apache
                @chmod($configPath, 0666);
                $saved = true;
            } else {
                // Segunda tentativa: criar arquivo temporário e mover
                $tempPath = storage_path('app/temp_tema_principal.php');
                if (File::put($tempPath, $configContent)) {
                    // Garantir permissões antes de mover
                    @chmod($tempPath, 0666);
                    if (File::move($tempPath, $configPath)) {
                        // Garantir permissões após mover
                        @chmod($configPath, 0666);
                        $saved = true;
                    } else {
                        File::delete($tempPath);
                    }
                }
            }
            
            // Terceira tentativa: usar file_put_contents diretamente com permissões
            if (!$saved) {
                // Garantir que o diretório config existe e tem permissões corretas
                $configDir = config_path();
                if (!is_dir($configDir)) {
                    @mkdir($configDir, 0777, true);
                }
                @chmod($configDir, 0777);
                
                // Tentar criar o arquivo diretamente
                if (@file_put_contents($configPath, $configContent)) {
                    @chmod($configPath, 0666);
                    $saved = true;
                } else {
                    // Log detalhado do erro
                    $error = error_get_last();
                    \Log::error("Erro ao salvar arquivo de configuração", [
                        'path' => $configPath,
                        'error' => $error,
                        'is_writable' => is_writable($configPath),
                        'dir_writable' => is_writable($configDir),
                        'file_exists' => file_exists($configPath),
                        'perms_file' => file_exists($configPath) ? substr(sprintf('%o', fileperms($configPath)), -4) : 'N/A',
                        'perms_dir' => substr(sprintf('%o', fileperms($configDir)), -4)
                    ]);
                }
            }
            
            if (!$saved) {
                \Log::error("Erro ao salvar configuração do tema em: {$configPath}", [
                    'path' => $configPath,
                    'is_writable' => is_writable($configPath),
                    'dir_writable' => is_writable(dirname($configPath)),
                    'file_exists' => file_exists($configPath)
                ]);
                
                // Tentar criar o arquivo com permissões mais amplas
                $configDir = config_path();
                @chmod($configDir, 0777);
                if (file_exists($configPath)) {
                    @chmod($configPath, 0666);
                }
                
                // Última tentativa: usar touch e depois escrever
                @touch($configPath);
                @chmod($configPath, 0666);
                if (@file_put_contents($configPath, $configContent, LOCK_EX)) {
                    @chmod($configPath, 0666);
                    $saved = true;
                }
            }
            
            if (!$saved) {
                // Se for requisição AJAX, retornar JSON com erro
                if (request()->ajax() || request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao salvar configuração do tema! Verifique as permissões da pasta config. O arquivo precisa ter permissões 666 e o diretório precisa ter permissões 777.'
                    ], 500);
                }
                
                return back()->withErrors(['tema' => 'Erro ao salvar configuração do tema! Verifique as permissões da pasta config. O arquivo precisa ter permissões 666 e o diretório precisa ter permissões 777.']);
            }
            
            \Log::info("Configuração do tema salva com sucesso: {$nomeTema}");
            
            // Atualizar o campo ativo na tabela temas do banco de dados
            // Primeiro, desativar todos os temas
            \DB::table('temas')->update(['ativo' => 0]);
            
            // Verificar se o tema existe na tabela temas
            $temaExistente = \DB::table('temas')
                ->where(function($query) use ($nomeTema) {
                    $query->where('nome', $nomeTema)
                          ->orWhere('slug', $nomeTema);
                })
                ->first();
            
            // Se o tema não existir e não for main-Thema, criar o registro
            if (!$temaExistente && $nomeTema !== 'main-Thema') {
                $slug = strtolower(str_replace([' ', '_', '-'], '-', $nomeTema));
                \DB::table('temas')->insert([
                    'nome' => ucfirst(str_replace(['-', '_'], ' ', $nomeTema)),
                    'slug' => $slug,
                    'preview_path' => null,
                    'arquivo_path' => $nomeTema,
                    'ativo' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                \Log::info("Tema {$nomeTema} criado e ativado no banco de dados");
            } else if ($temaExistente) {
                // Ativar o tema existente
                \DB::table('temas')
                    ->where(function($query) use ($nomeTema) {
                        $query->where('nome', $nomeTema)
                              ->orWhere('slug', $nomeTema);
                    })
                    ->update(['ativo' => 1]);
                \Log::info("Tema {$nomeTema} ativado no banco de dados");
            } else {
                \Log::info("Tema {$nomeTema} não encontrado na tabela temas (é main-Thema)");
            }
            
            // Se não for main-Thema, verificar se os formulários estão linkados
            if ($nomeTema !== 'main-Thema') {
                $this->verificarELinkarFormularios($temaViewsPath, $nomeTema);
                
                // Garantir que todas as rotas do tema existam no banco
                $this->garantirRotasTemaNoBanco($nomeTema);
            }
            
            // Limpar cache ANTES de registrar as rotas para garantir que não haja conflitos
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            // Registrar as rotas dinâmicas do tema imediatamente APÓS limpar o cache
            if ($nomeTema !== 'main-Thema') {
                $this->registrarRotasDinamicasTema($nomeTema);
            }
            
            \Log::info("Tema selecionado com sucesso: {$nomeTema}");
            
            // Se for requisição AJAX, retornar JSON
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tema "' . $nomeTema . '" selecionado como tema principal do sistema!'
                ]);
            }
            
            return redirect()->route('dashboard.temas')->with('success', 'Tema "' . $nomeTema . '" selecionado como tema principal do sistema!');
            
        } catch (\Exception $e) {
            \Log::error("Erro ao selecionar tema {$nomeTema}: " . $e->getMessage());
            
            // Se for requisição AJAX, retornar JSON com erro
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao selecionar o tema: ' . $e->getMessage()
                ], 500);
            }
            
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
     * Registrar rotas dinâmicas para um tema específico
     */
    private function registrarRotasDinamicasTema($nomeTema)
    {
        try {
            // Primeiro, garantir que todas as rotas necessárias existam no banco
            $this->garantirRotasTemaNoBanco($nomeTema);
            
            // Carregar rotas dinâmicas do tema do banco de dados (case-insensitive)
            $rotasDinamicas = DB::table('rotas_dinamicas')
                ->whereRaw('LOWER(tema) = ?', [strtolower($nomeTema)])
                ->where('ativo', 1)
                ->get();

            \Log::info("Registrando " . $rotasDinamicas->count() . " rotas dinâmicas para o tema {$nomeTema}");

            // Rotas principais que não devem ser sobrescritas
            $rotasPrincipais = ['/', '/sobre', '/contato', '/login', '/dashboard', '/blog'];

            foreach ($rotasDinamicas as $rotaDinamica) {
                $rotaCompleta = $rotaDinamica->rota;

                // Se a rota não começar com /, adicionar
                if (!str_starts_with($rotaCompleta, '/')) {
                    $rotaCompleta = '/' . $rotaCompleta;
                }

                // Verificar se a rota conflita com rotas principais
                if (in_array($rotaCompleta, $rotasPrincipais)) {
                    \Log::info("Pulando rota dinâmica que conflita com rota principal: {$rotaCompleta}");
                    continue;
                }
                
                // Verificar se já existe uma rota estática com essa URI
                try {
                    $rotasExistentes = Route::getRoutes();
                    foreach ($rotasExistentes as $rota) {
                        $uri = $rota->uri();
                        // Comparar URI exata (sem parâmetros)
                        if ($uri === $rotaCompleta || $uri === ltrim($rotaCompleta, '/')) {
                            \Log::info("Pulando rota dinâmica que conflita com rota estática existente: {$rotaCompleta} (rota estática: {$uri})");
                            continue 2; // Continue o loop externo
                        }
                    }
                } catch (\Exception $e) {
                    // Se houver erro ao verificar rotas, continuar normalmente
                }
                
                // Verificar se a rota dinâmica com {slug} conflita com rota estática
                // Ex: /blog/{slug} não deve ser registrada se já existe /blog
                if (str_contains($rotaCompleta, '{slug}')) {
                    $rotaBase = str_replace('/{slug}', '', $rotaCompleta);
                    // Verificar se a rota base está na lista de rotas principais
                    if (in_array($rotaBase, $rotasPrincipais)) {
                        \Log::info("Pulando rota dinâmica com slug que conflita com rota estática: {$rotaCompleta}");
                        continue;
                    }
                    // Verificar se já existe uma rota estática com esse nome
                    try {
                        $rotasExistentes = Route::getRoutes();
                        foreach ($rotasExistentes as $rota) {
                            $uri = $rota->uri();
                            if ($uri === $rotaBase || $uri === ltrim($rotaBase, '/')) {
                                \Log::info("Pulando rota dinâmica com slug que conflita com rota estática existente: {$rotaCompleta}");
                                continue 2; // Continue o loop externo
                            }
                        }
                    } catch (\Exception $e) {
                        // Se houver erro ao verificar rotas, continuar normalmente
                    }
                }

                // Criar nome único para a rota para evitar conflitos
                // Normalizar o nome do tema e nome_rota (substituir hífens por pontos)
                $nomeRotaUnico = theme_route_name($rotaDinamica->tema, $rotaDinamica->nome_rota);

                // Verificar se a rota já existe
                try {
                    $rotaExistente = Route::getRoutes()->getByName($nomeRotaUnico);
                    if ($rotaExistente) {
                        \Log::info("Rota {$nomeRotaUnico} já existe, pulando registro");
                        continue;
                    }
                } catch (\Illuminate\Routing\Exceptions\UrlGenerationException $e) {
                    // Rota não existe, pode registrar
                } catch (\Exception $e) {
                    // Outro tipo de erro, tentar registrar mesmo assim
                }

                // Registrar rota dinâmica
                if (str_contains($rotaCompleta, '{slug}')) {
                    // Para rotas com parâmetro slug (como detail_blogs)
                    Route::get($rotaCompleta, function($slug) use ($rotaDinamica) {
                        $controller = new \App\Http\Controllers\TemasController();
                        return $controller->renderizarPaginaDinamica($rotaDinamica->tema, $rotaDinamica->pagina, $slug);
                    })->name($nomeRotaUnico);
                } else {
                    // Para rotas sem parâmetros
                    Route::get($rotaCompleta, function() use ($rotaDinamica) {
                        $controller = new \App\Http\Controllers\TemasController();
                        return $controller->renderizarPaginaDinamica($rotaDinamica->tema, $rotaDinamica->pagina);
                    })->name($nomeRotaUnico);
                }

                \Log::info("Rota dinâmica registrada: {$rotaCompleta} → {$nomeRotaUnico}");
            }

            \Log::info("Rotas dinâmicas do tema {$nomeTema} registradas com sucesso");

        } catch (\Exception $e) {
            \Log::error("Erro ao registrar rotas dinâmicas do tema {$nomeTema}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    /**
     * Corrigir rotas de um tema específico (método público para uso administrativo)
     */
    public function corrigirRotasTema($nomeTema)
    {
        try {
            \Log::info("Iniciando correção de rotas para o tema: {$nomeTema}");
            
            // Garantir que todas as rotas existam no banco
            $this->garantirRotasTemaNoBanco($nomeTema);
            
            // Limpar cache
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            \Log::info("Rotas do tema {$nomeTema} corrigidas com sucesso");
            
            return redirect()->route('dashboard.temas')->with('success', "Rotas do tema '{$nomeTema}' corrigidas com sucesso! As rotas serão registradas na próxima requisição.");
        } catch (\Exception $e) {
            \Log::error("Erro ao corrigir rotas do tema {$nomeTema}: " . $e->getMessage());
            return back()->withErrors(['tema' => 'Erro ao corrigir rotas: ' . $e->getMessage()]);
        }
    }

    /**
     * Garantir que todas as rotas necessárias do tema existam no banco de dados
     */
    private function garantirRotasTemaNoBanco($nomeTema)
    {
        try {
            $temaViewsPath = resource_path('views/temas/' . $nomeTema);
            
            // Verificar se o diretório de views existe
            if (!File::exists($temaViewsPath)) {
                \Log::warning("Diretório de views do tema {$nomeTema} não encontrado: {$temaViewsPath}");
                return;
            }

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

            // Rotas principais que não devem ser criadas
            $rotasPrincipais = ['/', '/sobre', '/contato', '/login', '/dashboard'];
            $paginasEspeciais = ['index', 'home', 'about', 'contact', 'sobre', 'contato'];

            foreach ($paginas as $pagina) {
                // Pular páginas especiais
                if (in_array($pagina, $paginasEspeciais)) {
                    continue;
                }

                // Verificar se a rota já existe no banco (case-insensitive)
                $rotaExistente = DB::table('rotas_dinamicas')
                    ->whereRaw('LOWER(tema) = ?', [strtolower($nomeTema)])
                    ->whereRaw('LOWER(pagina) = ?', [strtolower($pagina)])
                    ->first();

                if (!$rotaExistente) {
                    // Criar a rota no banco
                    $rota = '/' . $pagina;
                    $nomeRota = $pagina;

                    // Verificar se não conflita com rotas principais
                    if (!in_array($rota, $rotasPrincipais)) {
                        DB::table('rotas_dinamicas')->insert([
                            'tema' => $nomeTema, // Usar o nome exato do tema (preservar case)
                            'pagina' => $pagina,
                            'rota' => $rota,
                            'nome_rota' => $nomeRota,
                            'controller' => 'TemasController',
                            'metodo' => 'renderizarPaginaDinamica',
                            'ativo' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        \Log::info("Rota dinâmica criada no banco: {$rota} para tema {$nomeTema}, página {$pagina}");
                    }
                }
            }

        } catch (\Exception $e) {
            \Log::error("Erro ao garantir rotas do tema {$nomeTema} no banco: " . $e->getMessage());
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

    /**
     * NOVA FUNCIONALIDADE: Detectar automaticamente páginas detail_blogs
     */
    private function detectarPaginaDetailBlogs($request)
    {
        if (!$request->hasFile('arquivo_paginas')) {
            return false;
        }
        
        $arquivoPaginas = $request->file('arquivo_paginas');
        $tempPath = $arquivoPaginas->getPathname();
        
        try {
            $zip = new \ZipArchive();
            if ($zip->open($tempPath) !== TRUE) {
                return false;
            }
            
            // Verificar se existe arquivo detail_blogs.html ou detail_blogs.php
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $nomeArquivo = $zip->getNameIndex($i);
                $nomeBase = pathinfo($nomeArquivo, PATHINFO_FILENAME);
                
                if (strtolower($nomeBase) === 'blog') {
                    \Log::info('página blog encontrada no ZIP', [
                        'arquivo' => $nomeArquivo,
                        'extensao' => pathinfo($nomeArquivo, PATHINFO_EXTENSION)
                    ]);
                    $zip->close();
                    return true;
                }
            }
            
            $zip->close();
            return false;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao detectar detail_blogs: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * NOVA FUNCIONALIDADE: Validar estrutura dos arquivos ZIP
     */
    private function validarEstruturaArquivosZip($request)
    {
        $erros = [];
        
        // Validar ZIP dos assets
        if ($request->hasFile('arquivo_zip')) {
            $arquivoZip = $request->file('arquivo_zip');
            $errosAssets = $this->validarZipAssets($arquivoZip);
            if (!empty($errosAssets)) {
                $erros = array_merge($erros, $errosAssets);
            }
        }
        
        // Validar ZIP das páginas (se fornecido)
        if ($request->hasFile('arquivo_paginas')) {
            $arquivoPaginas = $request->file('arquivo_paginas');
            $errosPaginas = $this->validarZipPaginas($arquivoPaginas, $request);
            if (!empty($errosPaginas)) {
                $erros = array_merge($erros, $errosPaginas);
            }
        }
        
        // Se houver erros, retornar string de erro
        if (!empty($erros)) {
            $mensagemErro = 'Erros encontrados nos arquivos ZIP: ';
            foreach ($erros as $campo => $mensagens) {
                $mensagemErro .= implode(', ', $mensagens) . ' ';
            }
            return trim($mensagemErro);
        }
        
        return true;
    }

    /**
     * Validar ZIP dos assets
     */
    private function validarZipAssets($arquivoZip)
    {
        $erros = [];
        $zip = new ZipArchive;
        
        try {
            if ($zip->open($arquivoZip->getPathname()) !== TRUE) {
                $erros['arquivo_zip'] = ['O arquivo ZIP dos assets não é válido ou está corrompido.'];
                return $erros;
            }
            
            // Verificar se contém estrutura básica de assets
            $temAssets = false;
            $temCss = false;
            $temJs = false;
            $temImages = false;
            
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $nomeArquivo = $zip->getNameIndex($i);
                
                if (strpos($nomeArquivo, 'assets/') === 0) {
                    $temAssets = true;
                }
                if (strpos($nomeArquivo, 'assets/css/') === 0) {
                    $temCss = true;
                }
                if (strpos($nomeArquivo, 'assets/js/') === 0) {
                    $temJs = true;
                }
                if (strpos($nomeArquivo, 'assets/images/') === 0 || strpos($nomeArquivo, 'assets/img/') === 0) {
                    $temImages = true;
                }
            }
            
            if (!$temAssets) {
                $erros['arquivo_zip'] = ['O arquivo ZIP dos assets deve conter uma pasta "assets/" na raiz.'];
            }
            
            if (!$temCss && !$temJs && !$temImages) {
                $erros['arquivo_zip'] = ['O arquivo ZIP dos assets deve conter pelo menos CSS, JavaScript ou imagens na pasta assets/.'];
            }
            
            $zip->close();
            
        } catch (\Exception $e) {
            $erros['arquivo_zip'] = ['Erro ao validar arquivo ZIP dos assets: ' . $e->getMessage()];
        }
        
        return $erros;
    }

    /**
     * Validar ZIP das páginas
     */
    private function validarZipPaginas($arquivoPaginas, $request)
    {
        $erros = [];
        $zip = new ZipArchive;
        
        try {
            if ($zip->open($arquivoPaginas->getPathname()) !== TRUE) {
                $erros['arquivo_paginas'] = ['O arquivo ZIP das páginas não é válido ou está corrompido.'];
                return $erros;
            }
            
            $tamanhoArquivo = $arquivoPaginas->getSize();
            $numeroArquivos = $zip->numFiles;
            
            // Verificar tamanho mínimo (mais flexível)
            if ($tamanhoArquivo < 10240) { // 10KB
                $erros['arquivo_paginas'] = ['O arquivo ZIP das páginas parece estar vazio ou corrompido. Tamanho: ' . round($tamanhoArquivo / 1024, 2) . 'KB. Para páginas HTML, o arquivo deve ter pelo menos 10KB.'];
            }
            
            // Verificar número mínimo de arquivos (mais flexível)
            if ($numeroArquivos < 2) {
                $erros['arquivo_paginas'] = ["O arquivo ZIP das páginas contém apenas {$numeroArquivos} arquivos. Para páginas HTML, esperamos pelo menos 2 arquivos."];
            }
            
            // Verificar se contém arquivos HTML
            $temHtml = false;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $nomeArquivo = $zip->getNameIndex($i);
                if (str_ends_with(strtolower($nomeArquivo), '.html')) {
                    $temHtml = true;
                    break;
                }
            }
            
            if (!$temHtml) {
                $erros['arquivo_paginas'] = ['O arquivo ZIP das páginas deve conter pelo menos um arquivo .html.'];
            }
            
            $zip->close();
            
        } catch (\Exception $e) {
            $erros['arquivo_paginas'] = ['Erro ao validar arquivo ZIP das páginas: ' . $e->getMessage()];
        }
        
        return $erros;
    }

    /**
     * NOVA FUNCIONALIDADE: Correção automática de assets
     */
    private function corrigirAssetsAutomaticamente($temaPath, $nomeTema)
    {
        try {
            \Log::info("Iniciando correção automática de assets para tema {$nomeTema}");
            
            // Verificar e corrigir estrutura de diretórios
            $this->corrigirEstruturaDiretorios($temaPath);
            
            // Corrigir links quebrados
            $this->corrigirLinksQuebrados($temaPath, $nomeTema);
            
            // Corrigir permissões
            $this->corrigirPermissoes($temaPath);
            
            \Log::info("Correção automática de assets concluída para tema {$nomeTema}");
            
        } catch (\Exception $e) {
            \Log::error("Erro na correção automática de assets: " . $e->getMessage());
        }
    }

    /**
     * Corrigir estrutura de diretórios
     */
    private function corrigirEstruturaDiretorios($temaPath)
    {
        $diretoriosNecessarios = [
            'assets/css',
            'assets/js', 
            'assets/images',
            'assets/img'
        ];
        
        foreach ($diretoriosNecessarios as $diretorio) {
            $caminhoCompleto = $temaPath . '/' . $diretorio;
            if (!File::exists($caminhoCompleto)) {
                File::makeDirectory($caminhoCompleto, 0755, true);
                \Log::info("Diretório criado: {$caminhoCompleto}");
            }
        }
    }

    /**
     * Corrigir links quebrados
     */
    private function corrigirLinksQuebrados($temaPath, $nomeTema)
    {
        // Buscar todos os arquivos CSS e JS
        $arquivos = array_merge(
            File::glob($temaPath . '/assets/css/*.css'),
            File::glob($temaPath . '/assets/js/*.js')
        );
        
        foreach ($arquivos as $arquivo) {
            $conteudo = File::get($arquivo);
            $conteudoCorrigido = $this->corrigirLinksNoArquivo($conteudo, $nomeTema);
            
            if ($conteudo !== $conteudoCorrigido) {
                File::put($arquivo, $conteudoCorrigido);
                \Log::info("Links corrigidos em: " . basename($arquivo));
            }
        }
    }

    /**
     * Corrigir links em arquivo específico
     */
    private function corrigirLinksNoArquivo($conteudo, $nomeTema)
    {
        // Corrigir URLs relativas quebradas
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
        
        return $conteudo;
    }

    /**
     * Corrigir permissões
     */
    private function corrigirPermissoes($temaPath)
    {
        try {
            // Definir permissões corretas para arquivos e diretórios
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($temaPath, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            
            foreach ($iterator as $item) {
                if ($item->isDir()) {
                    chmod($item->getPathname(), 0755);
                } else {
                    chmod($item->getPathname(), 0644);
                }
            }
            
        } catch (\Exception $e) {
            \Log::warning("Erro ao corrigir permissões: " . $e->getMessage());
        }
    }

    /**
     * NOVA FUNCIONALIDADE: Validação de rotas dinâmicas melhorada
     */
    private function validarRotasDinamicas($nomeTema, $paginas)
    {
        try {
            $conflitos = [];
            $rotasPrincipais = ['/', '/sobre', '/contato', '/login', '/blog', '/dashboard'];
            $rotasExistentes = [];
            
            // Verificar rotas já existentes no banco
            $rotasBanco = \DB::table('rotas_dinamicas')
                ->where('ativo', 1)
                ->pluck('rota')
                ->toArray();
            
            foreach ($paginas as $pagina) {
                $rota = '/' . strtolower($pagina);
                
                // Verificar conflito com rotas principais
                if (in_array($rota, $rotasPrincipais)) {
                    $conflitos[] = "{$pagina} (conflito com rota principal)";
                }
                
                // Verificar conflito com rotas existentes
                if (in_array($rota, $rotasBanco)) {
                    $conflitos[] = "{$pagina} (rota já existe)";
                }
                
                // Verificar caracteres especiais
                if (!preg_match('/^[a-z0-9_-]+$/', strtolower($pagina))) {
                    $conflitos[] = "{$pagina} (contém caracteres inválidos)";
                }
            }
            
            // Verificar se há páginas detail_blogs
            $temDetailBlogs = in_array('blog', array_map('strtolower', $paginas));
            if ($temDetailBlogs) {
                \Log::info("página blog detectada - configurando sistema de blog", [
                    'tema' => $nomeTema,
                    'pagina' => 'blog'
                ]);
                
                // Configurar automaticamente o sistema de blog
                $this->configurarSistemaBlog($nomeTema);
            }
            
            if (!empty($conflitos)) {
                \Log::warning("Conflitos de rotas detectados: " . implode(', ', $conflitos));
            }
            
            return $conflitos;
            
        } catch (\Exception $e) {
            \Log::error("Erro ao validar rotas dinâmicas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * NOVA FUNCIONALIDADE: Configurar sistema de blog automaticamente
     */
    private function configurarSistemaBlog($nomeTema)
    {
        try {
            // Verificar se já existe configuração de blog para este tema
            $configExistente = \DB::table('head_configs')
                ->where('tema', $nomeTema)
                ->where('pagina', 'blog')
                ->first();
            
            if (!$configExistente) {
                // Criar configuração para página blog
                \DB::table('head_configs')->insert([
                    'tema' => $nomeTema,
                    'pagina' => 'blog',
                    'meta_title' => 'Blog - ' . $nomeTema,
                    'meta_description' => 'Página de blog do tema ' . $nomeTema,
                    'meta_keywords' => 'blog, ' . strtolower($nomeTema),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                \Log::info("Configuração de blog criada automaticamente", [
                    'tema' => $nomeTema,
                    'pagina' => 'blog'
                ]);
            }
            
            // Garantir que a rota detail_blogs/{slug} seja criada
            $rotaDetailBlogs = \DB::table('rotas_dinamicas')
                ->where('tema', $nomeTema)
                ->where('pagina', 'blog')
                ->first();
            
            if (!$rotaDetailBlogs) {
                \DB::table('rotas_dinamicas')->insert([
                    'tema' => $nomeTema,
                    'pagina' => 'blog',
                    'rota' => '/detail-blogs/{slug}',
                    'nome_rota' => 'blog',
                    'controller' => 'TemasController',
                    'metodo' => 'renderizarPaginaDinamica',
                    'ativo' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                \Log::info("Rota detail-blogs/{slug} criada automaticamente", [
                    'tema' => $nomeTema
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error("Erro ao configurar sistema de blog: " . $e->getMessage());
        }
    }

    /**
     * NOVA FUNCIONALIDADE: Sistema de rollback
     */
    private function executarRollback($temaPath, $temaViewsPath, $nomeTema)
    {
        try {
            \Log::info("=== INICIANDO ROLLBACK PARA TEMA {$nomeTema} ===");
            
            $erros = [];
            
            // 1. Remover assets
            if (File::exists($temaPath)) {
                try {
                    File::deleteDirectory($temaPath);
                    \Log::info("✅ Assets removidos: {$temaPath}");
                } catch (\Exception $e) {
                    $erros[] = "Erro ao remover assets: " . $e->getMessage();
                }
            }
            
            // 2. Remover views
            if (File::exists($temaViewsPath)) {
                try {
                    File::deleteDirectory($temaViewsPath);
                    \Log::info("✅ Views removidas: {$temaViewsPath}");
                } catch (\Exception $e) {
                    $erros[] = "Erro ao remover views: " . $e->getMessage();
                }
            }
            
            // 3. Remover dados do banco
            try {
                \DB::beginTransaction();
                
                // Remover rotas dinâmicas
                $rotasRemovidas = \DB::table('rotas_dinamicas')->where('tema', $nomeTema)->delete();
                \Log::info("✅ Rotas dinâmicas removidas: {$rotasRemovidas} registros");
                
                // Remover configurações
                $configsRemovidas = \DB::table('head_configs')->where('tema', $nomeTema)->delete();
                \Log::info("✅ Configurações removidas: {$configsRemovidas} registros");
                
                // Remover registro do tema
                $temaRemovido = \DB::table('temas')->where('slug', $nomeTema)->delete();
                \Log::info("✅ Registro do tema removido: {$temaRemovido} registros");
                
                \DB::commit();
                
            } catch (\Exception $e) {
                \DB::rollback();
                $erros[] = "Erro ao remover dados do banco: " . $e->getMessage();
            }
            
            // 4. Limpar cache
            try {
                \Artisan::call('route:clear');
                \Artisan::call('view:clear');
                \Artisan::call('cache:clear');
                \Log::info("✅ Cache limpo");
            } catch (\Exception $e) {
                $erros[] = "Erro ao limpar cache: " . $e->getMessage();
            }
            
            if (!empty($erros)) {
                \Log::error("⚠️ Rollback concluído com erros:", $erros);
            } else {
                \Log::info("✅ Rollback concluído com sucesso para tema {$nomeTema}");
            }
            
        } catch (\Exception $e) {
            \Log::error("❌ Erro crítico durante rollback: " . $e->getMessage());
        }
    }

    /**
     * Determinar tipo de erro para mensagem mais específica
     */
    private function determinarTipoErro(\Exception $e)
    {
        $mensagem = $e->getMessage();
        
        // Erros de memória
        if (strpos($mensagem, 'memory') !== false || strpos($mensagem, 'Memory') !== false) {
            return 'Erro de memória insuficiente. Tente reduzir o tamanho dos arquivos ZIP ou contate o administrador.';
        }
        
        // Erros de timeout
        if (strpos($mensagem, 'timeout') !== false || strpos($mensagem, 'Time') !== false) {
            return 'Timeout durante o processamento. O arquivo pode ser muito grande. Tente arquivos menores.';
        }
        
        // Erros de ZIP
        if (strpos($mensagem, 'zip') !== false || strpos($mensagem, 'ZIP') !== false) {
            return 'Erro ao processar arquivo ZIP. Verifique se o arquivo não está corrompido.';
        }
        
        // Erros de permissão
        if (strpos($mensagem, 'permission') !== false || strpos($mensagem, 'Permission') !== false) {
            return 'Erro de permissão de arquivo. Contate o administrador do sistema.';
        }
        
        // Erros de banco de dados
        if (strpos($mensagem, 'database') !== false || strpos($mensagem, 'Database') !== false) {
            return 'Erro de banco de dados. Contate o administrador do sistema.';
        }
        
        // Erro genérico
        return 'Erro inesperado durante a instalação: ' . $mensagem;
    }
}
