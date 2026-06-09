<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\HeadHelper;
use App\Helpers\ThemeHelper;

class ThemePageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            /** @var \App\Models\Usuario $user */
            $user = Auth::user();
            if (!$user || !$user->isAdmin()) {
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json(['error' => 'Acesso negado. Você não tem permissão de administrador.'], 403);
                }
                return redirect()->route('dashboard')->with('error', 'Acesso negado. Você não tem permissão de administrador.');
            }
            return $next($request);
        });
    }
    
    /**
     * Listar todas as páginas do tema ativo
     */
    public function index(Request $request)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['error' => 'As configurações de páginas são aplicadas apenas aos temas personalizados.'], 400);
            }
            return redirect()->route('dashboard.temas')->with('info', 'As configurações de páginas são aplicadas apenas aos temas personalizados.');
        }
        
        // Verificar se o tema personalizado existe
        $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
        if (!file_exists($temaViewsPath)) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['error' => 'Tema personalizado não encontrado.'], 404);
            }
            return redirect()->route('dashboard.temas')->with('error', 'Tema personalizado não encontrado.');
        }
        
        // Obter páginas do tema
        $paginas = $this->obterPaginasDoTema($temaAtivo);
        
        // Garantir que todas as páginas tenham rotas dinâmicas criadas
        foreach ($paginas as $pagina) {
            $this->garantirRotaDinamica($temaAtivo, $pagina);
        }
        
        // Obter configurações existentes
        $configuracoes = HeadHelper::getAllConfigs($temaAtivo);
        
        // Obter informações de rotas para cada página
        $rotasPaginas = [];
        foreach ($paginas as $pagina) {
            $rotasPaginas[$pagina] = $this->obterRotaPagina($temaAtivo, $pagina);
        }
        
        return view('dashboard.theme-pages.index', compact('paginas', 'configuracoes', 'temaAtivo', 'rotasPaginas'));
    }
    
    /**
     * Mostrar formulário para uma página específica
     */
    public function show($pagina)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            return redirect()->route('dashboard.temas')->with('error', 'As configurações de páginas são aplicadas apenas aos temas personalizados.');
        }
        
        // Verificar se a página existe no tema
        $paginas = $this->obterPaginasDoTema($temaAtivo);
        if (!in_array($pagina, $paginas)) {
            return redirect()->route('dashboard.theme-pages')->with('error', 'Página não encontrada no tema.');
        }
        
        // Garantir que a configuração existe no banco de dados
        $configExistente = DB::table('head_configs')
            ->where('pagina', $pagina)
            ->where('tema', $temaAtivo)
            ->first();
        
        if (!$configExistente) {
            // Criar configuração inicial vazia se não existir
            DB::table('head_configs')->insert([
                'pagina' => $pagina,
                'tema' => $temaAtivo,
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Limpar cache para forçar recarregamento
            \App\Helpers\HeadHelper::clearCache($pagina, $temaAtivo);
        }
        
        // Obter configuração da página
        $configuracao = HeadHelper::getConfigs($pagina, $temaAtivo);
        
        // Converter datas para Carbon se necessário (apenas se as propriedades existirem)
        if ($configuracao && isset($configuracao->created_at) && $configuracao->created_at) {
            $configuracao->created_at = \Carbon\Carbon::parse($configuracao->created_at);
        }
        if ($configuracao && isset($configuracao->updated_at) && $configuracao->updated_at) {
            $configuracao->updated_at = \Carbon\Carbon::parse($configuracao->updated_at);
        }
        
        // Obter formulários de conteúdo existentes
        $formularios = DB::table('content_forms')
            ->where('tema', $temaAtivo)
            ->where('pagina', $pagina)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard.theme-pages.show', compact('pagina', 'configuracao', 'temaAtivo', 'formularios'));
    }
    
    /**
     * Atualizar configurações de uma página
     */
    public function update(Request $request, $pagina)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            return redirect()->route('dashboard.temas')->with('error', 'As configurações de páginas são aplicadas apenas aos temas personalizados.');
        }
        
        $request->validate([
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:255',
        ]);
        
        try {
            // Verificar se a configuração já existe
            $configExistente = DB::table('head_configs')
                ->where('pagina', $pagina)
                ->where('tema', $temaAtivo)
                ->first();
            
            if ($configExistente) {
                // Atualizar configuração existente
                DB::table('head_configs')
                    ->where('id', $configExistente->id)
                    ->update([
                        'meta_title' => $request->input('meta_title'),
                        'meta_description' => $request->input('meta_description'),
                        'meta_keywords' => $request->input('meta_keywords'),
                        'updated_at' => now(),
                    ]);
            } else {
                // Criar nova configuração
                DB::table('head_configs')->insert([
                    'pagina' => $pagina,
                    'tema' => $temaAtivo,
                    'meta_title' => $request->input('meta_title'),
                    'meta_description' => $request->input('meta_description'),
                    'meta_keywords' => $request->input('meta_keywords'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Limpar cache do HeadHelper para esta página e tema
            \App\Helpers\HeadHelper::clearCache($pagina, $temaAtivo);
            
            // Limpar cache de views e config para garantir que as mudanças sejam refletidas
            \Artisan::call('view:clear');
            \Artisan::call('config:clear');
            
            return redirect()->route('dashboard.theme-pages.show', $pagina)
                ->with('success', 'Configurações da página "' . ucfirst($pagina) . '" atualizadas com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar configurações da página: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar configurações. Tente novamente.');
        }
    }
    
    /**
     * Obter todas as páginas de um tema
     */
    private function obterPaginasDoTema($tema)
    {
        $temaViewsPath = resource_path('views/temas/' . $tema);
        
        if (!file_exists($temaViewsPath)) {
            return [];
        }
        
        $paginas = collect(File::files($temaViewsPath))
            ->filter(function($arquivo) {
                $nome = $arquivo->getFilename();
                $caminho = $arquivo->getPathname();
                
                // Incluir apenas arquivos .blade.php que não estão em subdiretórios
                return str_ends_with($nome, '.blade.php') && 
                       !str_contains($caminho, 'inc') &&
                       !str_contains($caminho, 'layouts') &&
                       !str_contains($caminho, 'auth') &&
                       !str_contains($caminho, '\\inc\\') &&
                       !str_contains($caminho, '\\layouts\\') &&
                       !str_contains($caminho, '\\auth\\');
            })
            ->map(function($arquivo) {
                return strtolower(basename($arquivo->getFilename(), '.blade.php'));
            })
            ->sort()
            ->values()
            ->toArray();
        
        return $paginas;
    }
    
    /**
     * Duplicar uma página do tema
     */
    public function duplicate(Request $request, $pagina)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            return redirect()->route('dashboard.theme-pages')->with('error', 'As configurações de páginas são aplicadas apenas aos temas personalizados.');
        }
        
        $request->validate([
            'new_page_name' => 'required|string|max:50|regex:/^[a-zA-Z0-9-]+$/',
        ], [
            'new_page_name.required' => 'O nome da nova página é obrigatório.',
            'new_page_name.regex' => 'O nome da página deve conter apenas letras, números e hífen (-).',
        ]);
        
        $newPageName = strtolower($request->input('new_page_name'));
        $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
        
        try {
            // Verificar se a página original existe
            $originalPagePath = $temaViewsPath . '/' . $pagina . '.blade.php';
            if (!File::exists($originalPagePath)) {
                return redirect()->route('dashboard.theme-pages')->with('error', 'Página original não encontrada.');
            }
            
            // Verificar se a nova página já existe
            $newPagePath = $temaViewsPath . '/' . $newPageName . '.blade.php';
            if (File::exists($newPagePath)) {
                return redirect()->route('dashboard.theme-pages')->with('error', 'Já existe uma página com esse nome.');
            }
            
            // Copiar o arquivo da página
            File::copy($originalPagePath, $newPagePath);
            
            // Copiar configurações da página original (se existirem)
            $configOriginal = DB::table('head_configs')
                ->where('pagina', $pagina)
                ->where('tema', $temaAtivo)
                ->first();
            
            if ($configOriginal) {
                DB::table('head_configs')->insert([
                    'pagina' => $newPageName,
                    'tema' => $temaAtivo,
                    'meta_title' => $configOriginal->meta_title,
                    'meta_description' => $configOriginal->meta_description,
                    'meta_keywords' => $configOriginal->meta_keywords,
                    'email_formulario' => $configOriginal->email_formulario,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Criar rota dinâmica para a nova página
            $this->criarRotaDinamica($temaAtivo, $newPageName);
            
            return redirect()->route('dashboard.theme-pages')->with('success', 
                'Página "' . ucfirst($newPageName) . '" criada com sucesso! A rota dinâmica foi configurada automaticamente.');
                
        } catch (\Exception $e) {
            Log::error('Erro ao duplicar página: ' . $e->getMessage());
            return redirect()->route('dashboard.theme-pages')->with('error', 
                'Erro ao duplicar página. Tente novamente.');
        }
    }
    
    /**
     * Renomear uma página do tema
     */
    public function rename(Request $request, $pagina)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            return redirect()->route('dashboard.theme-pages')->with('error', 'As páginas do tema principal não podem ser renomeadas.');
        }
        
        // Verificar se a página existe no tema
        $paginas = $this->obterPaginasDoTema($temaAtivo);
        if (!in_array($pagina, $paginas)) {
            return redirect()->route('dashboard.theme-pages')->with('error', 'Página não encontrada no tema.');
        }
        
        // Validar novo nome
        $request->validate([
            'novo_nome_pagina' => 'required|string|max:255|regex:/^[a-zA-Z0-9-]+$/',
        ], [
            'novo_nome_pagina.required' => 'O novo nome da página é obrigatório.',
            'novo_nome_pagina.regex' => 'O nome da página deve conter apenas letras, números e hífen (-).',
        ]);
        
        $novoNome = strtolower(trim($request->input('novo_nome_pagina')));
        $novoNome = preg_replace('/[^a-z0-9-]/', '-', $novoNome);
        $novoNome = preg_replace('/-+/', '-', $novoNome);
        $novoNome = trim($novoNome, '-');
        
        // Verificar se o novo nome é válido
        if (empty($novoNome) || !preg_match('/^[a-z0-9-]+$/', $novoNome)) {
            return redirect()->route('dashboard.theme-pages')->with('error', 'Nome inválido. Use apenas letras, números e hífen (-).');
        }
        
        // Verificar se já existe uma página com esse nome
        if (in_array($novoNome, $paginas) && $novoNome !== $pagina) {
            return redirect()->route('dashboard.theme-pages')->with('error', 'Já existe uma página com esse nome.');
        }
        
        // Verificar se é uma página essencial (não pode ser renomeada para nome diferente)
        $paginasEssenciais = ['home', 'sobre', 'contato'];
        if (in_array($pagina, $paginasEssenciais) && $novoNome !== $pagina) {
            return redirect()->route('dashboard.theme-pages')->with('error', 'Páginas essenciais (home, sobre, contato) não podem ser renomeadas.');
        }
        
        try {
            $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
            $arquivoAntigo = $temaViewsPath . '/' . $pagina . '.blade.php';
            $arquivoNovo = $temaViewsPath . '/' . $novoNome . '.blade.php';
            
            // Verificar se o arquivo antigo existe
            if (!File::exists($arquivoAntigo)) {
                return redirect()->route('dashboard.theme-pages')->with('error', 'Arquivo da página não encontrado.');
            }
            
            // Verificar se já existe arquivo com o novo nome
            if (File::exists($arquivoNovo) && $novoNome !== $pagina) {
                return redirect()->route('dashboard.theme-pages')->with('error', 'Já existe um arquivo com esse nome.');
            }
            
            // Renomear arquivo
            File::move($arquivoAntigo, $arquivoNovo);
            Log::info("Arquivo renomeado: {$pagina}.blade.php → {$novoNome}.blade.php");
            
            // Atualizar rota dinâmica no banco
            $rotaAntiga = DB::table('rotas_dinamicas')
                ->where('tema', $temaAtivo)
                ->where('pagina', $pagina)
                ->first();
            
            // Rotas principais que não devem ter rotas dinâmicas
            $rotasPrincipais = ['/', '/sobre', '/contato', '/login', '/dashboard', '/blog'];
            
            if ($rotaAntiga) {
                // Gerar nova rota baseada no novo nome
                $novaRota = '/' . str_replace('_', '-', $novoNome);
                $novoNomeRota = str_replace('_', '-', $novoNome);
                
                // Se a rota antiga tinha {slug}, manter na nova rota
                if (str_contains($rotaAntiga->rota, '{slug}')) {
                    $novaRota = $novaRota . '/{slug}';
                }
                
                // Verificar se a nova rota conflita com rotas principais
                $rotaBase = str_replace('/{slug}', '', $novaRota);
                if (in_array($rotaBase, $rotasPrincipais)) {
                    // Se conflitar, desativar a rota dinâmica em vez de atualizar
                    DB::table('rotas_dinamicas')
                        ->where('tema', $temaAtivo)
                        ->where('pagina', $pagina)
                        ->update([
                            'ativo' => 0,
                            'updated_at' => now(),
                        ]);
                    Log::info("Rota dinâmica desativada devido a conflito com rota principal: {$novaRota}");
                } else {
                    // Atualizar rota dinâmica
                    DB::table('rotas_dinamicas')
                        ->where('tema', $temaAtivo)
                        ->where('pagina', $pagina)
                        ->update([
                            'pagina' => $novoNome,
                            'rota' => $novaRota,
                            'nome_rota' => $novoNomeRota,
                            'updated_at' => now(),
                        ]);
                    
                    Log::info("Rota dinâmica atualizada: {$pagina} → {$novoNome} (rota: {$novaRota})");
                }
            } else {
                // Verificar se a nova rota conflita antes de criar
                $novaRota = '/' . str_replace('_', '-', $novoNome);
                $rotaBase = str_replace('/{slug}', '', $novaRota);
                if (!in_array($rotaBase, $rotasPrincipais)) {
                    // Criar nova rota dinâmica se não existir e não conflitar
                    $this->criarRotaDinamica($temaAtivo, $novoNome);
                } else {
                    Log::info("Rota dinâmica não criada devido a conflito com rota principal: {$novaRota}");
                }
            }
            
            // Atualizar configurações no banco (head_configs)
            DB::table('head_configs')
                ->where('pagina', $pagina)
                ->where('tema', $temaAtivo)
                ->update([
                    'pagina' => $novoNome,
                    'updated_at' => now(),
                ]);
            
            // Atualizar content_forms no banco
            DB::table('content_forms')
                ->where('pagina', $pagina)
                ->where('tema', $temaAtivo)
                ->update([
                    'pagina' => $novoNome,
                    'updated_at' => now(),
                ]);
            
            // Fazer varredura e atualizar referências nas outras páginas do tema
            $this->atualizarReferenciasPagina($temaAtivo, $pagina, $novoNome);
            
            // Atualizar referências em arquivos PHP (controllers, routes, etc.)
            $this->atualizarReferenciasEmArquivosPHP($temaAtivo, $pagina, $novoNome);
            
            // Limpar cache
            \App\Helpers\HeadHelper::clearCache($novoNome, $temaAtivo);
            if ($novoNome !== $pagina) {
                \App\Helpers\HeadHelper::clearCache($pagina, $temaAtivo);
            }
            
            Log::info("Página '{$pagina}' renomeada para '{$novoNome}' no tema '{$temaAtivo}' com sucesso");
            
            return redirect()->route('dashboard.theme-pages')->with('success', 
                'Página "' . ucfirst($pagina) . '" renomeada para "' . ucfirst($novoNome) . '" com sucesso! Todas as referências foram atualizadas.');
                
        } catch (\Exception $e) {
            Log::error('Erro ao renomear página: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('dashboard.theme-pages')->with('error', 
                'Erro ao renomear página: ' . $e->getMessage());
        }
    }
    
    /**
     * Atualizar referências de uma página renomeada em todas as outras páginas do tema
     */
    private function atualizarReferenciasPagina($tema, $nomeAntigo, $nomeNovo)
    {
        try {
            $temaViewsPath = resource_path('views/temas/' . $tema);
            
            if (!file_exists($temaViewsPath)) {
                return;
            }
            
            // Obter todas as páginas do tema
            $todasPaginas = $this->obterPaginasDoTema($tema);
            
            // Normalizar nomes para busca (considerar diferentes formatos)
            // Usar a função normalize_route_name para gerar o formato correto usado nas rotas
            $nomeAntigoNormalizado = normalize_route_name($nomeAntigo);
            $nomeNovoNormalizado = normalize_route_name($nomeNovo);
            
            $nomeAntigoVariacoes = [
                $nomeAntigo, // kebab-case original
                $nomeAntigoNormalizado, // PascalCase normalizado
                str_replace('-', '', $nomeAntigo), // sem hífen
                str_replace('-', '_', $nomeAntigo), // underscore
                ucfirst(str_replace('-', '', ucwords($nomeAntigo, '-'))), // CamelCase
                str_replace('-', '', ucwords($nomeAntigo, '-')), // PascalCase manual
            ];
            
            $nomeNovoVariacoes = [
                $nomeNovo, // kebab-case original
                $nomeNovoNormalizado, // PascalCase normalizado
                str_replace('-', '', $nomeNovo),
                str_replace('-', '_', $nomeNovo),
                ucfirst(str_replace('-', '', ucwords($nomeNovo, '-'))),
                str_replace('-', '', ucwords($nomeNovo, '-')),
            ];
            
            // Padrões de busca para referências à página antiga
            $padroes = [];
            $substituicoes = [];
            
            // Normalizar nome do tema também
            $temaNormalizado = normalize_route_name($tema);
            
            // Para cada variação do nome antigo, criar padrões
            foreach ($nomeAntigoVariacoes as $index => $variacaoAntiga) {
                $variacaoNova = $nomeNovoVariacoes[$index] ?? $nomeNovoNormalizado;
                
                // Escapar caracteres especiais para regex
                $variacaoAntigaEscapada = preg_quote($variacaoAntiga, '/');
                
                // Rotas nomeadas: route('tema.Tema.pagina-antiga') ou route("tema.Tema.pagina-antiga")
                // Considerar tanto o tema original quanto normalizado
                $temaEscapado = preg_quote($tema, '/');
                $temaNormalizadoEscapado = preg_quote($temaNormalizado, '/');
                
                // Padrão com tema original
                $padroes[] = "/route\(['\"]tema\.{$temaEscapado}\.{$variacaoAntigaEscapada}['\"]\)/i";
                $substituicoes[] = "route('tema.{$temaNormalizado}.{$variacaoNova}')";
                
                // Padrão com tema normalizado
                $padroes[] = "/route\(['\"]tema\.{$temaNormalizadoEscapado}\.{$variacaoAntigaEscapada}['\"]\)/i";
                $substituicoes[] = "route('tema.{$temaNormalizado}.{$variacaoNova}')";
            }
            
            // Padrões gerais (sem variações)
            $nomeAntigoEscapado = preg_quote($nomeAntigo, '/');
            $nomeNovoEscapado = $nomeNovo;
            
            // URLs: /pagina-antiga ou /pagina-antiga/
            $padroes[] = "/\/({$nomeAntigoEscapado})(?:\/|['\"\s>])/i";
            $substituicoes[] = "/{$nomeNovoEscapado}$1";
            
            // URLs com .php: /pagina-antiga.php
            $padroes[] = "/\/({$nomeAntigoEscapado})\.php/i";
            $substituicoes[] = "/{$nomeNovoEscapado}.php";
            
            // Nomes de arquivo: pagina-antiga.blade.php
            $padroes[] = "/({$nomeAntigoEscapado})\.blade\.php/i";
            $substituicoes[] = "{$nomeNovoEscapado}.blade.php";
            
            // Referências em href: href="pagina-antiga" ou href="/pagina-antiga"
            $padroes[] = "/(href=['\"]\/?)({$nomeAntigoEscapado})(['\"\s>])/i";
            $substituicoes[] = "$1{$nomeNovoEscapado}$3";
            
            // Referências em action: action="pagina-antiga"
            $padroes[] = "/(action=['\"]\/?)({$nomeAntigoEscapado})(['\"\s>])/i";
            $substituicoes[] = "$1{$nomeNovoEscapado}$3";
            
            // Referências em links com .php: href="pagina-antiga.php"
            $padroes[] = "/(href=['\"]\/?)({$nomeAntigoEscapado})\.php(['\"\s>])/i";
            $substituicoes[] = "$1{$nomeNovoEscapado}.php$3";
            
            $arquivosAtualizados = 0;
            
            foreach ($todasPaginas as $outraPagina) {
                // Pular a própria página que foi renomeada
                if ($outraPagina === $nomeNovo) {
                    continue;
                }
                
                $arquivoPagina = $temaViewsPath . '/' . $outraPagina . '.blade.php';
                
                if (!File::exists($arquivoPagina)) {
                    continue;
                }
                
                // Ler conteúdo do arquivo
                $conteudo = File::get($arquivoPagina);
                $conteudoOriginal = $conteudo;
                
                // Aplicar substituições
                foreach ($padroes as $index => $padrao) {
                    $conteudo = preg_replace($padrao, $substituicoes[$index], $conteudo);
                }
                
                // Se houve alterações, salvar arquivo
                if ($conteudo !== $conteudoOriginal) {
                    File::put($arquivoPagina, $conteudo);
                    $arquivosAtualizados++;
                    Log::info("Referências atualizadas em: {$outraPagina}.blade.php");
                }
            }
            
            // Atualizar também arquivos em subdiretórios (inc, layouts)
            $subdiretorios = ['inc', 'layouts'];
            foreach ($subdiretorios as $subdir) {
                $subdirPath = $temaViewsPath . '/' . $subdir;
                if (file_exists($subdirPath)) {
                    $arquivos = File::allFiles($subdirPath);
                    foreach ($arquivos as $arquivo) {
                        if ($arquivo->getExtension() === 'php' || str_ends_with($arquivo->getFilename(), '.blade.php')) {
                            $conteudo = File::get($arquivo->getPathname());
                            $conteudoOriginal = $conteudo;
                            
                            foreach ($padroes as $index => $padrao) {
                                $conteudo = preg_replace($padrao, $substituicoes[$index], $conteudo);
                            }
                            
                            if ($conteudo !== $conteudoOriginal) {
                                File::put($arquivo->getPathname(), $conteudo);
                                $arquivosAtualizados++;
                                Log::info("Referências atualizadas em: {$subdir}/{$arquivo->getFilename()}");
                            }
                        }
                    }
                }
            }
            
            Log::info("Total de arquivos atualizados: {$arquivosAtualizados}");
            
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar referências da página: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Atualizar referências de uma página renomeada em arquivos PHP (controllers, routes, etc.)
     */
    private function atualizarReferenciasEmArquivosPHP($tema, $nomeAntigo, $nomeNovo)
    {
        try {
            // Normalizar nomes
            $nomeAntigoNormalizado = normalize_route_name($nomeAntigo);
            $nomeNovoNormalizado = normalize_route_name($nomeNovo);
            
            // Padrões de busca para arquivos PHP
            $padroes = [];
            $substituicoes = [];
            
            // Escapar para regex
            $nomeAntigoEscapado = preg_quote($nomeAntigo, '/');
            $nomeAntigoNormalizadoEscapado = preg_quote($nomeAntigoNormalizado, '/');
            
            // Padrões para referências em PHP
            // 1. Referências diretas: 'blog' ou "blog"
            $padroes[] = "/(['\"]){$nomeAntigoEscapado}(['\"])/";
            $substituicoes[] = "$1{$nomeNovo}$2";
            
            // 2. Referências normalizadas: 'DetailBlogs' ou "DetailBlogs"
            $padroes[] = "/(['\"]){$nomeAntigoNormalizadoEscapado}(['\"])/";
            $substituicoes[] = "$1{$nomeNovoNormalizado}$2";
            
            // 3. Referências em where: ->where('pagina', 'blog')
            $padroes[] = "/->where\(['\"]pagina['\"],\s*['\"]{$nomeAntigoEscapado}['\"]\)/";
            $substituicoes[] = "->where('pagina', '{$nomeNovo}')";
            
            // 4. Referências em comparações: $pagina === 'blog'
            $padroes[] = "/(\\\$[a-zA-Z_][a-zA-Z0-9_]*\s*===\s*['\"]){$nomeAntigoEscapado}(['\"])/";
            $substituicoes[] = "$1{$nomeNovo}$2";
            
            // 5. Referências em in_array: in_array('blog', ...)
            $padroes[] = "/in_array\(['\"]{$nomeAntigoEscapado}['\"],/";
            $substituicoes[] = "in_array('{$nomeNovo}',";
            
            // 6. Referências em rotas: ->where('pagina', 'blog')
            $padroes[] = "/where\(['\"]pagina['\"],\s*['\"]{$nomeAntigoEscapado}['\"]\)/";
            $substituicoes[] = "where('pagina', '{$nomeNovo}')";
            
            // 7. Referências em comentários e logs (opcional, mas útil)
            $padroes[] = "/página\s+{$nomeAntigoEscapado}/i";
            $substituicoes[] = "página {$nomeNovo}";
            
            $arquivosAtualizados = 0;
            
            // Diretórios para buscar
            $diretorios = [
                app_path('Http/Controllers'),
                base_path('routes'),
            ];
            
            foreach ($diretorios as $diretorio) {
                if (!file_exists($diretorio)) {
                    continue;
                }
                
                // Buscar todos os arquivos PHP
                $arquivos = File::allFiles($diretorio);
                
                foreach ($arquivos as $arquivo) {
                    if ($arquivo->getExtension() !== 'php') {
                        continue;
                    }
                    
                    $caminhoArquivo = $arquivo->getPathname();
                    
                    // Pular arquivos de cache/vendor se existirem
                    if (str_contains($caminhoArquivo, '/vendor/') || 
                        str_contains($caminhoArquivo, '/storage/framework/')) {
                        continue;
                    }
                    
                    // Ler conteúdo
                    $conteudo = File::get($caminhoArquivo);
                    $conteudoOriginal = $conteudo;
                    
                    // Aplicar substituições
                    foreach ($padroes as $index => $padrao) {
                        $conteudo = preg_replace($padrao, $substituicoes[$index], $conteudo);
                    }
                    
                    // Se houve alterações, salvar arquivo
                    if ($conteudo !== $conteudoOriginal) {
                        File::put($caminhoArquivo, $conteudo);
                        $arquivosAtualizados++;
                        Log::info("Referências atualizadas em arquivo PHP: {$caminhoArquivo}");
                    }
                }
            }
            
            Log::info("Total de arquivos PHP atualizados: {$arquivosAtualizados}");
            
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar referências em arquivos PHP: ' . $e->getMessage());
            // Não lançar exceção para não interromper o processo de renomeação
        }
    }
    
    /**
     * Excluir uma página do tema
     */
    public function destroy($pagina)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            return redirect()->route('dashboard.temas')->with('error', 'As páginas do tema principal não podem ser excluídas.');
        }
        
        // Verificar se a página existe no tema
        $paginas = $this->obterPaginasDoTema($temaAtivo);
        if (!in_array($pagina, $paginas)) {
            return redirect()->route('dashboard.theme-pages')->with('error', 'Página não encontrada no tema.');
        }
        
        // Verificar se é uma página essencial (não pode ser excluída)
        $paginasEssenciais = ['home', 'sobre', 'contato'];
        if (in_array($pagina, $paginasEssenciais)) {
            return redirect()->route('dashboard.theme-pages')->with('error', 'Páginas essenciais (home, sobre, contato) não podem ser excluídas.');
        }
        
        try {
            $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
            $arquivoPagina = $temaViewsPath . '/' . $pagina . '.blade.php';
            
            // Verificar se o arquivo existe
            if (!File::exists($arquivoPagina)) {
                return redirect()->route('dashboard.theme-pages')->with('error', 'Arquivo da página não encontrado.');
            }
            
            // Excluir arquivo da página
            File::delete($arquivoPagina);
            
            // Excluir configurações da página (se existirem)
            DB::table('head_configs')
                ->where('pagina', $pagina)
                ->where('tema', $temaAtivo)
                ->delete();
            
            // Excluir rota dinâmica (se existir)
            DB::table('rotas_dinamicas')
                ->where('tema', $temaAtivo)
                ->where('pagina', $pagina)
                ->delete();
            
            Log::info("Página '{$pagina}' excluída do tema '{$temaAtivo}' com sucesso");
            
            return redirect()->route('dashboard.theme-pages')->with('success', 
                'Página "' . ucfirst($pagina) . '" excluída com sucesso! Arquivo, configurações e rota dinâmica foram removidos.');
                
        } catch (\Exception $e) {
            Log::error('Erro ao excluir página: ' . $e->getMessage());
            return redirect()->route('dashboard.theme-pages')->with('error', 
                'Erro ao excluir página. Tente novamente.');
        }
    }
    
    /**
     * Garantir que uma rota dinâmica existe para uma página
     */
    private function garantirRotaDinamica($tema, $pagina)
    {
        try {
            // Verificar se já existe uma rota para esta página
            $rotaExistente = DB::table('rotas_dinamicas')
                ->where('tema', $tema)
                ->where('pagina', $pagina)
                ->first();
            
            if ($rotaExistente) {
                return;
            }
            
            // Criar a rota dinâmica se não existir
            $this->criarRotaDinamica($tema, $pagina);
        } catch (\Exception $e) {
            Log::error('Erro ao garantir rota dinâmica: ' . $e->getMessage());
        }
    }
    
    /**
     * Criar rota dinâmica para uma página
     */
    private function criarRotaDinamica($tema, $pagina)
    {
        try {
            // Gerar nome da rota baseado na página
            $nomeRota = str_replace('_', '-', $pagina);
            $rota = '/' . $nomeRota;
            
            // Verificar se já existe uma rota para esta página
            $rotaExistente = DB::table('rotas_dinamicas')
                ->where('tema', $tema)
                ->where('pagina', $pagina)
                ->first();
            
            if ($rotaExistente) {
                Log::info("Rota dinâmica já existe para {$tema}/{$pagina}");
                return;
            }
            
            // Inserir nova rota dinâmica
            DB::table('rotas_dinamicas')->insert([
                'tema' => $tema,
                'pagina' => $pagina,
                'rota' => $rota,
                'nome_rota' => $nomeRota,
                'controller' => 'TemasController',
                'metodo' => 'renderizarPaginaDinamica',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Log::info("Rota dinâmica criada: {$rota} para {$tema}/{$pagina}");
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar rota dinâmica: ' . $e->getMessage());
        }
    }
    
    /**
     * Obter informação da rota de uma página
     */
    private function obterRotaPagina($tema, $pagina)
    {
        // Mapeamento de rotas principais
        $rotasPrincipais = [
            'home' => 'route(\'home\')',
            'sobre' => 'route(\'sobre\')',
            'contato' => 'route(\'contato\')',
        ];
        
        // Se for uma rota principal, retornar
        if (isset($rotasPrincipais[$pagina])) {
            return [
                'tipo' => 'nomeada',
                'rota' => $rotasPrincipais[$pagina],
                'url' => null,
            ];
        }
        
        // Buscar rota dinâmica no banco
        $rotaDinamica = DB::table('rotas_dinamicas')
            ->where('tema', $tema)
            ->where('pagina', $pagina)
            ->where('ativo', 1)
            ->first();
        
        if ($rotaDinamica) {
            // Verificar se a rota tem nome
            if ($rotaDinamica->nome_rota) {
                $nomeRota = theme_route_name($tema, $rotaDinamica->nome_rota);
                
                // Verificar se a rota existe
                try {
                    if (Route::has($nomeRota)) {
                        return [
                            'tipo' => 'nomeada',
                            'rota' => "route('{$nomeRota}')",
                            'url' => $rotaDinamica->rota,
                        ];
                    }
                } catch (\Exception $e) {
                    // Rota não existe ainda
                }
            }
            
            // Retornar apenas a URL se não houver rota nomeada
            return [
                'tipo' => 'url',
                'rota' => null,
                'url' => $rotaDinamica->rota,
            ];
        }
        
        // Se não encontrou rota, retornar padrão baseado no nome da página
        $rotaPadrao = '/' . str_replace('_', '-', $pagina);
        return [
            'tipo' => 'url',
            'rota' => null,
            'url' => $rotaPadrao,
        ];
    }
    
    /**
     * Criar formulário de conteúdo a partir do HTML da página
     */
    public function createContentForm(Request $request, $pagina)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            return back()->with('error', 'As configurações de páginas são aplicadas apenas aos temas personalizados.');
        }
        
        try {
            // Caminho do arquivo da página
            $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
            $arquivoPagina = $temaViewsPath . '/' . $pagina . '.blade.php';
            
            if (!File::exists($arquivoPagina)) {
                return back()->with('error', 'Arquivo da página não encontrado.');
            }
            
            // Ler o conteúdo do arquivo
            $conteudoHtml = File::get($arquivoPagina);
            
            // Verificar se já existe um formulário
            $formularioExistente = DB::table('content_forms')
                ->where('tema', $temaAtivo)
                ->where('pagina', $pagina)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($formularioExistente) {
                // ATUALIZAR formulário existente - REESCANEAR completamente e recriar
                Log::info("Atualizando formulário existente para {$temaAtivo}/{$pagina} - Reescaneando página completamente");
                
                // IMPORTANTE: Usar o HTML atual do arquivo para garantir que a classe "con" esteja presente
                // O HTML original salvo pode não ter a classe "con" se foi removida em atualizações anteriores
                // Restaurar as tags originais a partir dos helpers Blade, preservando a classe "con"
                $configuracaoExistente = json_decode($formularioExistente->configuracao, true);
                
                // Tentar restaurar o HTML original removendo os helpers Blade
                // Mas garantir que a classe "con" seja preservada nas tags
                $htmlParaProcessar = $conteudoHtml;
                
                // Se o HTML atual tem helpers Blade, tentar reconstruir as tags originais
                // Adicionar a classe "con" nas tags que foram convertidas
                // Padrão: <tag class="...">{!! helper !!}</tag> -> <tag class="... con">{!! helper !!}</tag>
                // Mas isso é complicado, então vamos usar o HTML atual diretamente
                // O usuário deve garantir que a classe "con" esteja presente no arquivo
                
                Log::info("Usando HTML atual do arquivo para reescaneamento (garantir que classe 'con' esteja presente)");
                
                // Processar HTML completamente do zero
                $secoesNovas = $this->processarHtmlParaFormularios($htmlParaProcessar);
                
                if (empty($secoesNovas)) {
                    return back()->with('error', 'Nenhuma seção com classe "sec" encontrada na página. Certifique-se de que as seções possuem a classe "sec".');
                }
                
                Log::info("Reescaneamento completo: " . count($secoesNovas) . " seção(ões) detectada(s)");
                
                // IMPORTANTE: Preservar valores dos campos já salvos
                $secoesAntigas = $this->extrairSecoes($configuracaoExistente);
                
                // Criar mapa de campos antigos por nome para preservar valores
                $camposAntigosPorNome = [];
                foreach ($secoesAntigas as $secaoAntiga) {
                    foreach ($secaoAntiga['campos'] ?? [] as $campoAntigo) {
                        $camposAntigosPorNome[$campoAntigo['nome']] = $campoAntigo;
                    }
                }
                
                // Mesclar seções novas com valores antigos preservados
                $secoes = [];
                foreach ($secoesNovas as $secaoNova) {
                    $camposMesclados = [];
                    foreach ($secaoNova['campos'] ?? [] as $campoNovo) {
                        $nomeCampo = $campoNovo['nome'];
                        
                        // Se o campo já existe, preservar valores salvos
                        if (isset($camposAntigosPorNome[$nomeCampo])) {
                            $campoAntigo = $camposAntigosPorNome[$nomeCampo];
                            
                            // Preservar todos os valores salvos (valor, src, alt, href, texto, ativo, etc.)
                            $campoMesclado = $campoNovo; // Começar com estrutura nova
                            
                            // Preservar valores específicos do tipo
                            if (isset($campoAntigo['valor'])) {
                                $campoMesclado['valor'] = $campoAntigo['valor'];
                            }
                            if (isset($campoAntigo['src'])) {
                                $campoMesclado['src'] = $campoAntigo['src'];
                            }
                            if (isset($campoAntigo['alt'])) {
                                $campoMesclado['alt'] = $campoAntigo['alt'];
                            }
                            if (isset($campoAntigo['href'])) {
                                $campoMesclado['href'] = $campoAntigo['href'];
                            }
                            if (isset($campoAntigo['texto'])) {
                                $campoMesclado['texto'] = $campoAntigo['texto'];
                            }
                            if (isset($campoAntigo['ativo'])) {
                                $campoMesclado['ativo'] = $campoAntigo['ativo'];
                            }
                            if (isset($campoAntigo['url'])) {
                                $campoMesclado['url'] = $campoAntigo['url'];
                            }
                            
                            $camposMesclados[] = $campoMesclado;
                            Log::info("Campo '{$nomeCampo}': valores preservados do banco de dados");
                        } else {
                            // Campo novo, usar valores padrão do HTML
                            $camposMesclados[] = $campoNovo;
                            Log::info("Campo '{$nomeCampo}': novo campo detectado, usando valores padrão");
                        }
                    }
                    
                    $secoes[] = [
                        'secao' => $secaoNova['secao'] ?? 0,
                        'campos' => $camposMesclados
                    ];
                }
                
                // IMPORTANTE: Processar e copiar imagens para o storage antes de tornar dinâmico
                // Isso garante que as imagens não quebrem quando o formulário é atualizado
                $secoes = $this->processarImagensParaStorage($secoes, $temaAtivo, $pagina);
                
                // Atualizar configuração completamente, preservando valores e caminhos de imagens atualizados
                $configuracaoCompleta = [
                    'html_original' => $htmlParaProcessar,
                    'secoes' => $secoes
                ];
                
                // Atualizar formulário no banco de dados
                DB::table('content_forms')
                    ->where('id', $formularioExistente->id)
                    ->update([
                        'configuracao' => json_encode($configuracaoCompleta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'updated_at' => now(),
                    ]);
                
                // Contar total de campos
                $totalCampos = 0;
                foreach ($secoes as $secao) {
                    $totalCampos += count($secao['campos'] ?? []);
                }
                
                // Tornar TODO o conteúdo dinâmico novamente usando o HTML original
                // Isso garante que todos os elementos sejam convertidos corretamente
                $htmlDinamico = $this->tornarConteudoDinamico($htmlParaProcessar, $secoes, $temaAtivo, $pagina);
                
                // Salvar o HTML atualizado no arquivo
                File::put($arquivoPagina, $htmlDinamico);
                
                Log::info("Formulário atualizado completamente para {$temaAtivo}/{$pagina} com " . count($secoes) . " seção(ões) e {$totalCampos} campo(s) - Todo o conteúdo foi reescaneado e convertido");
                
                $mensagem = "Formulário atualizado com sucesso! " . count($secoes) . " seção(ões) e {$totalCampos} campo(s) detectado(s). Todo o conteúdo foi reescaneado e convertido para dinâmico.";
                
                return redirect()->route('dashboard.theme-pages.show', $pagina)
                    ->with('success', $mensagem);
                    
            } else {
                // CRIAR novo formulário
                Log::info("Criando novo formulário para {$temaAtivo}/{$pagina}");
                
                // IMPORTANTE: Processar HTML para identificar seções e conteúdos
                // Esta função já extrai automaticamente os dados originais (valores, src, href, etc.)
                // dos elementos com classe "con" e os salva na configuração
                $secoes = $this->processarHtmlParaFormularios($conteudoHtml);
                
                if (empty($secoes)) {
                    return back()->with('error', 'Nenhuma seção com classe "sec" encontrada na página. Certifique-se de que as seções possuem a classe "sec".');
                }
                
                // Validar que os dados originais foram extraídos corretamente
                $totalCampos = 0;
                $camposComDados = 0;
                foreach ($secoes as $secao) {
                    foreach ($secao['campos'] ?? [] as $campo) {
                        $totalCampos++;
                        // Verificar se o campo tem dados originais preservados
                        $temDados = false;
                        if (isset($campo['valor']) && !empty($campo['valor'])) {
                            $temDados = true;
                        } elseif (isset($campo['src']) && !empty($campo['src'])) {
                            $temDados = true;
                        } elseif (isset($campo['href']) && !empty($campo['href'])) {
                            $temDados = true;
                        } elseif (isset($campo['texto']) && !empty($campo['texto'])) {
                            $temDados = true;
                        }
                        if ($temDados) {
                            $camposComDados++;
                        }
                    }
                }
                
                Log::info("Formulário criado: {$totalCampos} campo(s) total(is), {$camposComDados} campo(s) com dados originais preservados");
                
                // Salvar HTML original antes de tornar dinâmico
                // IMPORTANTE: O HTML original é preservado para garantir que a estrutura básica não seja quebrada
                // Adicionar o HTML original ao início do JSON de configuração
                $configuracaoCompleta = [
                    'html_original' => $conteudoHtml, // HTML original completo preservado
                    'secoes' => $secoes // Seções com dados originais extraídos e preservados
                ];
                
                // Criar formulário no banco de dados
                $formularioId = DB::table('content_forms')->insertGetId([
                    'tema' => $temaAtivo,
                    'pagina' => $pagina,
                    'nome' => 'Formulário - ' . ucfirst($pagina),
                    'configuracao' => json_encode($configuracaoCompleta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // IMPORTANTE: Processar e copiar imagens para o storage antes de tornar dinâmico
                // Isso garante que as imagens não quebrem quando o formulário é criado
                $secoes = $this->processarImagensParaStorage($secoes, $temaAtivo, $pagina);
                
                // Atualizar configuração com caminhos de imagens atualizados
                $configuracaoCompleta = [
                    'html_original' => $conteudoHtml,
                    'secoes' => $secoes
                ];
                
                // Atualizar formulário no banco com caminhos de imagens corretos
                DB::table('content_forms')
                    ->where('id', $formularioId)
                    ->update([
                        'configuracao' => json_encode($configuracaoCompleta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'updated_at' => now(),
                    ]);
                
                // IMPORTANTE: Tornar o conteúdo dinâmico preservando:
                // 1. A classe "con" nas tags (já implementado em tornarConteudoDinamico)
                // 2. Os dados originais através dos helpers Blade que usam os valores do banco
                // 3. A estrutura básica do HTML
                $htmlDinamico = $this->tornarConteudoDinamico($conteudoHtml, $secoes, $temaAtivo, $pagina);
                
                // Validar que o HTML dinâmico mantém a estrutura básica
                // Verificar se ainda há tags com classe "con" (devem ser preservadas)
                $tagsComCon = preg_match_all('/class=["\'][^"\']*\bcon\b[^"\']*["\']/i', $htmlDinamico);
                Log::info("HTML dinâmico gerado: {$tagsComCon} tag(s) com classe 'con' preservada(s)");
                
                // Salvar o HTML atualizado no arquivo
                File::put($arquivoPagina, $htmlDinamico);
                
                Log::info("Formulário de conteúdo criado para {$temaAtivo}/{$pagina} com ID {$formularioId}. Dados originais preservados: {$camposComDados}/{$totalCampos} campos. HTML atualizado mantendo estrutura básica.");
                
                return redirect()->route('dashboard.theme-pages.show', $pagina)
                    ->with('success', 'Formulário de conteúdo criado com sucesso! ' . count($secoes) . ' seção(ões) encontrada(s) com ' . $totalCampos . ' campo(s). Os dados originais foram preservados e o conteúdo foi atualizado para ser dinâmico mantendo a estrutura básica do site.');
            }
                
        } catch (\Exception $e) {
            Log::error('Erro ao criar/atualizar formulário de conteúdo: ' . $e->getMessage());
            return back()->with('error', 'Erro ao criar/atualizar formulário de conteúdo: ' . $e->getMessage());
        }
    }
    
    /**
     * Alocar automaticamente a classe "con" nas tags com conteúdo
     */
    public function allocateClasses(Request $request, $pagina)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            return back()->with('error', 'As configurações de páginas são aplicadas apenas aos temas personalizados.');
        }
        
        try {
            // Caminho do arquivo da página
            $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
            $arquivoPagina = $temaViewsPath . '/' . $pagina . '.blade.php';
            
            if (!File::exists($arquivoPagina)) {
                return back()->with('error', 'Arquivo da página não encontrado.');
            }
            
            // Ler o conteúdo do arquivo
            $conteudoHtml = File::get($arquivoPagina);
            
            $conteudosAdicionados = 0;
            
            // Extrair apenas o conteúdo da seção @section('content')
            preg_match('/@section\([\'"]content[\'"]\)\s*\n(.*?)@endsection/s', $conteudoHtml, $matchesSection);
            $temSection = isset($matchesSection[1]);
            $conteudoSection = $temSection ? $matchesSection[1] : $conteudoHtml;
            
            // Tags que devem receber a classe "con" se tiverem conteúdo
            $elementosCon = ['a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'img', 'div', 'span'];
            
            // Processar cada tipo de tag usando regex (de dentro para fora para evitar conflitos)
            // Processar tags auto-fechadas (img) primeiro
            $patternImg = "/<img([^>]*)>/i";
            $conteudoSection = preg_replace_callback($patternImg, function($matches) use (&$conteudosAdicionados) {
                $atributos = $matches[1] ?? '';
                
                // Verificar se já tem classe con
                if (strpos($atributos, 'class=') !== false && preg_match('/class=["\']([^"\']*)["\']/', $atributos, $classMatch)) {
                    $classes = $classMatch[1];
                    if (strpos($classes, 'con') === false) {
                        // Verificar se tem src ou alt válidos
                        if (preg_match('/src=["\']([^"\']+)["\']/', $atributos, $srcMatch) || 
                            preg_match('/alt=["\']([^"\']+)["\']/', $atributos, $altMatch)) {
                            $src = $srcMatch[1] ?? '';
                            $alt = $altMatch[1] ?? '';
                            if ((!empty($src) && trim($src) !== '#' && trim($src) !== 'javascript:void(0)') || 
                                (!empty($alt) && trim($alt) !== '')) {
                                $classes .= ' con';
                                $atributos = preg_replace('/class=["\']([^"\']*)["\']/', 'class="' . $classes . '"', $atributos);
                                $conteudosAdicionados++;
                                return "<img{$atributos}>";
                            }
                        }
                    }
                } else {
                    // Não tem classe, adicionar
                    if (preg_match('/src=["\']([^"\']+)["\']/', $atributos, $srcMatch) || 
                        preg_match('/alt=["\']([^"\']+)["\']/', $atributos, $altMatch)) {
                        $src = $srcMatch[1] ?? '';
                        $alt = $altMatch[1] ?? '';
                        if ((!empty($src) && trim($src) !== '#' && trim($src) !== 'javascript:void(0)') || 
                            (!empty($alt) && trim($alt) !== '')) {
                            $conteudosAdicionados++;
                            return "<img class=\"con\"{$atributos}>";
                        }
                    }
                }
                return $matches[0];
            }, $conteudoSection);
            
            // Processar outras tags (coletar todas primeiro, depois substituir de trás para frente)
            foreach ($elementosCon as $tag) {
                if ($tag === 'img') {
                    continue; // Já processado
                }
                
                $tagsParaModificar = [];
                
                // Coletar todas as tags com classe que precisam ser modificadas
                $patternComClasse = "/<({$tag})([^>]*class=[\"']([^\"']*)[\"'])([^>]*)>/i";
                preg_match_all($patternComClasse, $conteudoSection, $matchesComClasse, PREG_OFFSET_CAPTURE);
                
                foreach ($matchesComClasse[0] as $index => $match) {
                    $classes = $matchesComClasse[3][$index][0] ?? '';
                    $resto = $matchesComClasse[4][$index][0] ?? '';
                    $posicaoInicio = $match[1];
                    $tagCompleta = $match[0];
                    
                    // Verificar se já tem a classe "con"
                    if (strpos($classes, 'con') === false) {
                        // Encontrar o fechamento correto da tag
                        $conteudoInterno = $this->encontrarConteudoTag($tag, $conteudoSection, $posicaoInicio + strlen($tagCompleta));
                        
                        // Se não encontrou o conteúdo, tentar método alternativo usando regex
                        if ($conteudoInterno === false) {
                            $patternCompleto = '/' . preg_quote($tagCompleta, '/') . '(.*?)<\/' . $tag . '>/is';
                            if (preg_match($patternCompleto, substr($conteudoSection, $posicaoInicio), $matchCompleto)) {
                                $conteudoInterno = $matchCompleto[1] ?? '';
                            }
                        }
                        
                        if ($conteudoInterno !== false && $conteudoInterno !== '') {
                            // Verificar se a tag tem conteúdo usando regex
                            if ($this->tagTemConteudoRegex($tag, $tagCompleta, $conteudoInterno)) {
                                if (!empty($classes)) {
                                    $novaClasse = $classes . ' con';
                                } else {
                                    $novaClasse = 'con';
                                }
                                $tagsParaModificar[] = [
                                    'posicao' => $posicaoInicio,
                                    'tagCompleta' => $tagCompleta,
                                    'novaTag' => "<{$tag} class=\"{$novaClasse}\"{$resto}>",
                                    'tag' => $tag
                                ];
                            }
                        }
                    }
                }
                
                // Coletar todas as tags sem classe que precisam ser modificadas
                $patternSemClasse = "/<({$tag})((?![^>]*class=)[^>]*)>/i";
                preg_match_all($patternSemClasse, $conteudoSection, $matchesSemClasse, PREG_OFFSET_CAPTURE);
                
                foreach ($matchesSemClasse[0] as $index => $match) {
                    $resto = $matchesSemClasse[2][$index][0] ?? '';
                    $posicaoInicio = $match[1];
                    $tagCompleta = $match[0];
                    
                    // Verificar se já foi processado (não deve ter con ainda)
                    if (strpos($resto, 'con') === false) {
                        // Encontrar o fechamento correto da tag
                        $conteudoInterno = $this->encontrarConteudoTag($tag, $conteudoSection, $posicaoInicio + strlen($tagCompleta));
                        
                        // Se não encontrou o conteúdo, tentar método alternativo usando regex
                        if ($conteudoInterno === false) {
                            $patternCompleto = '/' . preg_quote($tagCompleta, '/') . '(.*?)<\/' . $tag . '>/is';
                            if (preg_match($patternCompleto, substr($conteudoSection, $posicaoInicio), $matchCompleto)) {
                                $conteudoInterno = $matchCompleto[1] ?? '';
                            }
                        }
                        
                        if ($conteudoInterno !== false && $conteudoInterno !== '') {
                            // Verificar se a tag tem conteúdo usando regex
                            if ($this->tagTemConteudoRegex($tag, $tagCompleta, $conteudoInterno)) {
                                $tagsParaModificar[] = [
                                    'posicao' => $posicaoInicio,
                                    'tagCompleta' => $tagCompleta,
                                    'novaTag' => "<{$tag} class=\"con\"{$resto}>",
                                    'tag' => $tag
                                ];
                            }
                        }
                    }
                }
                
                // Ordenar por posição (maior primeiro) para substituir de trás para frente
                usort($tagsParaModificar, function($a, $b) {
                    return $b['posicao'] - $a['posicao'];
                });
                
                // Substituir todas as tags de trás para frente
                foreach ($tagsParaModificar as $tagMod) {
                    $conteudoSection = substr_replace($conteudoSection, $tagMod['novaTag'], $tagMod['posicao'], strlen($tagMod['tagCompleta']));
                    $conteudosAdicionados++;
                }
            }
            
            // Restaurar o conteúdo completo com a seção atualizada
            if ($temSection) {
                $conteudoHtml = preg_replace(
                    '/(@section\([\'"]content[\'"]\)\s*\n)(.*?)(@endsection)/s',
                    '$1' . $conteudoSection . '$3',
                    $conteudoHtml
                );
            } else {
                $conteudoHtml = $conteudoSection;
            }
            
            // Salvar o arquivo
            File::put($arquivoPagina, $conteudoHtml);
            
            Log::info("Classe 'con' alocada para {$temaAtivo}/{$pagina}: {$conteudosAdicionados} conteúdo(s) atualizado(s)");
            
            return redirect()->route('dashboard.theme-pages.show', $pagina)
                ->with('success', "Classe 'con' alocada com sucesso! {$conteudosAdicionados} conteúdo(s) atualizado(s).");
                
        } catch (\Exception $e) {
            Log::error('Erro ao alocar classe con: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Erro ao alocar classe: ' . $e->getMessage());
        }
    }
    
    /**
     * Encontrar o conteúdo interno de uma tag, lidando com tags aninhadas
     */
    private function encontrarConteudoTag($tag, $html, $posicaoInicio)
    {
        $htmlRestante = substr($html, $posicaoInicio);
        $tagFechamento = "</{$tag}>";
        $profundidade = 1;
        $posicaoAtual = 0;
        $posicaoFechamento = false;
        $maxTentativas = 10000; // Limite de segurança
        $tentativas = 0;
        
        while ($profundidade > 0 && $posicaoAtual < strlen($htmlRestante) && $tentativas < $maxTentativas) {
            $tentativas++;
            
            // Procurar próxima tag de abertura ou fechamento
            // Usar stripos para case-insensitive
            $proximaAbertura = stripos($htmlRestante, "<{$tag}", $posicaoAtual);
            $proximoFechamento = stripos($htmlRestante, $tagFechamento, $posicaoAtual);
            
            if ($proximoFechamento === false) {
                return false; // Não encontrou fechamento
            }
            
            // Verificar se a tag de abertura encontrada é realmente uma tag de abertura (não fechamento)
            if ($proximaAbertura !== false && $proximaAbertura < $proximoFechamento) {
                // Verificar se não é uma tag de fechamento
                $charDepoisTag = substr($htmlRestante, $proximaAbertura + strlen("<{$tag}"), 1);
                if ($charDepoisTag !== '/' && (ctype_space($charDepoisTag) || $charDepoisTag === '>' || in_array($charDepoisTag, ['"', "'"]))) {
                    // É uma tag de abertura (tag aninhada)
                    $profundidade++;
                    $posicaoAtual = $proximaAbertura + 1;
                } else {
                    // Não é uma tag de abertura válida, procurar próximo fechamento
                    $posicaoAtual = $proximoFechamento + strlen($tagFechamento);
                    $profundidade--;
                    if ($profundidade === 0) {
                        $posicaoFechamento = $proximoFechamento;
                    }
                }
            } else {
                // Encontrou fechamento
                $profundidade--;
                if ($profundidade === 0) {
                    $posicaoFechamento = $proximoFechamento;
                } else {
                    $posicaoAtual = $proximoFechamento + strlen($tagFechamento);
                }
            }
        }
        
        if ($posicaoFechamento === false) {
            return false;
        }
        
        // Extrair conteúdo entre abertura e fechamento
        return substr($htmlRestante, 0, $posicaoFechamento);
    }
    
    /**
     * Verificar se uma tag tem conteúdo usando regex
     * Para a: sempre retorna true
     * Para img: verifica src ou alt (já processado separadamente)
     * Para outras tags: verifica se tem texto direto (não apenas em elementos filhos aninhados)
     */
    private function tagTemConteudoRegex($tag, $tagCompleta, $conteudoInterno)
    {
        // Para tags a, sempre adicionar classe con
        if ($tag === 'a') {
            return true;
        }
        
        // Para img, já foi processado separadamente
        if ($tag === 'img') {
            return false; // Não deve chegar aqui
        }
        
        // Para div e span, ser mais rigoroso - verificar se há tags de bloco aninhadas
        if ($tag === 'div' || $tag === 'span') {
            // Se contém tags de bloco (div, section, article, etc.), não adicionar con
            // a menos que tenha texto direto antes ou depois dessas tags
            $tagsBloco = ['div', 'section', 'article', 'aside', 'header', 'footer', 'main', 'nav', 'form', 'table', 'ul', 'ol', 'dl', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
            
            // Verificar se há tags de bloco no conteúdo
            $temTagBloco = false;
            foreach ($tagsBloco as $tagBloco) {
                if (preg_match('/<' . $tagBloco . '[^>]*>/i', $conteudoInterno)) {
                    $temTagBloco = true;
                    break;
                }
            }
            
            if ($temTagBloco) {
                // Se tem tags de bloco, só adicionar con se houver texto direto antes ou depois
                // Verificar texto antes da primeira tag
                if (preg_match('/^([^<]+)/', $conteudoInterno, $matchInicio)) {
                    $textoInicio = trim($matchInicio[1]);
                    if (!empty($textoInicio) && preg_match('/[^\s\n\r\t<>]/', $textoInicio)) {
                        return true;
                    }
                }
                // Verificar texto após a última tag
                if (preg_match('/([^>]+)$/', $conteudoInterno, $matchFim)) {
                    $textoFim = trim($matchFim[1]);
                    if (!empty($textoFim) && preg_match('/[^\s\n\r\t<>]/', $textoFim)) {
                        return true;
                    }
                }
                // Se tem tags de bloco mas não tem texto direto, não adicionar con
                return false;
            }
            // Se não tem tags de bloco, continuar com a verificação normal abaixo
        }
        
        // Para outras tags (h1-h6, p), verificar se tem texto direto
        // Primeiro, remover todas as tags e verificar se sobra texto
        // Isso funciona mesmo quando não há tags ou só há tags inline como <br>
        $textoSemTags = strip_tags($conteudoInterno);
        $textoSemTags = preg_replace('/\s+/', ' ', $textoSemTags);
        $textoSemTags = trim($textoSemTags);
        
        // Se sobrar texto após remover todas as tags, significa que há texto direto
        if (!empty($textoSemTags) && preg_match('/[^\s\n\r\t]/', $textoSemTags)) {
            return true;
        }
        
        // Verificar se há texto antes da primeira tag
        if (preg_match('/^([^<]+)/', $conteudoInterno, $matchInicio)) {
            $textoInicio = trim($matchInicio[1]);
            // Verificar se não é apenas espaços, quebras de linha ou caracteres especiais
            if (!empty($textoInicio) && preg_match('/[^\s\n\r\t<>]/', $textoInicio)) {
                return true;
            }
        }
        
        // Verificar se há texto após a última tag
        if (preg_match('/([^>]+)$/', $conteudoInterno, $matchFim)) {
            $textoFim = trim($matchFim[1]);
            if (!empty($textoFim) && preg_match('/[^\s\n\r\t<>]/', $textoFim)) {
                return true;
            }
        }
        
        // Verificar se há texto entre tags fechadas e abertas (padrão: </tag>texto<tag)
        if (preg_match('/>([^<>]+)</', $conteudoInterno, $matchEntre)) {
            $textoEntre = trim($matchEntre[1]);
            if (!empty($textoEntre) && preg_match('/[^\s\n\r\t]/', $textoEntre)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Reprocessar elementos com classe "con" que não foram convertidos
     */
    public function reprocessarElementosCon(Request $request, $pagina)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            return back()->with('error', 'As configurações de páginas são aplicadas apenas aos temas personalizados.');
        }
        
        try {
            // Caminho do arquivo da página
            $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
            $arquivoPagina = $temaViewsPath . '/' . $pagina . '.blade.php';
            
            if (!File::exists($arquivoPagina)) {
                return back()->with('error', 'Arquivo da página não encontrado.');
            }
            
            // Ler o conteúdo do arquivo
            $conteudoHtml = File::get($arquivoPagina);
            
            // Encontrar todos os elementos com classe "con" que ainda não foram convertidos
            // Um elemento foi convertido se contém ContentFormHelper::getCampo
            // Usar múltiplos padrões para capturar diferentes formatos
            $padroes = [
                '/<([a-zA-Z]+)([^>]*class="[^"]*\bcon\b[^"]*"[^>]*)>(.*?)<\/\1>/is', // class="... con ..."
                '/<([a-zA-Z]+)([^>]*class=\'[^\']*\bcon\b[^\']*\'[^>]*)>(.*?)<\/\1>/is', // class='... con ...'
                '/<([a-zA-Z]+)([^>]*\bclass\s*=\s*["\'][^"\']*\bcon\b[^"\']*["\'][^>]*)>(.*?)<\/\1>/is', // class com espaços extras
            ];
            
            $matches = [];
            foreach ($padroes as $padrao) {
                preg_match_all($padrao, $conteudoHtml, $matchesTemp, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
                $matches = array_merge($matches, $matchesTemp);
            }
            
            // Remover duplicatas baseado na posição
            $matchesUnicos = [];
            $posicoesProcessadas = [];
            foreach ($matches as $match) {
                $posicao = $match[0][1];
                if (!in_array($posicao, $posicoesProcessadas)) {
                    $matchesUnicos[] = $match;
                    $posicoesProcessadas[] = $posicao;
                }
            }
            
            $elementosNaoConvertidos = [];
            foreach ($matchesUnicos as $match) {
                $tagCompleta = $match[0][0];
                $posicao = $match[0][1];
                $tagName = strtolower($match[1][0]);
                $conteudo = isset($match[3]) ? $match[3][0] : '';
                
                // Verificar se já foi convertido (contém ContentFormHelper)
                if (strpos($tagCompleta, 'ContentFormHelper::getCampo') === false) {
                    $elementosNaoConvertidos[] = [
                        'tag' => $tagCompleta,
                        'posicao' => $posicao,
                        'tagName' => $tagName,
                        'conteudo' => $conteudo
                    ];
                }
            }
            
            if (empty($elementosNaoConvertidos)) {
                return back()->with('info', 'Todos os elementos com classe "con" já foram convertidos.');
            }
            
            // Verificar se já existe um formulário de conteúdo
            $formularioExistente = DB::table('content_forms')
                ->where('tema', $temaAtivo)
                ->where('pagina', $pagina)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$formularioExistente) {
                return back()->with('error', 'Nenhum formulário de conteúdo encontrado. Crie um formulário primeiro.');
            }
            
            // Obter configuração do formulário
            $configuracao = json_decode($formularioExistente->configuracao, true);
            $secoes = $configuracao['secoes'] ?? [];
            
            // Processar elementos não convertidos e adicionar aos campos
            $contadorCampos = 0;
            foreach ($secoes as &$secao) {
                if (!isset($secao['campos'])) {
                    $secao['campos'] = [];
                }
                $contadorCampos += count($secao['campos']);
            }
            
            // Criar uma seção virtual para elementos não convertidos que não estão em seções
            $secaoVirtual = [
                'nome' => 'Elementos não convertidos',
                'campos' => []
            ];
            
            foreach ($elementosNaoConvertidos as $elemento) {
                $campo = $this->extrairCampoDoHtml($elemento['tag'], $elemento['tagName'], $contadorCampos);
                if ($campo) {
                    $secaoVirtual['campos'][] = $campo;
                    $contadorCampos++;
                }
            }
            
            // Adicionar seção virtual se houver campos
            if (!empty($secaoVirtual['campos'])) {
                $secoes[] = $secaoVirtual;
            }
            
            // Atualizar configuração
            $configuracao['secoes'] = $secoes;
            
            // Atualizar formulário no banco
            DB::table('content_forms')
                ->where('id', $formularioExistente->id)
                ->update([
                    'configuracao' => json_encode($configuracao, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'updated_at' => now(),
                ]);
            
            // Tornar o conteúdo dinâmico novamente
            $htmlDinamico = $this->tornarConteudoDinamico($conteudoHtml, $secoes, $temaAtivo, $pagina);
            
            // Salvar o HTML atualizado
            File::put($arquivoPagina, $htmlDinamico);
            
            Log::info("Elementos não convertidos reprocessados para {$temaAtivo}/{$pagina}: " . count($elementosNaoConvertidos) . " elementos");
            
            return redirect()->route('dashboard.theme-pages.show', $pagina)
                ->with('success', count($elementosNaoConvertidos) . ' elemento(s) com classe "con" foram reprocessados e convertidos para dinâmicos.');
                
        } catch (\Exception $e) {
            Log::error('Erro ao reprocessar elementos con: ' . $e->getMessage());
            return back()->with('error', 'Erro ao reprocessar elementos: ' . $e->getMessage());
        }
    }
    
    /**
     * Processar HTML para identificar seções (.sec) e conteúdos (.con)
     */
    private function processarHtmlParaFormularios($html)
    {
        $secoes = [];
        
        Log::info("Iniciando processamento de HTML para formulários. Tamanho do HTML: " . strlen($html) . " caracteres");
        
        // IMPORTANTE: Primeiro, extrair todos os src de imagens do HTML original ANTES de substituir
        // Isso garante que possamos obter o caminho real mesmo quando contém código Blade
        $imagensSrcMap = [];
        preg_match_all('/<img[^>]*>/i', $html, $matchesImg, PREG_OFFSET_CAPTURE);
        foreach ($matchesImg[0] as $idx => $match) {
            $posicao = $match[1];
            $tagCompleta = $match[0];
            
            // Extrair src da tag
            if (preg_match('/src\s*=\s*["\']([^"\']*)["\']/i', $tagCompleta, $srcMatch)) {
                $srcOriginal = $srcMatch[1];
                // Extrair o caminho do código Blade se necessário
                $caminhoReal = $this->extrairCaminhoArquivo($srcOriginal);
                
                // Extrair alt também para melhor identificação
                $alt = '';
                if (preg_match('/alt\s*=\s*["\']([^"\']*)["\']/i', $tagCompleta, $altMatch)) {
                    $alt = $altMatch[1];
                }
                
                // Extrair classes para melhor identificação
                $classes = '';
                if (preg_match('/class\s*=\s*["\']([^"\']*)["\']/i', $tagCompleta, $classMatch)) {
                    $classes = $classMatch[1];
                }
                
                // Mapear pela posição
                $imagensSrcMap[$posicao] = [
                    'src_original' => $srcOriginal,
                    'caminho' => $caminhoReal,
                    'tag_completa' => $tagCompleta,
                    'alt' => $alt,
                    'classes' => $classes
                ];
                Log::info("Imagem mapeada na posição {$posicao}: caminho=" . substr($caminhoReal, 0, 80) . ", alt=" . substr($alt, 0, 50));
            }
        }
        
        Log::info("Total de imagens mapeadas do HTML original: " . count($imagensSrcMap));
        
        // Limpar apenas diretivas Blade que podem interferir no parsing
        // Remover @extends, @section, @endsection, etc., mas manter o conteúdo HTML
        $htmlLimpo = preg_replace('/@extends\([^)]+\)\s*/', '', $html);
        $htmlLimpo = preg_replace('/@section\([^)]+\)\s*/', '', $htmlLimpo);
        $htmlLimpo = preg_replace('/@endsection\s*/', '', $htmlLimpo);
        $htmlLimpo = preg_replace('/@php\s*.*?@endphp/s', '', $htmlLimpo);
        
        // Substituir {{ }} e {!! !!} por placeholders temporários para não quebrar o HTML
        // MAS preservar o mapeamento para poder recuperar depois
        $htmlLimpo = preg_replace('/\{\{([^}]+)\}\}/', 'PLACEHOLDER_BLADE', $htmlLimpo);
        $htmlLimpo = preg_replace('/\{!!\s*([^!]+)!!\}/', 'PLACEHOLDER_BLADE', $htmlLimpo);
        
        // Verificar se há tags com classe "sec" no HTML antes de processar
        $contagemSec = preg_match_all('/class=["\'][^"\']*\bsec\b[^"\']*["\']/i', $htmlLimpo);
        Log::info("Encontradas {$contagemSec} ocorrências de 'class=...sec...' no HTML limpo");
        
        if ($contagemSec === 0) {
            Log::warning("Nenhuma tag com classe 'sec' encontrada no HTML. Verificando HTML original...");
            $contagemSecOriginal = preg_match_all('/class=["\'][^"\']*\bsec\b[^"\']*["\']/i', $html);
            Log::info("Encontradas {$contagemSecOriginal} ocorrências de 'class=...sec...' no HTML original");
        }
        
        // Primeiro tentar usar DOMDocument que é mais robusto
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        
        // Adicionar wrapper HTML se necessário para garantir estrutura válida
        $htmlParaProcessar = $htmlLimpo;
        if (stripos($htmlLimpo, '<html') === false) {
            $htmlParaProcessar = '<!DOCTYPE html><html><body>' . $htmlLimpo . '</body></html>';
        }
        
        // Tentar carregar o HTML
        $sucesso = @$dom->loadHTML(mb_convert_encoding($htmlParaProcessar, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Verificar erros
        $errors = libxml_get_errors();
        $numErrors = count($errors);
        libxml_clear_errors();
        
        Log::info("DOMDocument loadHTML: sucesso=" . ($sucesso ? 'sim' : 'não') . ", erros=" . $numErrors);
        
        // Se houver muitos erros, considerar que falhou
        if ($numErrors > 10) {
            $sucesso = false;
            Log::warning("DOMDocument falhou devido a muitos erros ({$numErrors}). Usando fallback regex.");
        }
        
        if ($sucesso) {
            $xpath = new \DOMXPath($dom);
            
            // Encontrar todas as tags com classe "sec" (pode ter múltiplas classes)
            // Tentar múltiplas queries para garantir que encontre
            $secoesNodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' sec ')]");
            
            Log::info("Query XPath 1 (normalize-space): " . ($secoesNodes ? $secoesNodes->length : 0) . " seções encontradas");
            
            // Se não encontrou, tentar query alternativa
            if (!$secoesNodes || $secoesNodes->length === 0) {
                $secoesNodes = $xpath->query("//*[contains(@class, 'sec')]");
                Log::info("Query XPath 2 (contains): " . ($secoesNodes ? $secoesNodes->length : 0) . " seções encontradas");
            }
            
            // Tentar buscar todos os elementos com @class e filtrar manualmente
            if (!$secoesNodes || $secoesNodes->length === 0) {
                $todosNodes = $xpath->query("//*[@class]");
                if ($todosNodes) {
                    $secoesComSec = [];
                    foreach ($todosNodes as $node) {
                        $classes = $node->getAttribute('class');
                        if (preg_match('/\bsec\b/', $classes)) {
                            $secoesComSec[] = $node;
                        }
                    }
                    if (!empty($secoesComSec)) {
                        Log::info("Query XPath 3 (manual filter): " . count($secoesComSec) . " seções encontradas");
                        // Usar array diretamente em vez de DOMNodeList
                        $secoesNodes = $secoesComSec;
                        $usarArray = true;
                    }
                }
            }
            
            if (isset($usarArray) && $usarArray) {
                // Processar array de nós manualmente
                Log::info("Processando " . count($secoesNodes) . " seções encontradas via filtro manual");
                $secoesComPosicao = [];
                foreach ($secoesNodes as $index => $secaoNode) {
                    // Obter posição do nó no HTML original
                    // Usar a tag de abertura da seção para encontrar sua posição no HTML original
                    $tagAbertura = $dom->saveHTML($secaoNode);
                    // Extrair apenas a tag de abertura (primeira linha)
                    if (preg_match('/^<[^>]+>/', $tagAbertura, $match)) {
                        $tagAberturaSimples = $match[0];
                        // Buscar a posição desta tag no HTML original
                        $posicao = strpos($htmlLimpo, $tagAberturaSimples);
                        if ($posicao === false) {
                            // Se não encontrou, tentar buscar pela classe
                            $classes = $secaoNode->getAttribute('class');
                            if ($classes) {
                                $tagName = strtolower($secaoNode->nodeName);
                                $pattern = '/<' . preg_quote($tagName, '/') . '[^>]*class=["\'][^"\']*' . preg_quote($classes, '/') . '[^"\']*["\'][^>]*>/i';
                                if (preg_match($pattern, $htmlLimpo, $match, PREG_OFFSET_CAPTURE)) {
                                    $posicao = $match[0][1];
                                }
                            }
                        }
                    }
                    
                    // Fallback: usar método antigo se a posição for inválida
                    if ($posicao === false || $posicao < 0) {
                        $posicao = $this->obterPosicaoNo($secaoNode, $dom);
                    }
                    
                    Log::info("Seção " . ($index + 1) . " (DOMDocument - array): posição calculada = {$posicao}");
                    
                    $campos = [];
                    
                    // Encontrar todos os elementos com classe "con" dentro desta seção
                    // IMPORTANTE: Buscar TODOS os elementos, incluindo os aninhados dentro de tags <a>
                    $conNodes = $xpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' con ')]", $secaoNode);
                    
                    Log::info("Seção " . ($index + 1) . " - Query 1 (normalize-space): " . ($conNodes ? $conNodes->length : 0) . " elementos 'con' encontrados");
                    
                    // Se não encontrou, tentar query alternativa
                    if (!$conNodes || $conNodes->length === 0) {
                        $conNodes = $xpath->query(".//*[contains(@class, 'con')]", $secaoNode);
                        Log::info("Seção " . ($index + 1) . " - Query 2 (contains): " . ($conNodes ? $conNodes->length : 0) . " elementos 'con' encontrados");
                    }
                    
                    // Se ainda não encontrou, buscar manualmente TODOS os elementos com @class
                    if (!$conNodes || $conNodes->length === 0) {
                        $todosConNodes = $xpath->query(".//*[@class]", $secaoNode);
                        if ($todosConNodes) {
                            $conNodesComCon = [];
                            foreach ($todosConNodes as $node) {
                                $classes = $node->getAttribute('class');
                                if (preg_match('/\bcon\b/', $classes)) {
                                    $conNodesComCon[] = $node;
                                }
                            }
                            $conNodes = $conNodesComCon;
                            Log::info("Seção " . ($index + 1) . " - Query 3 (manual filter): " . count($conNodesComCon) . " elementos 'con' encontrados");
                        }
                    }
                    
                    // Se ainda não encontrou, pode ser que a seção não tenha elementos 'con' ou há um problema
                    if (!$conNodes || (is_array($conNodes) ? count($conNodes) : $conNodes->length) === 0) {
                        Log::warning("Seção " . ($index + 1) . " - NENHUM elemento 'con' encontrado. Verificando HTML da seção...");
                        $secaoHtml = $dom->saveHTML($secaoNode);
                        $contagemConSecao = preg_match_all('/class=["\'][^"\']*\bcon\b[^"\']*["\']/i', $secaoHtml);
                        Log::info("Seção " . ($index + 1) . " - Regex encontrou {$contagemConSecao} ocorrências de 'class=...con...' no HTML da seção");
                    }
                    
                    // Criar array com campos e suas posições para ordenar
                    $camposComPosicao = [];
                    $elementosProcessados = [];
                    
                    $conNodesArray = is_array($conNodes) ? $conNodes : iterator_to_array($conNodes);
                    foreach ($conNodesArray as $conNode) {
                        $posCampo = $this->obterPosicaoNo($conNode, $dom);
                        $tagNameOriginal = strtolower($conNode->nodeName);
                        
                        $jaProcessado = false;
                        foreach ($elementosProcessados as $processado) {
                            if ($processado['posicao'] == $posCampo) {
                                $jaProcessado = true;
                                break;
                            }
                        }
                        
                        if (!$jaProcessado) {
                            // IMPORTANTE: Usar o mapeamento de imagens para obter o src original
                            $srcOriginalMapeado = null;
                            $tagHtmlOriginal = '';
                            
                            if ($tagNameOriginal === 'img') {
                                // Obter alt e classes do nó para melhor identificação
                                $altNode = $conNode->getAttribute('alt');
                                $classesNode = $conNode->getAttribute('class');
                                
                                // Estratégia 1: Buscar por alt e classes (mais preciso)
                                $encontrado = false;
                                foreach ($imagensSrcMap as $posImg => $dadosImg) {
                                    $matchAlt = !empty($altNode) && !empty($dadosImg['alt']) && $altNode === $dadosImg['alt'];
                                    $matchClasses = !empty($classesNode) && !empty($dadosImg['classes']) && 
                                                   strpos($classesNode, $dadosImg['classes']) !== false || 
                                                   strpos($dadosImg['classes'], $classesNode) !== false;
                                    
                                    if ($matchAlt || ($matchClasses && abs($posImg - $posCampo) < 500)) {
                                        $srcOriginalMapeado = $dadosImg['caminho'];
                                        $tagHtmlOriginal = $dadosImg['tag_completa'];
                                        Log::info("Src encontrado no mapeamento por alt/classes (posição: {$posImg}): {$srcOriginalMapeado}");
                                        $encontrado = true;
                                        break;
                                    }
                                }
                                
                                // Estratégia 2: Buscar pela posição mais próxima
                                if (!$encontrado) {
                                    $posicaoMaisProxima = null;
                                    $distanciaMinima = PHP_INT_MAX;
                                    
                                    foreach ($imagensSrcMap as $posImg => $dadosImg) {
                                        $distancia = abs($posImg - $posCampo);
                                        if ($distancia < $distanciaMinima && $distancia < 500) {
                                            $distanciaMinima = $distancia;
                                            $posicaoMaisProxima = $posImg;
                                            $srcOriginalMapeado = $dadosImg['caminho'];
                                            $tagHtmlOriginal = $dadosImg['tag_completa'];
                                        }
                                    }
                                    
                                    if ($srcOriginalMapeado) {
                                        Log::info("Src encontrado no mapeamento por posição (posição: {$posicaoMaisProxima}, distância: {$distanciaMinima}): {$srcOriginalMapeado}");
                                    }
                                }
                                
                                // Fallback: buscar no HTML limpo
                                if (!$srcOriginalMapeado) {
                                    $htmlAposPosicao = substr($htmlLimpo, max(0, $posCampo - 100), 500);
                                    if (preg_match('/<img[^>]*>/i', $htmlAposPosicao, $imgMatch)) {
                                        $tagHtmlOriginal = $imgMatch[0];
                                        Log::info("Tag img encontrada no HTML limpo (fallback): " . substr($tagHtmlOriginal, 0, 200));
                                    }
                                }
                            }
                            
                            $campo = $this->extrairCampoDoNode($conNode, 0, $tagHtmlOriginal, $srcOriginalMapeado);
                            if ($campo) {
                                $camposComPosicao[] = [
                                    'posicao' => $posCampo,
                                    'tagName' => $tagNameOriginal,
                                    'campo' => $campo
                                ];
                                $elementosProcessados[] = [
                                    'posicao' => $posCampo,
                                    'tagName' => $tagNameOriginal
                                ];
                            }
                        }
                    }
                    
                    // Ordenar campos por posição
                    usort($camposComPosicao, function($a, $b) {
                        return $a['posicao'] <=> $b['posicao'];
                    });
                    
                    // Extrair apenas os campos ordenados
                    $campoIndex = 1;
                    foreach ($camposComPosicao as $item) {
                        $item['campo']['nome'] = $this->gerarNomeCampo($item['tagName'], $campoIndex);
                        $campos[] = $item['campo'];
                        $campoIndex++;
                    }
                    
                    // IMPORTANTE: Adicionar APENAS seções que têm pelo menos um campo 'con'
                    // Seções sem elementos 'con' não devem ser incluídas no formulário
                    if (!empty($campos)) {
                        $secoesComPosicao[] = [
                            'posicao' => $posicao,
                            'secao' => [
                                'secao' => 0,
                                'campos' => $campos
                            ]
                        ];
                        Log::info("Seção " . ($index + 1) . " adicionada com " . count($campos) . " campo(s)");
                    } else {
                        Log::info("Seção " . ($index + 1) . " IGNORADA - não possui elementos 'con' dentro dela");
                    }
                }
                
                // Ordenar seções por posição
                usort($secoesComPosicao, function($a, $b) {
                    return $a['posicao'] <=> $b['posicao'];
                });
                
                // Atualizar índices das seções
                foreach ($secoesComPosicao as $idx => $item) {
                    if (isset($item['secao']) && is_array($item['secao'])) {
                        $item['secao']['secao'] = $idx + 1;
                        $item['secao']['posicao'] = $item['posicao'];
                        $secoes[] = $item['secao'];
                    } else {
                        Log::warning("Item de seção (array) inválido no índice {$idx}: " . json_encode($item));
                    }
                }
                
                Log::info("Retornando " . count($secoes) . " seções processadas via filtro manual");
                return $secoes;
            } else if ($secoesNodes && $secoesNodes->length > 0) {
                Log::info("Encontradas " . $secoesNodes->length . " seções com classe 'sec' via DOMDocument");
                
                // Criar array com seções e suas posições para ordenar
                $secoesComPosicao = [];
                foreach ($secoesNodes as $index => $secaoNode) {
                    // Obter posição do nó no HTML original
                    // Usar a tag de abertura da seção para encontrar sua posição no HTML original
                    $tagAbertura = $dom->saveHTML($secaoNode);
                    // Extrair apenas a tag de abertura (primeira linha)
                    $posicao = false;
                    if (preg_match('/^<[^>]+>/', $tagAbertura, $match)) {
                        $tagAberturaSimples = $match[0];
                        // Buscar a posição desta tag no HTML original
                        $posicao = strpos($htmlLimpo, $tagAberturaSimples);
                        if ($posicao === false) {
                            // Se não encontrou, tentar buscar pela classe
                            $classes = $secaoNode->getAttribute('class');
                            if ($classes) {
                                $tagName = strtolower($secaoNode->nodeName);
                                $pattern = '/<' . preg_quote($tagName, '/') . '[^>]*class=["\'][^"\']*' . preg_quote($classes, '/') . '[^"\']*["\'][^>]*>/i';
                                if (preg_match($pattern, $htmlLimpo, $match, PREG_OFFSET_CAPTURE)) {
                                    $posicao = $match[0][1];
                                }
                            }
                        }
                    }
                    
                    // Fallback: usar método antigo se a posição for inválida
                    if ($posicao === false || $posicao < 0) {
                        $posicao = $this->obterPosicaoNo($secaoNode, $dom);
                    }
                    
                    Log::info("Seção " . ($index + 1) . " (DOMDocument): posição calculada = {$posicao}");
                    
                    $campos = [];
                    
                    // Encontrar todos os elementos com classe "con" dentro desta seção
                    // IMPORTANTE: Buscar TODOS os elementos, incluindo os aninhados dentro de tags <a>
                    // Usar múltiplas estratégias para garantir que todos sejam encontrados
                    
                    $conNodes = null;
                    $conNodesArray = [];
                    
                    // Estratégia 1: Query XPath com normalize-space (mais precisa)
                    $conNodes = $xpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' con ')]", $secaoNode);
                    $count1 = $conNodes ? $conNodes->length : 0;
                    Log::info("Seção " . ($index + 1) . " - Query 1 (normalize-space): {$count1} elementos 'con' encontrados");
                    
                    if ($conNodes && $conNodes->length > 0) {
                        foreach ($conNodes as $node) {
                            $pos = $this->obterPosicaoNo($node, $dom);
                            $tag = strtolower($node->nodeName);
                            $classes = $node->getAttribute('class');
                            $conNodesArray[] = [
                                'node' => $node,
                                'posicao' => $pos,
                                'tag' => $tag,
                                'classes' => $classes
                            ];
                        }
                    }
                    
                    // Estratégia 2: Query XPath alternativa (menos precisa, mas pode pegar mais)
                    $conNodes2 = $xpath->query(".//*[contains(@class, 'con')]", $secaoNode);
                    $count2 = $conNodes2 ? $conNodes2->length : 0;
                    Log::info("Seção " . ($index + 1) . " - Query 2 (contains): {$count2} elementos 'con' encontrados");
                    
                    if ($conNodes2 && $conNodes2->length > 0) {
                        foreach ($conNodes2 as $node) {
                            $pos = $this->obterPosicaoNo($node, $dom);
                            $tag = strtolower($node->nodeName);
                            $classes = $node->getAttribute('class');
                            
                            // Verificar se já foi adicionado (evitar duplicatas)
                            $jaAdicionado = false;
                            foreach ($conNodesArray as $existente) {
                                if ($existente['posicao'] == $pos) {
                                    $jaAdicionado = true;
                                    break;
                                }
                            }
                            
                            if (!$jaAdicionado) {
                                $conNodesArray[] = [
                                    'node' => $node,
                                    'posicao' => $pos,
                                    'tag' => $tag,
                                    'classes' => $classes
                                ];
                            }
                        }
                    }
                    
                    // Estratégia 3: Buscar manualmente todos os elementos com @class
                    $todosConNodes = $xpath->query(".//*[@class]", $secaoNode);
                    if ($todosConNodes) {
                        $conNodesComCon = [];
                        foreach ($todosConNodes as $node) {
                            $classes = $node->getAttribute('class');
                            if (preg_match('/\bcon\b/', $classes)) {
                                $pos = $this->obterPosicaoNo($node, $dom);
                                $tag = strtolower($node->nodeName);
                                
                                // Verificar se já foi adicionado (evitar duplicatas)
                                $jaAdicionado = false;
                                foreach ($conNodesArray as $existente) {
                                    if ($existente['posicao'] == $pos) {
                                        $jaAdicionado = true;
                                        break;
                                    }
                                }
                                
                                if (!$jaAdicionado) {
                                    $conNodesArray[] = [
                                        'node' => $node,
                                        'posicao' => $pos,
                                        'tag' => $tag,
                                        'classes' => $classes
                                    ];
                                }
                            }
                        }
                        Log::info("Seção " . ($index + 1) . " - Query 3 (manual filter): " . count($conNodesComCon) . " elementos 'con' encontrados");
                    }
                    
                    // Ordenar por posição no documento
                    usort($conNodesArray, function($a, $b) {
                        return $a['posicao'] <=> $b['posicao'];
                    });
                    
                    Log::info("Seção " . ($index + 1) . " - Total de elementos 'con' únicos encontrados: " . count($conNodesArray));
                    
                    // Se ainda não encontrou, verificar HTML da seção
                    if (empty($conNodesArray)) {
                        Log::warning("Seção " . ($index + 1) . " - NENHUM elemento 'con' encontrado via XPath. Verificando HTML da seção...");
                        $secaoHtml = $dom->saveHTML($secaoNode);
                        $contagemConSecao = preg_match_all('/class=["\'][^"\']*\bcon\b[^"\']*["\']/i', $secaoHtml);
                        Log::info("Seção " . ($index + 1) . " - Regex encontrou {$contagemConSecao} ocorrências de 'class=...con...' no HTML da seção");
                    }
                    
                    // Criar array com campos e suas posições para ordenar
                    $camposComPosicao = [];
                    $elementosProcessados = []; // Para evitar processar o mesmo elemento duas vezes
                    
                    Log::info("Seção " . ($index + 1) . ": Processando " . count($conNodesArray) . " elementos 'con' encontrados");
                    
                    foreach ($conNodesArray as $idxCon => $itemCon) {
                        $conNode = $itemCon['node'];
                        $posCampo = $this->obterPosicaoNo($conNode, $dom);
                        $tagNameOriginal = strtolower($conNode->nodeName);
                        $classes = $conNode->getAttribute('class');
                        $textoPreview = trim(substr($conNode->textContent, 0, 50));
                        
                        Log::info("Seção " . ($index + 1) . " - Elemento " . ($idxCon + 1) . ": tag={$tagNameOriginal}, posição={$posCampo}, classes={$classes}, texto=" . ($textoPreview ?: '(vazio)'));
                        
                        // IMPORTANTE: Processar TODOS os elementos, mesmo os aninhados
                        // Não verificar se está dentro de outro elemento "con", pois todos devem ser processados
                        // Verificar apenas se já foi processado (mesma posição)
                        $jaProcessado = false;
                        foreach ($elementosProcessados as $processado) {
                            if ($processado['posicao'] == $posCampo) {
                                $jaProcessado = true;
                                Log::info("Seção " . ($index + 1) . " - Elemento " . ($idxCon + 1) . " já foi processado (posição duplicada)");
                                break;
                            }
                        }
                        
                        if (!$jaProcessado) {
                            // IMPORTANTE: Usar o mapeamento de imagens para obter o src original
                            $srcOriginalMapeado = null;
                            $tagHtmlOriginal = '';
                            
                            if ($tagNameOriginal === 'img') {
                                // Obter alt e classes do nó para melhor identificação
                                $altNode = $conNode->getAttribute('alt');
                                $classesNode = $conNode->getAttribute('class');
                                
                                // Estratégia 1: Buscar por alt e classes (mais preciso)
                                $encontrado = false;
                                foreach ($imagensSrcMap as $posImg => $dadosImg) {
                                    $matchAlt = !empty($altNode) && !empty($dadosImg['alt']) && $altNode === $dadosImg['alt'];
                                    $matchClasses = !empty($classesNode) && !empty($dadosImg['classes']) && 
                                                   strpos($classesNode, $dadosImg['classes']) !== false || 
                                                   strpos($dadosImg['classes'], $classesNode) !== false;
                                    
                                    if ($matchAlt || ($matchClasses && abs($posImg - $posCampo) < 500)) {
                                        $srcOriginalMapeado = $dadosImg['caminho'];
                                        $tagHtmlOriginal = $dadosImg['tag_completa'];
                                        Log::info("Src encontrado no mapeamento por alt/classes (posição: {$posImg}): {$srcOriginalMapeado}");
                                        $encontrado = true;
                                        break;
                                    }
                                }
                                
                                // Estratégia 2: Buscar pela posição mais próxima
                                if (!$encontrado) {
                                    $posicaoMaisProxima = null;
                                    $distanciaMinima = PHP_INT_MAX;
                                    
                                    foreach ($imagensSrcMap as $posImg => $dadosImg) {
                                        $distancia = abs($posImg - $posCampo);
                                        if ($distancia < $distanciaMinima && $distancia < 500) {
                                            $distanciaMinima = $distancia;
                                            $posicaoMaisProxima = $posImg;
                                            $srcOriginalMapeado = $dadosImg['caminho'];
                                            $tagHtmlOriginal = $dadosImg['tag_completa'];
                                        }
                                    }
                                    
                                    if ($srcOriginalMapeado) {
                                        Log::info("Src encontrado no mapeamento por posição (posição: {$posicaoMaisProxima}, distância: {$distanciaMinima}): {$srcOriginalMapeado}");
                                    }
                                }
                                
                                // Fallback: buscar no HTML limpo
                                if (!$srcOriginalMapeado) {
                                    $htmlAposPosicao = substr($htmlLimpo, max(0, $posCampo - 100), 500);
                                    if (preg_match('/<img[^>]*>/i', $htmlAposPosicao, $imgMatch)) {
                                        $tagHtmlOriginal = $imgMatch[0];
                                        Log::info("Tag img encontrada no HTML limpo (fallback): " . substr($tagHtmlOriginal, 0, 200));
                                    }
                                }
                            }
                            
                            $campo = $this->extrairCampoDoNode($conNode, 0, $tagHtmlOriginal, $srcOriginalMapeado);
                            if ($campo) {
                                $camposComPosicao[] = [
                                    'posicao' => $posCampo,
                                    'tagName' => $tagNameOriginal,
                                    'campo' => $campo
                                ];
                                $elementosProcessados[] = [
                                    'posicao' => $posCampo,
                                    'tagName' => $tagNameOriginal
                                ];
                                Log::info("Seção " . ($index + 1) . " - Elemento " . ($idxCon + 1) . " processado com sucesso. Campo criado: " . $campo['nome']);
                            } else {
                                // Log para depuração: por que o campo não foi criado?
                                Log::warning("Seção " . ($index + 1) . " - Elemento " . ($idxCon + 1) . ": Campo não criado para elemento '{$tagNameOriginal}' na posição {$posCampo}. Texto: " . $textoPreview);
                            }
                        }
                    }
                    
                    $totalNodes = is_array($conNodes) ? count($conNodes) : ($conNodes ? $conNodes->length : 0);
                    Log::info("Seção " . ($index + 1) . ": Encontrados " . count($camposComPosicao) . " campos únicos com classe 'con' (total de nós encontrados: {$totalNodes})");
                    
                    // Se encontrou nós mas não criou campos, verificar o HTML da seção
                    if ($totalNodes > 0 && count($camposComPosicao) === 0) {
                        Log::warning("Seção " . ($index + 1) . ": Encontrou {$totalNodes} nós 'con' mas não criou campos. Verificando HTML...");
                        $secaoHtml = $dom->saveHTML($secaoNode);
                        $contagemConSecao = preg_match_all('/class=["\'][^"\']*\bcon\b[^"\']*["\']/i', $secaoHtml);
                        Log::info("Seção " . ($index + 1) . ": Regex encontrou {$contagemConSecao} ocorrências de 'class=...con...' no HTML da seção");
                    }
                    
                    // Ordenar campos por posição no documento
                    usort($camposComPosicao, function($a, $b) {
                        return $a['posicao'] <=> $b['posicao'];
                    });
                    
                    // Extrair apenas os campos ordenados
                    $campoIndex = 1;
                    foreach ($camposComPosicao as $item) {
                        $item['campo']['nome'] = $this->gerarNomeCampo($item['tagName'], $campoIndex);
                        $campos[] = $item['campo'];
                        $campoIndex++;
                    }
                    
                    Log::info("Seção " . ($index + 1) . ": Encontrados " . count($campos) . " campos com classe 'con'");
                    
                    // IMPORTANTE: Adicionar APENAS seções que têm pelo menos um campo 'con'
                    // Seções sem elementos 'con' não devem ser incluídas no formulário
                    if (!empty($campos)) {
                        $secoesComPosicao[] = [
                            'posicao' => $posicao,
                            'secao' => [
                                'secao' => 0, // Será atualizado após ordenação
                                'campos' => $campos
                            ]
                        ];
                        Log::info("Seção " . ($index + 1) . " adicionada com " . count($campos) . " campo(s)");
                    } else {
                        Log::info("Seção " . ($index + 1) . " IGNORADA - não possui elementos 'con' dentro dela");
                    }
                }
                
                // Log das posições antes da ordenação
                Log::info("Seções antes da ordenação (DOMDocument):");
                foreach ($secoesComPosicao as $idx => $item) {
                    Log::info("  Seção " . ($idx + 1) . ": posição={$item['posicao']}, campos=" . count($item['secao']['campos']));
                }
                
                // Ordenar seções por posição no documento
                usort($secoesComPosicao, function($a, $b) {
                    return $a['posicao'] <=> $b['posicao'];
                });
                
                // Log das posições após a ordenação
                Log::info("Seções após a ordenação (DOMDocument):");
                foreach ($secoesComPosicao as $idx => $item) {
                    Log::info("  Seção " . ($idx + 1) . ": posição={$item['posicao']}, campos=" . count($item['secao']['campos']));
                }
                
                // Atualizar índices das seções na ordem correta
                foreach ($secoesComPosicao as $idx => $item) {
                    if (isset($item['secao']) && is_array($item['secao'])) {
                        $item['secao']['secao'] = $idx + 1;
                        $item['secao']['posicao'] = $item['posicao']; // Incluir posição na seção
                        $secoes[] = $item['secao'];
                    } else {
                        Log::warning("Item de seção inválido no índice {$idx}: " . json_encode($item));
                    }
                }
                
                return $secoes;
            }
        }
        
        // Se DOMDocument não funcionou, usar regex melhorada
        Log::info("Usando método regex para detectar seções");
        // Buscar tags que começam com abertura e encontram o fechamento correspondente
        // Suportar aspas duplas e simples
        preg_match_all('/<([a-zA-Z]+)[^>]*class=["\'][^"\']*\bsec\b[^"\']*["\'][^>]*>/i', $htmlLimpo, $inicios, PREG_OFFSET_CAPTURE);
        
        Log::info("Regex encontrou " . count($inicios[0] ?? []) . " tags com classe 'sec'");
        
        if (!empty($inicios[0])) {
            // Criar array com seções e posições para ordenar
            $secoesComPosicaoRegex = [];
            
            foreach ($inicios[0] as $idx => $inicio) {
                $tagName = strtolower($inicios[1][$idx][0]);
                $posInicioSecao = $inicio[1]; // Posição da tag <section> no HTML original - NÃO SOBRESCREVER
                $tagCompleta = $inicio[0];
                
                // Encontrar o fechamento correspondente da tag
                $posAtual = $posInicioSecao + strlen($tagCompleta);
                $nivel = 1;
                $posFim = strlen($htmlLimpo);
                
                while ($nivel > 0 && $posAtual < strlen($htmlLimpo)) {
                    // Procurar pela próxima tag de abertura ou fechamento
                    if (preg_match('/<\/?' . preg_quote($tagName, '/') . '[^>]*>/i', $htmlLimpo, $match, PREG_OFFSET_CAPTURE, $posAtual)) {
                        $posMatch = $match[0][1];
                        $tagMatch = $match[0][0];
                        
                        if (strpos($tagMatch, '</') === 0) {
                            // Tag de fechamento
                            $nivel--;
                            if ($nivel === 0) {
                                $posFim = $posMatch;
                                break;
                            }
                        } else {
                            // Tag de abertura
                            $nivel++;
                        }
                        $posAtual = $posMatch + strlen($tagMatch);
                    } else {
                        break;
                    }
                }
                
                if ($nivel === 0) {
                    // Extrair conteúdo da seção
                    $secaoHtml = substr($htmlLimpo, $posInicioSecao, $posFim - $posInicioSecao + strlen('</' . $tagName . '>'));
                    
                    Log::info("Seção regex " . ($idx + 1) . " (posição {$posInicioSecao}): Extraído HTML de " . strlen($secaoHtml) . " caracteres");
                    
                    // Verificar quantos elementos 'con' existem nesta seção
                    $contagemConSecao = preg_match_all('/class=["\'][^"\']*\bcon\b[^"\']*["\']/i', $secaoHtml);
                    Log::info("Seção regex " . ($idx + 1) . ": Encontradas {$contagemConSecao} ocorrências de 'class=...con...' no HTML da seção");
                    
                    // Log detalhado: mostrar todas as tags encontradas
                    if ($contagemConSecao > 0) {
                        preg_match_all('/<([a-zA-Z0-9]+)[^>]*class=["\'][^"\']*\bcon\b[^"\']*["\'][^>]*>/i', $secaoHtml, $matchesDebug, PREG_SET_ORDER);
                        $tagsEncontradas = [];
                        foreach ($matchesDebug as $match) {
                            $tagsEncontradas[] = $match[1][0];
                        }
                        Log::info("Seção regex " . ($idx + 1) . ": Tags encontradas com 'con': " . implode(', ', array_unique($tagsEncontradas)));
                    }
                    
                    $camposComPosicao = [];
                    
                    // Encontrar TODOS os elementos com classe "con" - usar múltiplas estratégias
                    // Estratégia 1: Tags com fechamento normal (div, h1, p, etc.)
                    // IMPORTANTE: Usar uma abordagem que captura elementos aninhados corretamente
                    // Primeiro, encontrar todas as tags de abertura com classe "con"
                    // Usar múltiplos padrões para capturar diferentes formatos
                    $padroesAbertura = [
                        '/<([a-zA-Z0-9]+)([^>]*class="[^"]*\bcon\b[^"]*"[^>]*)>/i', // class="... con ..." (suporta h1, h2, etc.)
                        '/<([a-zA-Z0-9]+)([^>]*class=\'[^\']*\bcon\b[^\']*\'[^>]*)>/i', // class='... con ...' (suporta h1, h2, etc.)
                    ];
                    
                    $aberturas = [];
                    foreach ($padroesAbertura as $padrao) {
                        preg_match_all($padrao, $secaoHtml, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
                        $aberturas = array_merge($aberturas, $matches);
                    }
                    
                    // Remover duplicatas baseado na posição
                    $aberturasUnicas = [];
                    $posicoesProcessadas = [];
                    foreach ($aberturas as $abertura) {
                        $posicao = $abertura[0][1];
                        if (!in_array($posicao, $posicoesProcessadas)) {
                            $aberturasUnicas[] = $abertura;
                            $posicoesProcessadas[] = $posicao;
                        }
                    }
                    
                    Log::info("Estratégia 1 (regex): Encontradas " . count($aberturasUnicas) . " tags de abertura com classe 'con'");
                    
                    foreach ($aberturasUnicas as $abertura) {
                        $tagNameCon = strtolower($abertura[1][0]);
                        $posicaoAbertura = $abertura[0][1];
                        $tagAbertura = $abertura[0][0];
                        
                        Log::info("Seção regex " . ($idx + 1) . " - Processando tag '{$tagNameCon}' na posição {$posicaoAbertura}");
                        
                        // Encontrar o fechamento correspondente, contando tags aninhadas
                        $posicaoFechamento = $this->encontrarFechamentoTag($secaoHtml, $tagNameCon, $posicaoAbertura);
                        
                        if ($posicaoFechamento !== false) {
                            $tagCompletaCon = substr($secaoHtml, $posicaoAbertura, $posicaoFechamento - $posicaoAbertura + strlen("</{$tagNameCon}>"));
                            
                            $campo = $this->extrairCampoDoHtml($tagCompletaCon, $tagNameCon, 0);
                            if ($campo) {
                                $camposComPosicao[] = [
                                    'posicao' => $posicaoAbertura,
                                    'campo' => $campo
                                ];
                                Log::info("Seção regex " . ($idx + 1) . " - Campo criado para tag '{$tagNameCon}': " . $campo['nome']);
                            } else {
                                Log::warning("Seção regex " . ($idx + 1) . " - Campo não criado para tag '{$tagNameCon}' na posição {$posicaoAbertura} (regex fallback)");
                            }
                        } else {
                            Log::warning("Seção regex " . ($idx + 1) . " - Fechamento não encontrado para tag '{$tagNameCon}' na posição {$posicaoAbertura}");
                        }
                    }
                    
                    // Estratégia 2: Tags auto-fechadas como <img>, <input>, <br>, etc.
                    // Melhorar padrão para capturar tags com múltiplas classes e diferentes formatos
                    preg_match_all('/<(img|input|br|hr|meta|link)([^>]*class=["\'][^"\']*\bcon\b[^"\']*["\'][^>]*)\/?>/is', $secaoHtml, $matchesSelfClosing, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
                    
                    foreach ($matchesSelfClosing as $matchSelf) {
                        $tagNameSelf = strtolower($matchSelf[1][0]);
                        $tagCompletaSelf = $matchSelf[0][0];
                        $posicaoSelf = $matchSelf[0][1];
                        
                        $campo = $this->extrairCampoDoHtml($tagCompletaSelf, $tagNameSelf, 0);
                        if ($campo) {
                            $camposComPosicao[] = [
                                'posicao' => $posicaoSelf,
                                'campo' => $campo
                            ];
                        }
                    }
                    
                    // Estratégia 3: Verificar se há elementos que não foram capturados
                    // Usar uma abordagem mais simples: buscar todas as ocorrências de class="...con..."
                    // e verificar se já foram processadas
                    preg_match_all('/<([a-zA-Z]+)[^>]*class="[^"]*\bcon\b[^"]*"[^>]*>/i', $secaoHtml, $matchesAll, PREG_OFFSET_CAPTURE);
                    
                    // Criar array de posições já processadas
                    $posicoesProcessadas = [];
                    foreach ($aberturas as $abertura) {
                        // Com PREG_OFFSET_CAPTURE, $abertura[0] é um array [string, offset]
                        $posicoesProcessadas[] = $abertura[0][1] ?? false;
                    }
                    foreach ($matchesSelfClosing as $matchSelf) {
                        // Com PREG_OFFSET_CAPTURE, $matchSelf[0] é um array [string, offset]
                        $posicoesProcessadas[] = $matchSelf[0][1] ?? false;
                    }
                    
                    // Processar elementos não capturados
                    foreach ($matchesAll[0] as $idx => $matchAll) {
                        $tagNameGen = strtolower($matchesAll[1][$idx][0]);
                        $posInicio = $matchesAll[0][$idx][1];
                        $tagAbertura = $matchesAll[0][$idx][0];
                        
                        // Verificar se já foi processado
                        $jaProcessado = false;
                        foreach ($posicoesProcessadas as $posProcessada) {
                            if ($posProcessada !== false && abs($posProcessada - $posInicio) < 10) {
                                $jaProcessado = true;
                                break;
                            }
                        }
                        
                        if (!$jaProcessado && !in_array($tagNameGen, ['img', 'input', 'br', 'hr', 'meta', 'link'])) {
                            // Tentar encontrar o fechamento desta tag
                            $posFim = strlen($secaoHtml);
                            $tagCompleta = '';
                            
                            // Procurar pelo fechamento
                            if (preg_match('/<\/' . preg_quote($tagNameGen, '/') . '[^>]*>/i', $secaoHtml, $matchFechamento, PREG_OFFSET_CAPTURE, $posInicio)) {
                                $posFim = $matchFechamento[0][1] + strlen($matchFechamento[0][0]);
                                $tagCompleta = substr($secaoHtml, $posInicio, $posFim - $posInicio);
                                
                                $campo = $this->extrairCampoDoHtml($tagCompleta, $tagNameGen, 0);
                                if ($campo) {
                                    $camposComPosicao[] = [
                                        'posicao' => $posInicio,
                                        'campo' => $campo
                                    ];
                                }
                            }
                        }
                    }
                    
                    // Ordenar campos por posição no HTML
                    usort($camposComPosicao, function($a, $b) {
                        return $a['posicao'] <=> $b['posicao'];
                    });
                    
                    // Gerar nomes dos campos na ordem correta
                    $campos = [];
                    $campoIndex = 1;
                    foreach ($camposComPosicao as $item) {
                        $tagNameFinal = '';
                        if ($item['campo']['tipo'] === 'imagem') {
                            $tagNameFinal = 'img';
                        } elseif ($item['campo']['tipo'] === 'link') {
                            $tagNameFinal = 'a';
                        } elseif ($item['campo']['tipo'] === 'titulo') {
                            $tagNameFinal = 'h1';
                        } elseif ($item['campo']['tipo'] === 'paragrafo') {
                            $tagNameFinal = 'p';
                        } else {
                            $tagNameFinal = 'texto';
                        }
                        
                        $item['campo']['nome'] = $this->gerarNomeCampo($tagNameFinal, $campoIndex);
                        $campos[] = $item['campo'];
                        $campoIndex++;
                    }
                    
                    // IMPORTANTE: Adicionar APENAS seções que têm pelo menos um campo 'con'
                    // Seções sem elementos 'con' não devem ser incluídas no formulário
                    if (!empty($campos)) {
                        // IMPORTANTE: Usar $posInicioSecao que é a posição da tag <section> no HTML original
                        // Não usar a posição dos campos dentro da seção
                        $secoesComPosicaoRegex[] = [
                            'posicao' => $posInicioSecao, // Posição da tag <section> no HTML original
                            'secao' => [
                                'secao' => 0, // Será atualizado após ordenação
                                'campos' => $campos
                            ]
                        ];
                        Log::info("Seção regex " . ($idx + 1) . " adicionada com " . count($campos) . " campo(s) na posição {$posInicioSecao} do HTML original");
                    } else {
                        Log::info("Seção regex " . ($idx + 1) . " IGNORADA - não possui elementos 'con' dentro dela");
                    }
                }
            }
            
            // Log das posições antes da ordenação
            Log::info("Seções antes da ordenação (regex):");
            foreach ($secoesComPosicaoRegex as $idx => $item) {
                Log::info("  Seção " . ($idx + 1) . ": posição={$item['posicao']}, campos=" . count($item['secao']['campos']));
            }
            
            // Ordenar seções por posição no HTML
            usort($secoesComPosicaoRegex, function($a, $b) {
                return $a['posicao'] <=> $b['posicao'];
            });
            
            // Log das posições após a ordenação
            Log::info("Seções após a ordenação (regex):");
            foreach ($secoesComPosicaoRegex as $idx => $item) {
                Log::info("  Seção " . ($idx + 1) . ": posição={$item['posicao']}, campos=" . count($item['secao']['campos']));
            }
            
            // Atualizar índices das seções na ordem correta
            foreach ($secoesComPosicaoRegex as $idx => $item) {
                if (isset($item['secao']) && is_array($item['secao'])) {
                    $item['secao']['secao'] = $idx + 1;
                    $item['secao']['posicao'] = $item['posicao']; // Incluir posição na seção
                    $secoes[] = $item['secao'];
                } else {
                    Log::warning("Item de seção regex inválido no índice {$idx}: " . json_encode($item));
                }
            }
        }
        
        // Filtrar valores null do array de seções
        $secoes = array_filter($secoes, function($secao) {
            return $secao !== null && is_array($secao);
        });
        
        // Reindexar array após filtrar
        $secoes = array_values($secoes);
        
        // Log final
        if (empty($secoes)) {
            Log::error("NENHUMA seção foi encontrada após processar HTML. Verifique se há tags com classe 'sec' no arquivo.");
        } else {
            Log::info("Total de " . count($secoes) . " seção(ões) processada(s) com sucesso");
        }
        
        return $secoes;
    }
    
    /**
     * Encontrar o fechamento de uma tag, contando tags aninhadas
     */
    private function encontrarFechamentoTag($html, $tagName, $posicaoInicio)
    {
        $posicao = $posicaoInicio;
        $nivel = 0;
        $tagAbertura = "<{$tagName}";
        $tagFechamento = "</{$tagName}>";
        
        // Encontrar a posição após a tag de abertura
        $posicaoAposAbertura = strpos($html, '>', $posicao);
        if ($posicaoAposAbertura === false) {
            return false;
        }
        $posicao = $posicaoAposAbertura + 1;
        
        // Procurar pelo fechamento, contando tags aninhadas
        while ($posicao < strlen($html)) {
            // Procurar pela próxima tag de abertura ou fechamento
            // Usar regex para encontrar tags de abertura que começam com o nome da tag
            $proximaAbertura = false;
            $proximoFechamento = stripos($html, $tagFechamento, $posicao);
            
            // Procurar por abertura usando regex para garantir que é uma tag completa
            if (preg_match('/<' . preg_quote($tagName, '/') . '[\s>]/i', $html, $matches, PREG_OFFSET_CAPTURE, $posicao)) {
                $proximaAbertura = $matches[0][1];
            }
            
            // Se não há mais fechamentos, retornar false
            if ($proximoFechamento === false) {
                return false;
            }
            
            // Se a próxima tag é um fechamento e não há tags aninhadas, encontramos o fechamento correto
            if ($proximaAbertura === false || $proximoFechamento < $proximaAbertura) {
                if ($nivel === 0) {
                    return $proximoFechamento;
                }
                $nivel--;
                $posicao = $proximoFechamento + strlen($tagFechamento);
            } else {
                // Encontramos uma tag de abertura aninhada
                $nivel++;
                $posicaoAposAbertura = strpos($html, '>', $proximaAbertura);
                if ($posicaoAposAbertura === false) {
                    return false;
                }
                $posicao = $posicaoAposAbertura + 1;
            }
        }
        
        return false;
    }
    
    /**
     * Obter posição de um nó no documento DOM
     */
    private function obterPosicaoNo($node, $dom)
    {
        $xpath = new \DOMXPath($dom);
        // Contar todos os nós anteriores (incluindo ancestrais e irmãos anteriores)
        $query = 'count(preceding::*) + count(ancestor::*) + count(preceding-sibling::*)';
        $posicao = (int)$xpath->evaluate($query, $node);
        
        // Se a posição for muito grande ou inválida, usar uma abordagem alternativa
        // Calcular posição baseada na ordem de travessia do DOM
        if ($posicao < 0 || $posicao > 100000) {
            // Usar uma abordagem mais simples: contar nós anteriores no mesmo nível
            $posicao = 0;
            $atual = $node;
            while ($atual->previousSibling !== null) {
                $posicao++;
                $atual = $atual->previousSibling;
            }
        }
        
        return $posicao;
    }
    
    /**
     * Extrair campo a partir de HTML (usado quando DOMDocument não funciona)
     */
    private function extrairCampoDoHtml($tagHtml, $tagName, $index)
    {
        $campo = [
            'ativo' => '1',
            'nome' => '',
            'tipo' => '',
            'label' => '',
        ];
        
        $campoNome = $this->gerarNomeCampo($tagName, $index);
        $campo['nome'] = $campoNome;
        
        // Extrair atributos - usar regex mais flexível para capturar atributos com código Blade
        // Padrão 1: atributo="valor" (aspas duplas)
        preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $tagHtml, $attrMatches1, PREG_SET_ORDER);
        // Padrão 2: atributo='valor' (aspas simples)
        preg_match_all('/(\w+)\s*=\s*\'([^\']*)\'/', $tagHtml, $attrMatches2, PREG_SET_ORDER);
        
        $atributos = [];
        foreach ($attrMatches1 as $attrMatch) {
            $atributos[strtolower($attrMatch[1])] = $attrMatch[2];
        }
        foreach ($attrMatches2 as $attrMatch) {
            // Não sobrescrever se já existe (priorizar aspas duplas)
            if (!isset($atributos[strtolower($attrMatch[1])])) {
                $atributos[strtolower($attrMatch[1])] = $attrMatch[2];
            }
        }
        
        // Log para debug
        if (stripos($tagHtml, '<img') !== false) {
            Log::info("Atributos extraídos: " . json_encode($atributos, JSON_UNESCAPED_UNICODE));
        }
        
        // Extrair conteúdo interno - melhorar para capturar melhor o texto
        // Primeiro tentar capturar conteúdo entre > e </
        // IMPORTANTE: Usar preg_quote para escapar o nome da tag corretamente (incluindo números como h2)
        $tagNameEscaped = preg_quote($tagName, '/');
        if (preg_match('/>(.*?)<\/' . $tagNameEscaped . '[^>]*>/is', $tagHtml, $contentMatch)) {
            $conteudo = trim($contentMatch[1]);
            
            // Remover tags HTML aninhadas para obter apenas o texto
            $conteudo = preg_replace('/<[^>]+>/', '', $conteudo);
            $conteudo = html_entity_decode($conteudo, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $conteudo = trim($conteudo);
        } else {
            $conteudo = '';
        }
        
        if ($tagName === 'img') {
            $src = $atributos['src'] ?? '';
            $alt = $atributos['alt'] ?? '';
            
            Log::info("=== extrairCampoDoHtml - Imagem ===");
            Log::info("Campo: {$campoNome}");
            Log::info("Src inicial dos atributos: " . ($src ?: 'VAZIO'));
            Log::info("Tag HTML: " . substr($tagHtml, 0, 300));
            
            // IMPORTANTE: Se o src está vazio, tentar extrair diretamente do HTML
            if (empty($src)) {
                // Tentar padrões mais flexíveis para extrair src
                $padroes = [
                    '/src\s*=\s*["\']([^"\']*)["\']/i',
                    '/src\s*=\s*\{\{\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*\}\}/i',
                    '/src\s*=\s*\{!!\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*!!\}/i',
                ];
                
                foreach ($padroes as $padrao) {
                    if (preg_match($padrao, $tagHtml, $match)) {
                        $src = $match[1];
                        Log::info("Src extraído com padrão alternativo (extrairCampoDoHtml): {$src}");
                        break;
                    }
                }
            }
            
            // IMPORTANTE: Se o src contém código Blade, extrair o caminho
            if (!empty($src) && (strpos($src, '{{') !== false || strpos($src, 'asset') !== false)) {
                $src = $this->extrairCaminhoArquivo($src);
                Log::info("Src após extrair caminho do Blade (extrairCampoDoHtml): {$src}");
            }
            
            // Limpar PLACEHOLDER_BLADE se existir
            $src = str_replace('PLACEHOLDER_BLADE', '', $src);
            $alt = str_replace('PLACEHOLDER_BLADE', '', $alt);
            
            $campo['tipo'] = 'imagem';
            $campo['label'] = 'Imagem:';
            $campo['src'] = $src ?: null;
            $campo['alt'] = $alt ?: null;
            
            // Log final
            if (empty($campo['src'])) {
                Log::error("ERRO: Campo de imagem '{$campoNome}' SEM SRC (extrairCampoDoHtml)!");
                Log::error("Tag HTML completa: {$tagHtml}");
            } else {
                Log::info("✓ Src salvo com sucesso (extrairCampoDoHtml): {$campo['src']}");
            }
            
        } elseif ($tagName === 'a') {
            $href = $atributos['href'] ?? '';
            $altLink = $atributos['title'] ?? $atributos['alt'] ?? '';
            
            // Limpar PLACEHOLDER_BLADE se existir
            $href = str_replace('PLACEHOLDER_BLADE', '', $href);
            $altLink = str_replace('PLACEHOLDER_BLADE', '', $altLink);
            $conteudo = str_replace('PLACEHOLDER_BLADE', '', $conteudo);
            
            $campo['tipo'] = 'link';
            $campo['label'] = 'Link: ' . ($conteudo ?: '');
            $campo['href'] = $href ?: null;
            $campo['title'] = $altLink ?: null;
            $campo['texto'] = $conteudo ?: null;
            
        } elseif (in_array($tagName, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
            // Limpar PLACEHOLDER_BLADE se existir
            $conteudo = str_replace('PLACEHOLDER_BLADE', '', $conteudo);
            
            $campo['tipo'] = 'titulo';
            $campo['label'] = 'Título: ' . $conteudo;
            $campo['valor'] = $conteudo;
            
        } elseif ($tagName === 'p') {
            // Limpar PLACEHOLDER_BLADE se existir
            $conteudo = str_replace('PLACEHOLDER_BLADE', '', $conteudo);
            
            $campo['tipo'] = 'paragrafo';
            $campo['label'] = 'Parágrafo: ' . (mb_strlen($conteudo) > 50 ? mb_substr($conteudo, 0, 50) . '...' : $conteudo);
            $campo['valor'] = $conteudo;
            
        } else {
            // Limpar PLACEHOLDER_BLADE se existir
            $conteudo = str_replace('PLACEHOLDER_BLADE', '', $conteudo);
            
            // Para outros elementos (div, span, etc.), sempre criar campo mesmo se vazio
            // O conteúdo pode estar aninhado ou ser capturado incorretamente pela regex
            $campo['tipo'] = 'texto';
            
            if (!empty($conteudo)) {
                $campo['label'] = 'Texto: ' . (mb_strlen($conteudo) > 50 ? mb_substr($conteudo, 0, 50) . '...' : $conteudo);
                $campo['valor'] = $conteudo;
            } else {
                // Mesmo sem conteúdo visível, criar o campo (pode ser um container ou ter conteúdo dinâmico)
                $campo['label'] = 'Texto (' . $tagName . '):';
                $campo['valor'] = '';
            }
        }
        
        return $campo;
    }
    
    /**
     * Extrair informações de um nó com classe .con
     */
    private function extrairCampoDoNode($node, $index, $htmlOriginal = null, $srcMapeado = null)
    {
        $tagName = strtolower($node->nodeName);
        $campo = [
            'ativo' => '1',
            'nome' => '',
            'tipo' => '',
            'label' => '',
        ];
        
        // Gerar nome único para o campo
        $campoNome = $this->gerarNomeCampo($tagName, $index);
        $campo['nome'] = $campoNome;
        
        // Processar de acordo com o tipo de tag
        if ($tagName === 'img') {
            // IMPORTANTE: Priorizar HTML original se fornecido (antes do processamento do DOMDocument)
            $htmlNode = $htmlOriginal;
            
            // Se não temos HTML original, tentar salvar do DOMDocument
            if (empty($htmlNode)) {
                $htmlNode = $node->ownerDocument->saveHTML($node);
            }
            
            // Para imagens, tentar obter src do HTML original primeiro
            $src = '';
            $alt = '';
            
            // PRIORIDADE 1: Usar src mapeado do HTML original (ANTES da substituição por PLACEHOLDER_BLADE)
            if (!empty($srcMapeado)) {
                $src = $srcMapeado;
                Log::info("✓ Src obtido do mapeamento (HTML original): {$src}");
                
                // Extrair alt do HTML original se disponível
                if (!empty($htmlNode) && preg_match('/alt\s*=\s*["\']([^"\']*)["\']/i', $htmlNode, $altMatch)) {
                    $alt = $altMatch[1];
                }
            }
            // PRIORIDADE 2: Extrair do HTML original (pode conter código Blade, mas já foi substituído)
            elseif (!empty($htmlNode)) {
                // Tentar múltiplos padrões para extrair src
                $padroesSrc = [
                    '/src\s*=\s*["\']([^"\']*)["\']/i',
                    '/src\s*=\s*\{\{\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*\}\}/i',
                    '/src\s*=\s*\{!!\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*!!\}/i',
                ];
                
                foreach ($padroesSrc as $padrao) {
                    if (preg_match($padrao, $htmlNode, $srcMatch)) {
                        $src = $srcMatch[1];
                        Log::info("Src extraído do HTML com padrão: {$src}");
                        break;
                    }
                }
                
                // Extrair alt também
                if (preg_match('/alt\s*=\s*["\']([^"\']*)["\']/i', $htmlNode, $altMatch)) {
                    $alt = $altMatch[1];
                }
            }
            
            // PRIORIDADE 3: Se não encontrou, tentar do DOMDocument
            if (empty($src)) {
                $src = $node->getAttribute('src');
                $alt = $node->getAttribute('alt');
                Log::info("Src obtido do DOMDocument: " . ($src ?: 'VAZIO'));
            }
            
            // Limpar PLACEHOLDER_BLADE se existir
            $src = str_replace('PLACEHOLDER_BLADE', '', $src);
            $alt = str_replace('PLACEHOLDER_BLADE', '', $alt);
            
            // IMPORTANTE: Se o src ainda contém código Blade, extrair o caminho
            // Isso garante que mesmo se o mapeamento falhou, ainda limpamos o código Blade
            if (strpos($src, '{{') !== false || strpos($src, 'asset') !== false || strpos($src, '{!!') !== false) {
                $src = $this->extrairCaminhoArquivo($src);
                Log::info("Src após extrair caminho do Blade: {$src}");
            }
            
            // GARANTIR que o src está limpo antes de salvar (sem código Blade)
            // Remover qualquer código Blade que possa ter sobrado
            $src = preg_replace('/\{\{[^}]+\}\}/', '', $src);
            $src = preg_replace('/\{!![^!]+!!\}/', '', $src);
            $src = trim($src);
            
            // Se ainda contém "asset", tentar extrair o caminho novamente
            if (strpos($src, 'asset') !== false) {
                $src = $this->extrairCaminhoArquivo($src);
            }
            
            $campo['tipo'] = 'imagem';
            $campo['label'] = 'Imagem:';
            $campo['src'] = $src ?: null;
            $campo['alt'] = $alt ?: null;
            
            // IMPORTANTE: Log detalhado para debug
            Log::info("=== extrairCampoDoNode - Imagem ===");
            Log::info("Campo: {$campoNome}");
            Log::info("Src após processamento: " . ($src ?: 'VAZIO ou NULL'));
            Log::info("Alt: " . ($alt ?: 'VAZIO ou NULL'));
            if (isset($htmlNode)) {
                Log::info("HTML do nó: " . substr($htmlNode, 0, 300));
            }
            
            // Se o src ainda está vazio, tentar uma última vez extrair do HTML do nó
            if (empty($src) && isset($htmlNode)) {
                // Tentar padrões mais flexíveis
                $padroes = [
                    '/src\s*=\s*["\']([^"\']*)["\']/i',
                    '/src\s*=\s*\{\{\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*\}\}/i',
                    '/src\s*=\s*\{!!\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*!!\}/i',
                ];
                
                foreach ($padroes as $padrao) {
                    if (preg_match($padrao, $htmlNode, $match)) {
                        $src = $match[1];
                        Log::info("Src extraído com padrão alternativo: {$src}");
                        
                        // Se contém código Blade, extrair caminho
                        if (strpos($src, '{{') !== false || strpos($src, 'asset') !== false) {
                            $src = $this->extrairCaminhoArquivo($src);
                            Log::info("Src após extrair caminho: {$src}");
                        }
                        
                        $campo['src'] = $src ?: null;
                        break;
                    }
                }
            }
            
            // Log final
            if (empty($campo['src'])) {
                Log::error("ERRO: Campo de imagem '{$campoNome}' SEM SRC após todas as tentativas!");
                Log::error("HTML completo do nó: " . ($htmlNode ?? 'não disponível'));
            } else {
                Log::info("✓ Src salvo com sucesso: {$campo['src']}");
            }
            
        } elseif ($tagName === 'a') {
            // Para links, salvar href, title (ou alt) e texto
            $href = $node->getAttribute('href');
            $altLink = $node->getAttribute('title') ?: $node->getAttribute('alt');
            
            // IMPORTANTE: Preservar HTML interno completo se houver tags aninhadas
            $temTagsAninhadas = false;
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $temTagsAninhadas = true;
                    break;
                }
            }
            
            if ($temTagsAninhadas) {
                // Preservar HTML interno completo
                $htmlInterno = '';
                foreach ($node->childNodes as $child) {
                    $htmlInterno .= $node->ownerDocument->saveHTML($child);
                }
                $texto = trim($htmlInterno);
            } else {
                // Apenas texto simples
                $texto = trim($node->textContent);
            }
            
            // Limpar PLACEHOLDER_BLADE se existir
            $href = str_replace('PLACEHOLDER_BLADE', '', $href);
            $altLink = str_replace('PLACEHOLDER_BLADE', '', $altLink);
            $texto = str_replace('PLACEHOLDER_BLADE', '', $texto);
            
            $campo['tipo'] = 'link';
            $campo['label'] = 'Link: ' . (strip_tags($texto) ?: '');
            $campo['href'] = $href ?: null;
            $campo['title'] = $altLink ?: null;
            $campo['texto'] = $texto ?: null;
            
        } elseif (in_array($tagName, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
            // Para títulos, verificar se há tags aninhadas
            $temTagsAninhadas = false;
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $temTagsAninhadas = true;
                    break;
                }
            }
            
            if ($temTagsAninhadas) {
                // Preservar HTML interno completo
                $htmlInterno = '';
                foreach ($node->childNodes as $child) {
                    $htmlInterno .= $node->ownerDocument->saveHTML($child);
                }
                $texto = trim($htmlInterno);
            } else {
                // Apenas texto simples
                $texto = trim($node->textContent);
            }
            
            // Limpar PLACEHOLDER_BLADE se existir
            $texto = str_replace('PLACEHOLDER_BLADE', '', $texto);
            
            $campo['tipo'] = 'titulo';
            $campo['label'] = 'Título: ' . strip_tags($texto);
            $campo['valor'] = $texto;
            
        } elseif ($tagName === 'p') {
            // Para parágrafos, verificar se há tags aninhadas
            $temTagsAninhadas = false;
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $temTagsAninhadas = true;
                    break;
                }
            }
            
            if ($temTagsAninhadas) {
                // Preservar HTML interno completo
                $htmlInterno = '';
                foreach ($node->childNodes as $child) {
                    $htmlInterno .= $node->ownerDocument->saveHTML($child);
                }
                $texto = trim($htmlInterno);
            } else {
                // Apenas texto simples
                $texto = trim($node->textContent);
            }
            
            // Limpar PLACEHOLDER_BLADE se existir
            $texto = str_replace('PLACEHOLDER_BLADE', '', $texto);
            
            $campo['tipo'] = 'paragrafo';
            $campo['label'] = 'Parágrafo: ' . (mb_strlen(strip_tags($texto)) > 50 ? mb_substr(strip_tags($texto), 0, 50) . '...' : strip_tags($texto));
            $campo['valor'] = $texto;
            
        } else {
            // Para outros elementos (div, span, etc.), verificar se há tags aninhadas
            $temTagsAninhadas = false;
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $temTagsAninhadas = true;
                    break;
                }
            }
            
            if ($temTagsAninhadas) {
                // IMPORTANTE: Preservar HTML interno completo quando há tags aninhadas
                // Exemplo: <div class="con"><strong>Saiba Mais</strong></div> -> salvar <strong>Saiba Mais</strong>
                $htmlInterno = '';
                foreach ($node->childNodes as $child) {
                    $htmlInterno .= $node->ownerDocument->saveHTML($child);
                }
                $texto = trim($htmlInterno);
            } else {
                // Apenas texto simples
                $texto = trim($node->textContent);
            }
            
            // Limpar PLACEHOLDER_BLADE se existir
            $texto = str_replace('PLACEHOLDER_BLADE', '', $texto);
            
            $campo['tipo'] = 'texto';
            
            if (!empty($texto)) {
                $campo['label'] = 'Texto: ' . (mb_strlen(strip_tags($texto)) > 50 ? mb_substr(strip_tags($texto), 0, 50) . '...' : strip_tags($texto));
                $campo['valor'] = $texto; // Salvar HTML completo quando há tags aninhadas
            } else {
                // Mesmo sem conteúdo visível, criar o campo (pode ser um container ou ter conteúdo dinâmico)
                $campo['label'] = 'Texto (' . $tagName . '):';
                $campo['valor'] = '';
            }
        }
        
        return $campo;
    }
    
    /**
     * Processar e copiar imagens para o storage
     * Garante que as imagens sejam salvas no storage e os caminhos atualizados no banco
     */
    private function processarImagensParaStorage($secoes, $tema, $pagina)
    {
        $diretorio = 'temas/' . $tema . '/content-images';
        
        // IMPORTANTE: Garantir que o diretório existe no storage
        if (!Storage::disk('public')->exists($diretorio)) {
            Storage::disk('public')->makeDirectory($diretorio, 0755, true);
        }
        
        // Verificar se o diretório foi criado
        $caminhoFisicoDiretorio = storage_path('app/public/' . $diretorio);
        if (!is_dir($caminhoFisicoDiretorio)) {
            Log::error("Não foi possível criar o diretório: {$caminhoFisicoDiretorio}");
            // Tentar criar manualmente
            if (!mkdir($caminhoFisicoDiretorio, 0755, true)) {
                Log::error("Falha ao criar diretório manualmente: {$caminhoFisicoDiretorio}");
            }
        }
        
        // Verificar se o link simbólico do storage existe
        $linkSimbolico = public_path('storage');
        if (!is_link($linkSimbolico) && !is_dir($linkSimbolico)) {
            Log::warning("Link simbólico do storage não existe: {$linkSimbolico}");
            Log::warning("Execute: php artisan storage:link para criar o link simbólico");
        }
        
        $imagensProcessadas = 0;
        
        Log::info("=== INICIANDO processarImagensParaStorage ===");
        Log::info("Tema: {$tema}, Página: {$pagina}");
        Log::info("Total de seções: " . count($secoes));
        
        foreach ($secoes as &$secao) {
            $totalCamposSecao = count($secao['campos'] ?? []);
            Log::info("Processando seção com {$totalCamposSecao} campos");
            
            foreach ($secao['campos'] ?? [] as &$campo) {
                if ($campo['tipo'] === 'imagem') {
                    $srcOriginal = $campo['src'] ?? null;
                    $campoNome = $campo['nome'] ?? 'sem_nome';
                    
                    Log::info("Campo de imagem encontrado: {$campoNome}");
                    Log::info("  src original: " . ($srcOriginal ?? 'NULL ou VAZIO'));
                    Log::info("  tipo: " . ($campo['tipo'] ?? 'não definido'));
                    Log::info("  ativo: " . ($campo['ativo'] ?? 'não definido'));
                    
                    // IMPORTANTE: Se o src está vazio, tentar não processar mas logar
                    if (empty($srcOriginal)) {
                        Log::warning("Campo de imagem '{$campoNome}' sem src! Não será processado.");
                        Log::warning("  Campo completo: " . json_encode($campo, JSON_UNESCAPED_UNICODE));
                        continue; // Pular este campo
                    }
                    
                    $srcOriginal = $campo['src'];
                    
                    // Verificar se já está no storage (caminho já processado)
                    // Verificar diferentes formatos de caminho já processado
                    $jaProcessado = false;
                    if (strpos($srcOriginal, 'storage/app/public/temas/') !== false) {
                        $jaProcessado = true;
                    } elseif (strpos($srcOriginal, 'temas/' . $tema . '/content-images/') !== false) {
                        // Se está no formato temas/{tema}/content-images/, normalizar
                        $campo['src'] = 'storage/app/public/' . $srcOriginal;
                        $jaProcessado = true;
                    } elseif (preg_match('/storage\/app\/public\/temas\/' . preg_quote($tema, '/') . '\/content-images\//', $srcOriginal)) {
                        $jaProcessado = true;
                    }
                    
                    if ($jaProcessado) {
                        // Já está no storage, apenas normalizar o caminho
                        $campo['src'] = $this->normalizarCaminhoImagem($srcOriginal);
                        
                        // Verificar se o arquivo realmente existe no storage
                        $caminhoRelativo = str_replace('storage/app/public/', '', $campo['src']);
                        $caminhoFisico = storage_path('app/public/' . $caminhoRelativo);
                        if (file_exists($caminhoFisico)) {
                            Log::info("Imagem já processada e existe: {$campo['src']}");
                        } else {
                            Log::warning("Imagem marcada como processada mas arquivo não existe: {$campo['src']} (físico: {$caminhoFisico})");
                            // Continuar para reprocessar
                            $jaProcessado = false;
                        }
                    }
                    
                    if ($jaProcessado) {
                        continue;
                    }
                    
                    // Verificar se é uma URL externa (http:// ou https://)
                    if (preg_match('/^https?:\/\//i', $srcOriginal)) {
                        // URL externa, manter como está
                        continue;
                    }
                    
                    // Extrair caminho do arquivo
                    // Pode estar em formatos como:
                    // - {{ asset('temas/Ampiezza/assets/images/image.jpg') }}
                    // - temas/Ampiezza/assets/images/image.jpg
                    // - /temas/Ampiezza/assets/images/image.jpg
                    // - public/temas/Ampiezza/assets/images/image.jpg
                    
                    $caminhoArquivo = $this->extrairCaminhoArquivo($srcOriginal);
                    
                    if (!$caminhoArquivo) {
                        Log::warning("Não foi possível extrair caminho do arquivo da imagem: {$srcOriginal}");
                        continue;
                    }
                    
                    // Verificar se o arquivo existe
                    $caminhoCompleto = null;
                    
                    // Tentar diferentes localizações possíveis
                    // As imagens podem estar em:
                    // - public/temas/... (mais comum)
                    // - storage/app/public/temas/... (se já foram processadas)
                    // - base_path/temas/... (menos comum)
                    $possiveisCaminhos = [
                        public_path($caminhoArquivo), // public/temas/...
                        public_path(str_replace('public/', '', $caminhoArquivo)), // se já tem public/ no caminho
                        base_path('public/' . $caminhoArquivo), // base_path/public/temas/...
                        base_path($caminhoArquivo), // base_path/temas/...
                        storage_path('app/public/' . $caminhoArquivo), // storage/app/public/temas/...
                    ];
                    
                    // Se o caminho começa com "temas/", também tentar sem o prefixo
                    if (strpos($caminhoArquivo, 'temas/') === 0) {
                        $caminhoSemTemas = substr($caminhoArquivo, 6); // Remover "temas/"
                        $possiveisCaminhos[] = public_path('temas/' . $caminhoSemTemas);
                        $possiveisCaminhos[] = base_path('public/temas/' . $caminhoSemTemas);
                    }
                    
                    foreach ($possiveisCaminhos as $caminho) {
                        if (file_exists($caminho) && is_file($caminho)) {
                            $caminhoCompleto = $caminho;
                            break;
                        }
                    }
                    
                    if (!$caminhoCompleto || !file_exists($caminhoCompleto)) {
                        Log::warning("Arquivo de imagem não encontrado: {$srcOriginal} (tentou: " . implode(', ', $possiveisCaminhos) . ")");
                        continue;
                    }
                    
                    // Gerar nome único para a imagem
                    $nomeArquivo = basename($caminhoCompleto);
                    $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
                    $nomeBase = pathinfo($nomeArquivo, PATHINFO_FILENAME);
                    $nomeImagem = time() . '_' . Str::slug($tema . '_' . $pagina . '_' . $nomeBase) . '.' . $extensao;
                    
                    // IMPORTANTE: Primeiro copiar a imagem para o storage
                    // Depois salvar o caminho correto no banco
                    try {
                        // Ler o conteúdo do arquivo original
                        $conteudoArquivo = file_get_contents($caminhoCompleto);
                        if ($conteudoArquivo === false) {
                            throw new \Exception("Não foi possível ler o arquivo: {$caminhoCompleto}");
                        }
                        
                        // Verificar tamanho do arquivo
                        $tamanhoArquivo = filesize($caminhoCompleto);
                        if ($tamanhoArquivo === false || $tamanhoArquivo === 0) {
                            throw new \Exception("Arquivo está vazio ou não pode ser lido: {$caminhoCompleto}");
                        }
                        
                        Log::info("Lendo arquivo original: {$caminhoCompleto} (tamanho: {$tamanhoArquivo} bytes)");
                        
                        // Definir caminho no storage (sem storage/app/public, apenas o caminho relativo)
                        $caminhoStorage = $diretorio . '/' . $nomeImagem;
                        
                        // Copiar arquivo para o storage usando Storage::disk('public')
                        // Isso salva em: storage/app/public/temas/{tema}/content-images/{nomeImagem}
                        $salvo = Storage::disk('public')->put($caminhoStorage, $conteudoArquivo);
                        
                        if (!$salvo) {
                            throw new \Exception("Storage::put() retornou false para: {$caminhoStorage}");
                        }
                        
                        // Verificar se o arquivo foi realmente salvo
                        $caminhoFisicoStorage = storage_path('app/public/' . $caminhoStorage);
                        if (!file_exists($caminhoFisicoStorage)) {
                            throw new \Exception("Arquivo não existe após salvar: {$caminhoFisicoStorage}");
                        }
                        
                        // Verificar tamanho do arquivo salvo
                        $tamanhoSalvo = filesize($caminhoFisicoStorage);
                        if ($tamanhoSalvo === false || $tamanhoSalvo === 0) {
                            throw new \Exception("Arquivo salvo está vazio: {$caminhoFisicoStorage}");
                        }
                        
                        if ($tamanhoSalvo !== $tamanhoArquivo) {
                            Log::warning("Tamanho do arquivo original ({$tamanhoArquivo}) diferente do salvo ({$tamanhoSalvo})");
                        }
                        
                        // IMPORTANTE: Salvar o caminho no formato que uploaded_assets() entende
                        // Formato: storage/app/public/temas/{tema}/content-images/{nomeImagem}
                        // O helper uploaded_assets() vai converter isso para URL pública
                        $campo['src'] = 'storage/app/public/' . $caminhoStorage;
                        
                        $imagensProcessadas++;
                        Log::info("✓ Imagem copiada com sucesso!");
                        Log::info("  Original: {$srcOriginal}");
                        Log::info("  Arquivo físico: {$caminhoCompleto} ({$tamanhoArquivo} bytes)");
                        Log::info("  Storage: {$caminhoFisicoStorage} ({$tamanhoSalvo} bytes)");
                        Log::info("  Caminho salvo no banco: {$campo['src']}");
                        
                    } catch (\Exception $e) {
                        Log::error("✗ Erro ao copiar imagem para storage:");
                        Log::error("  Arquivo original: {$srcOriginal}");
                        Log::error("  Caminho completo: " . ($caminhoCompleto ?? 'não encontrado'));
                        Log::error("  Erro: " . $e->getMessage());
                        Log::error("  Stack trace: " . $e->getTraceAsString());
                        // Manter o caminho original se falhar (para não quebrar o site)
                        // Mas logar o erro para correção manual
                    }
                }
            }
        }
        
        Log::info("Total de imagens processadas e copiadas para storage: {$imagensProcessadas}");
        
        return $secoes;
    }
    
    /**
     * Extrair caminho do arquivo de uma string que pode conter Blade helpers
     */
    private function extrairCaminhoArquivo($src)
    {
        // Remover helpers Blade como {{ asset('...') }}
        $src = preg_replace('/\{\{\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*\}\}/i', '$1', $src);
        $src = preg_replace('/\{!!\s*asset\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*!!\}/i', '$1', $src);
        
        // Remover espaços e quebras de linha
        $src = trim($src);
        
        // Remover barra inicial se existir
        $src = ltrim($src, '/');
        
        // Se começar com "public/", remover
        if (strpos($src, 'public/') === 0) {
            $src = substr($src, 7);
        }
        
        return $src;
    }
    
    /**
     * Normalizar caminho de imagem para formato padrão
     * Formato esperado: storage/app/public/temas/{tema}/content-images/{nomeImagem}
     */
    private function normalizarCaminhoImagem($src)
    {
        // Remover espaços e normalizar barras
        $src = trim($src);
        $src = preg_replace('#/+#', '/', $src);
        
        // Se já está no formato storage/app/public/, manter (mas normalizar)
        if (strpos($src, 'storage/app/public/') !== false) {
            // Garantir que não tem barras duplicadas
            $src = preg_replace('#storage/app/public/+#', 'storage/app/public/', $src);
            return $src;
        }
        
        // Se está no formato storage/... (sem app/public), adicionar app/public
        if (preg_match('/^storage\/(?!app\/public)/', $src)) {
            return 'storage/app/public/' . substr($src, 8);
        }
        
        // Se está no formato temas/... (sem storage), adicionar storage/app/public
        if (strpos($src, 'temas/') === 0) {
            return 'storage/app/public/' . $src;
        }
        
        // Se contém /storage/ mas não tem app/public, tentar adicionar
        if (strpos($src, '/storage/') !== false && strpos($src, 'storage/app/public/') === false) {
            $src = str_replace('/storage/', '/storage/app/public/', $src);
            $src = str_replace('storage/', 'storage/app/public/', $src);
        }
        
        return $src;
    }
    
    /**
     * Gerar nome único para o campo
     */
    private function gerarNomeCampo($tipo, $index)
    {
        $tipos = [
            'img' => 'imagem',
            'a' => 'link',
            'h1' => 'titulo',
            'h2' => 'titulo',
            'h3' => 'titulo',
            'h4' => 'titulo',
            'h5' => 'titulo',
            'h6' => 'titulo',
            'p' => 'paragrafo',
            'strong' => 'texto', // Tags strong são tratadas como texto
        ];
        
        $prefixo = $tipos[$tipo] ?? 'texto';
        return $prefixo . '_' . $index;
    }
    
    /**
     * Mostrar formulário de edição de conteúdo
     */
    public function editContentForm($pagina, $formularioId)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        $formulario = DB::table('content_forms')
            ->where('id', $formularioId)
            ->where('tema', $temaAtivo)
            ->where('pagina', $pagina)
            ->first();
        
        if (!$formulario) {
            return redirect()->route('dashboard.theme-pages.show', $pagina)
                ->with('error', 'Formulário não encontrado.');
        }
        
        $secoes = $this->extrairSecoes($formulario->configuracao);
        
        return view('dashboard.theme-pages.edit-content-form', compact('pagina', 'formulario', 'secoes', 'temaAtivo'));
    }
    
    /**
     * Atualizar conteúdo do formulário
     */
    public function updateContentForm(Request $request, $pagina, $formularioId)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        $formulario = DB::table('content_forms')
            ->where('id', $formularioId)
            ->where('tema', $temaAtivo)
            ->where('pagina', $pagina)
            ->first();
        
        if (!$formulario) {
            return back()->with('error', 'Formulário não encontrado.');
        }
        
        try {
            $configuracao = json_decode($formulario->configuracao, true);
            $secoes = $this->extrairSecoes($configuracao);
            $dados = $request->all();
            
            // Preparar regras de validação para todos os uploads de imagem
            $regrasValidacao = [];
            $mensagensValidacao = [];
            foreach ($secoes as $secao) {
                foreach ($secao['campos'] as $campo) {
                    if ($campo['tipo'] === 'imagem' && $request->hasFile($campo['nome'] . '_upload')) {
                        // Para SVG, não usar validação 'image' pois o Laravel pode não reconhecer como imagem
                        $regrasValidacao[$campo['nome'] . '_upload'] = 'mimes:jpeg,png,jpg,gif,webp,svg|max:5120';
                        $mensagensValidacao[$campo['nome'] . '_upload.mimes'] = 'A imagem deve ser do tipo: jpeg, png, jpg, gif, webp ou svg.';
                        $mensagensValidacao[$campo['nome'] . '_upload.max'] = 'A imagem não pode ter mais de 5MB.';
                    }
                }
            }
            
            // Validar todos os uploads de uma vez
            if (!empty($regrasValidacao)) {
                $request->validate($regrasValidacao, $mensagensValidacao);
            }
            
            // Atualizar valores dos campos
            foreach ($secoes as &$secao) {
                foreach ($secao['campos'] as &$campo) {
                    $campoNome = $campo['nome'];
                    
                    // Atualizar de acordo com o tipo
                    if ($campo['tipo'] === 'imagem') {
                        // Processar upload de imagem se fornecido
                        if ($request->hasFile($campoNome . '_upload')) {
                            $imagem = $request->file($campoNome . '_upload');
                            
                            // Gerar nome único para a imagem
                            $nomeImagem = time() . '_' . Str::slug($temaAtivo . '_' . $pagina . '_' . $campoNome) . '.' . $imagem->getClientOriginalExtension();
                            
                            // Criar diretório se não existir
                            $diretorio = 'temas/' . $temaAtivo . '/content-images';
                            
                            // Garantir que o diretório existe usando Storage
                            if (!Storage::disk('public')->exists($diretorio)) {
                                Storage::disk('public')->makeDirectory($diretorio, 0755, true);
                            }
                            
                            // Verificar se o diretório foi criado
                            if (!Storage::disk('public')->exists($diretorio)) {
                                Log::error("Não foi possível criar o diretório: {$diretorio}");
                                return back()->with('error', 'Erro ao criar diretório para a imagem. Verifique as permissões.');
                            }
                            
                            // Salvar imagem no disco público usando storeAs
                            try {
                                $caminhoImagem = $imagem->storeAs($diretorio, $nomeImagem, 'public');
                                
                                if (!$caminhoImagem) {
                                    throw new \Exception("storeAs retornou null");
                                }
                                
                                // Verificar se o arquivo foi realmente salvo
                                if (!Storage::disk('public')->exists($caminhoImagem)) {
                                    throw new \Exception("Arquivo não existe após storeAs: {$caminhoImagem}");
                                }
                                
                                // Verificar tamanho do arquivo salvo
                                $tamanhoArquivo = Storage::disk('public')->size($caminhoImagem);
                                if ($tamanhoArquivo === 0) {
                                    throw new \Exception("Arquivo salvo está vazio: {$caminhoImagem}");
                                }
                                
                                Log::info("Imagem salva com sucesso: {$caminhoImagem} (tamanho: {$tamanhoArquivo} bytes)");
                                
                            } catch (\Exception $e) {
                                Log::error("Erro ao salvar imagem: " . $e->getMessage());
                                Log::error("Diretório: {$diretorio}, Nome: {$nomeImagem}");
                                return back()->with('error', 'Erro ao salvar a imagem: ' . $e->getMessage());
                            }
                            
                            // Salvar o caminho físico completo no banco (storage/app/public/temas/...)
                            // A função uploaded_assets() vai converter para URL pública
                            $caminhoCompleto = 'storage/app/public/' . $caminhoImagem;
                            
                            // Atualizar src com o caminho físico completo (salvar no banco)
                            $campo['src'] = $caminhoCompleto;
                            
                            Log::info("Caminho físico salvo no banco: {$caminhoCompleto}");
                            Log::info("Caminho relativo: {$caminhoImagem}");
                            
                            // Se havia uma imagem anterior do upload, deletar (antes de atualizar o campo)
                            $srcAntigo = $campo['src'] ?? '';
                            if (!empty($srcAntigo) && $srcAntigo !== $caminhoCompleto) {
                                // Normalizar caminho antigo (pode estar com /storage/ ou storage/)
                                $caminhoAntigo = $srcAntigo;
                                
                                // Remover URLs completas (http://...)
                                if (strpos($caminhoAntigo, 'http') === 0) {
                                    $caminhoAntigo = parse_url($caminhoAntigo, PHP_URL_PATH);
                                }
                                
                                // Remover /storage/ ou storage/ do início
                                $caminhoAntigo = preg_replace('#^/?storage/#', '', $caminhoAntigo);
                                
                                // Tentar deletar o arquivo
                                if (Storage::disk('public')->exists($caminhoAntigo)) {
                                    Storage::disk('public')->delete($caminhoAntigo);
                                }
                            }
                        } else {
                            // Se não houve upload e não há src existente, manter o src atual do campo
                            // (não fazer nada, manter o valor atual do banco)
                        }
                        
                        if (isset($dados[$campoNome . '_alt'])) {
                            $campo['alt'] = $dados[$campoNome . '_alt'];
                        }
                    } elseif ($campo['tipo'] === 'link') {
                        if (isset($dados[$campoNome . '_href'])) {
                            $campo['href'] = $dados[$campoNome . '_href'];
                        }
                        if (isset($dados[$campoNome . '_texto'])) {
                            $campo['texto'] = $dados[$campoNome . '_texto'];
                        }
                    } else {
                        // Para texto, título, parágrafo
                        if (isset($dados[$campoNome])) {
                            $campo['valor'] = $dados[$campoNome];
                        }
                    }
                    
                    // Atualizar status ativo
                    if (isset($dados[$campoNome . '_ativo'])) {
                        $campo['ativo'] = $dados[$campoNome . '_ativo'];
                    } else {
                        $campo['ativo'] = '0';
                    }
                }
            }
            
            // Preservar HTML original se existir, caso contrário usar formato antigo
            $htmlOriginal = isset($configuracao['html_original']) ? $configuracao['html_original'] : null;
            
            if ($htmlOriginal) {
                // Novo formato: preservar HTML original
                $configuracaoAtualizada = [
                    'html_original' => $htmlOriginal,
                    'secoes' => $secoes
                ];
            } else {
                // Formato antigo: apenas seções
                $configuracaoAtualizada = $secoes;
            }
            
            // Salvar no banco
            DB::table('content_forms')
                ->where('id', $formularioId)
                ->update([
                    'configuracao' => json_encode($configuracaoAtualizada, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'updated_at' => now(),
                ]);
            
            return redirect()->route('dashboard.theme-pages.show', $pagina)
                ->with('success', 'Conteúdo atualizado com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar formulário de conteúdo: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar conteúdo: ' . $e->getMessage());
        }
    }
    
    /**
     * Tornar o conteúdo HTML dinâmico substituindo elementos .con pelos helpers
     */
    private function tornarConteudoDinamico($html, $secoes, $tema, $pagina)
    {
        try {
            $htmlResultado = $html;
            
            // Limitar o número máximo de substituições para evitar loops infinitos
            $maxSubstituicoes = 1000;
            $contadorSubstituicoes = 0;
            
            // Mapear todos os campos para suas posições no HTML
            $substituicoes = [];
            
            // Primeiro, coletar todos os campos e seus tipos
            $camposParaProcessar = [];
            foreach ($secoes as $secaoIndex => $secao) {
                if (!isset($secao['campos'])) {
                    continue;
                }
                
                foreach ($secao['campos'] as $campo) {
                    if (!isset($campo['nome']) || !isset($campo['tipo'])) {
                        continue;
                    }
                    
                    $camposParaProcessar[] = [
                        'nome' => $campo['nome'],
                        'tipo' => $campo['tipo'],
                        'secao' => $secaoIndex
                    ];
                }
            }
            
            // Processar cada campo, buscando a tag correspondente no HTML
            // IMPORTANTE: Processar links primeiro para evitar processar tags internas
            // Ordenar campos: links primeiro, depois os outros
            usort($camposParaProcessar, function($a, $b) {
                if ($a['tipo'] === 'link' && $b['tipo'] !== 'link') {
                    return -1; // link vem antes
                }
                if ($a['tipo'] !== 'link' && $b['tipo'] === 'link') {
                    return 1; // outros vêm depois
                }
                return 0; // manter ordem original
            });
            
            // IMPORTANTE: Não usar offset por tipo, pois isso pode pular tags
            // Em vez disso, buscar todas as tags do tipo e processar em ordem
            // Armazenar todas as tags encontradas por tipo para processar em ordem
            $tagsPorTipo = [];
            foreach ($camposParaProcessar as $campo) {
                $tipo = $campo['tipo'];
                if (!isset($tagsPorTipo[$tipo])) {
                    $tagsPorTipo[$tipo] = [];
                }
                $tagsPorTipo[$tipo][] = $campo;
            }
            
            // Armazenar posições de tags <a> já processadas para verificar sobreposição
            $tagsAProcessadas = [];
            
            Log::info("Total de campos para processar: " . count($camposParaProcessar) . " em tornarConteudoDinamico para {$tema}/{$pagina}");
            
            // Processar por tipo, mas buscar todas as tags de uma vez e processar em ordem
            foreach ($tagsPorTipo as $tipo => $camposDoTipo) {
                Log::info("Processando tipo '{$tipo}': " . count($camposDoTipo) . " campos");
                
                // Buscar TODAS as tags deste tipo no HTML
                $todasTags = $this->encontrarTodasTagsNoHtml($htmlResultado, $tipo);
                Log::info("Encontradas " . count($todasTags) . " tags do tipo '{$tipo}' no HTML");
                
                // IMPORTANTE: Processar tags em ordem, uma por campo
                // Se houver mais campos do que tags, alguns campos não serão processados
                // Se houver mais tags do que campos, algumas tags não serão processadas
                $idxTag = 0;
                foreach ($camposDoTipo as $idxCampo => $campo) {
                    // Proteção contra loops infinitos
                    if ($contadorSubstituicoes >= $maxSubstituicoes) {
                        Log::warning("Limite de substituições atingido em tornarConteudoDinamico para {$tema}/{$pagina}");
                        break 2;
                    }
                    
                    $campoNome = $campo['nome'];
                    
                    Log::info("Processando campo {$idxCampo}/" . count($camposDoTipo) . ": nome={$campoNome}, tipo={$tipo}, idxTag={$idxTag}");
                    
                    // Encontrar a próxima tag não processada deste tipo
                    $tagEncontrada = null;
                    
                    // Começar a partir do índice da última tag processada
                    for ($i = $idxTag; $i < count($todasTags); $i++) {
                        $tag = $todasTags[$i];
                        
                        // Verificar se esta tag já foi processada
                        $jaProcessada = false;
                        foreach ($substituicoes as $subExistente) {
                            if ($subExistente['inicio'] == $tag['inicio'] && 
                                $subExistente['fim'] == $tag['fim']) {
                                $jaProcessada = true;
                                break;
                            }
                        }
                        
                        if (!$jaProcessada) {
                            $tagEncontrada = $tag;
                            $idxTag = $i + 1; // Próxima tag para o próximo campo
                            break;
                        }
                    }
                
                if ($tagEncontrada && isset($tagEncontrada['inicio']) && isset($tagEncontrada['fim'])) {
                    // Verificar se a posição é válida
                    if ($tagEncontrada['fim'] <= strlen($htmlResultado) && $tagEncontrada['inicio'] < $tagEncontrada['fim']) {
                        // Verificar se esta tag já foi processada (evitar duplicatas)
                        $jaProcessada = false;
                        foreach ($substituicoes as $subExistente) {
                            if ($subExistente['inicio'] == $tagEncontrada['inicio'] && 
                                $subExistente['fim'] == $tagEncontrada['fim']) {
                                $jaProcessada = true;
                                Log::info("Tag já processada: campo={$campoNome}, inicio={$tagEncontrada['inicio']}, fim={$tagEncontrada['fim']}");
                                break;
                            }
                        }
                        
                        if (!$jaProcessada) {
                            Log::info("Tag encontrada para campo {$campoNome}: tipo={$tipo}, inicio={$tagEncontrada['inicio']}, fim={$tagEncontrada['fim']}");
                            // Gerar o helper Blade correspondente
                            $helperBlade = $this->gerarHelperBlade(
                                $tema, 
                                $pagina, 
                                $campoNome, 
                                $tipo, 
                                $tagEncontrada['classes'] ?? '', 
                                $tagEncontrada['tagHtml'] ?? '',
                                $tagEncontrada['atributos'] ?? [],
                                $tagEncontrada['conteudo'] ?? null
                            );
                            
                            // Adicionar à lista de substituições
                            $substituicoes[] = [
                                'inicio' => $tagEncontrada['inicio'],
                                'fim' => $tagEncontrada['fim'],
                                'helper' => $helperBlade,
                                'tipo' => $tipo,
                                'campoNome' => $campoNome
                            ];
                            
                            // Se for uma tag <a>, adicionar à lista de tags <a> processadas
                            if ($tipo === 'link') {
                                $tagsAProcessadas[] = [
                                    'inicio' => $tagEncontrada['inicio'],
                                    'fim' => $tagEncontrada['fim']
                                ];
                            }
                            
                            $contadorSubstituicoes++;
                            Log::info("Substituição adicionada: campo={$campoNome}, tipo={$tipo}");
                        }
                    } else {
                        Log::warning("Posições inválidas para campo {$campoNome}: inicio={$tagEncontrada['inicio']}, fim={$tagEncontrada['fim']}, html_length=" . strlen($htmlResultado));
                    }
                } else {
                    Log::warning("Tag não encontrada para campo {$campoNome} (tipo={$tipo}) - todas as tags deste tipo já foram processadas ou não existem");
                }
            }
            }
            
            Log::info("Total de substituições coletadas: " . count($substituicoes) . " em tornarConteudoDinamico para {$tema}/{$pagina}");
            
            // Aplicar substituições na ordem reversa (do final para o início) para não quebrar offsets
            // IMPORTANTE: Processar tags internas primeiro, depois a tag <a> que as contém
            if (!empty($substituicoes)) {
                // Separar substituições de links e outras tags
                $substituicoesLinks = [];
                $substituicoesOutras = [];
                
                foreach ($substituicoes as $sub) {
                    if ($sub['tipo'] === 'link') {
                        $substituicoesLinks[] = $sub;
                    } else {
                        $substituicoesOutras[] = $sub;
                    }
                }
                
                // Primeiro, aplicar substituições de tags internas (não-links)
                usort($substituicoesOutras, function($a, $b) {
                    return $b['inicio'] <=> $a['inicio'];
                });
                
                foreach ($substituicoesOutras as $sub) {
                    if ($sub['inicio'] < strlen($htmlResultado) && $sub['fim'] <= strlen($htmlResultado)) {
                        $antes = substr($htmlResultado, 0, $sub['inicio']);
                        $depois = substr($htmlResultado, $sub['fim']);
                        
                        if (empty($sub['helper'])) {
                            Log::warning("Helper vazio para substituição: tipo={$sub['tipo']}, campo={$sub['campoNome']}");
                            continue;
                        }
                        
                        $htmlResultado = $antes . $sub['helper'] . $depois;
                        
                        // Recalcular posições das tags <a> após cada substituição
                        $offsetAjuste = strlen($sub['helper']) - ($sub['fim'] - $sub['inicio']);
                        
                        foreach ($substituicoesLinks as &$subLink) {
                            // Verificar se a tag substituída está dentro da tag <a>
                            $estaDentro = ($sub['inicio'] >= $subLink['inicio'] && $sub['fim'] <= $subLink['fim']);
                            
                            if ($estaDentro) {
                                // Se está dentro, ajustar apenas o fim da tag <a>
                                $fimAntigo = $subLink['fim'];
                                $subLink['fim'] += $offsetAjuste;
                                Log::info("Tag {$sub['tipo']} dentro de <a>: ajustando fim de {$fimAntigo} para {$subLink['fim']}");
                            } elseif ($sub['fim'] <= $subLink['inicio']) {
                                // Se está completamente antes, ajustar ambas as posições
                                $subLink['inicio'] += $offsetAjuste;
                                $subLink['fim'] += $offsetAjuste;
                                Log::info("Tag {$sub['tipo']} antes de <a>: ajustando inicio e fim");
                            }
                        }
                        
                        Log::info("Substituição de {$sub['tipo']}: offset={$offsetAjuste}, inicio={$sub['inicio']}, fim={$sub['fim']}, helper_length=" . strlen($sub['helper']));
                    }
                }
                
                // Depois, aplicar substituições de tags <a>
                // Para cada tag <a>, pegar o conteúdo atual do HTML (já com tags internas processadas)
                usort($substituicoesLinks, function($a, $b) {
                    return $b['inicio'] <=> $a['inicio'];
                });
                
                foreach ($substituicoesLinks as $sub) {
                    // Validar posições antes de processar
                    if ($sub['inicio'] >= strlen($htmlResultado) || $sub['fim'] > strlen($htmlResultado) || $sub['inicio'] >= $sub['fim']) {
                        Log::warning("Posições inválidas para tag <a>: inicio={$sub['inicio']}, fim={$sub['fim']}, html_length=" . strlen($htmlResultado));
                        continue;
                    }
                    
                    $antes = substr($htmlResultado, 0, $sub['inicio']);
                    $depois = substr($htmlResultado, $sub['fim']);
                    
                    if (empty($sub['helper'])) {
                        Log::warning("Helper vazio para substituição: tipo={$sub['tipo']}, campo={$sub['campoNome']}");
                        continue;
                    }
                    
                    // Pegar o conteúdo atual do HTML (já com tags internas processadas)
                    $conteudoAtualHtml = substr($htmlResultado, $sub['inicio'], $sub['fim'] - $sub['inicio']);
                    
                    // Validar que o conteúdo contém uma tag <a> completa
                    if (!preg_match('/^<a[^>]*>.*?<\/a>$/is', trim($conteudoAtualHtml))) {
                        Log::warning("Conteúdo da tag <a> inválido. Inicio: {$sub['inicio']}, Fim: {$sub['fim']}, Conteúdo: " . substr($conteudoAtualHtml, 0, 200));
                        // Tentar encontrar a tag <a> no conteúdo
                        if (preg_match('/<a[^>]*>(.*?)<\/a>/is', $conteudoAtualHtml, $matches)) {
                            $conteudoInternoProcessado = $matches[1];
                            
                            // Reconstruir o helper com o conteúdo interno processado
                            if (preg_match('/<a([^>]*)>/i', $sub['helper'], $attrMatches)) {
                                $atributosTagA = $attrMatches[1];
                                $sub['helper'] = '<a' . $atributosTagA . '>' . $conteudoInternoProcessado . '</a>';
                            }
                        } else {
                            // Se não conseguir extrair, usar o helper original
                            Log::warning("Não foi possível extrair conteúdo interno da tag <a>. Usando helper original.");
                        }
                    } else {
                        // Extrair apenas o conteúdo interno (sem a tag <a>)
                        if (preg_match('/<a[^>]*>(.*?)<\/a>/is', $conteudoAtualHtml, $matches)) {
                            $conteudoInternoProcessado = $matches[1];
                            
                            // Reconstruir o helper com o conteúdo interno processado
                            if (preg_match('/<a([^>]*)>/i', $sub['helper'], $attrMatches)) {
                                $atributosTagA = $attrMatches[1];
                                $sub['helper'] = '<a' . $atributosTagA . '>' . $conteudoInternoProcessado . '</a>';
                                
                                Log::info("Tag <a> atualizada com conteúdo interno processado. Tamanho: " . strlen($conteudoInternoProcessado));
                            }
                        }
                    }
                    
                    $htmlResultado = $antes . $sub['helper'] . $depois;
                }
            }
            
            return $htmlResultado;
        } catch (\Exception $e) {
            Log::error('Erro ao tornar conteúdo dinâmico: ' . $e->getMessage());
            // Em caso de erro, retornar o HTML original
            return $html;
        }
    }
    
    /**
     * Encontrar TODAS as tags com classe .con no HTML de um tipo específico
     */
    private function encontrarTodasTagsNoHtml($html, $tipo)
    {
        $tags = [];
        $offset = 0;
        
        // Buscar todas as tags deste tipo no HTML
        while (true) {
            $tagEncontrada = $this->encontrarTagNoHtml($html, $tipo, $offset, $html);
            
            if (!$tagEncontrada) {
                break;
            }
            
            $tags[] = $tagEncontrada;
            $offset = $tagEncontrada['fim'];
            
            // Proteção contra loops infinitos
            if (count($tags) > 1000) {
                Log::warning("Limite de tags atingido ao buscar tags do tipo '{$tipo}'");
                break;
            }
        }
        
        // Ordenar por posição
        usort($tags, function($a, $b) {
            return $a['inicio'] <=> $b['inicio'];
        });
        
        return $tags;
    }
    
    /**
     * Encontrar a próxima tag com classe .con no HTML a partir de uma posição
     */
    private function encontrarTagNoHtml($html, $tipo, $offsetInicial = 0, $htmlCompleto = null)
    {
        // Se htmlCompleto não foi fornecido, usar o próprio html
        if ($htmlCompleto === null) {
            $htmlCompleto = $html;
        }
        
        // Verificar se o offset inicial é válido
        if ($offsetInicial >= strlen($htmlCompleto)) {
            return null;
        }
        
        $html = substr($htmlCompleto, $offsetInicial);
        
        // Verificar se há conteúdo para processar
        if (empty($html)) {
            return null;
        }
        
        // IMPORTANTE: NÃO ignorar elementos dentro de tags <a> com classe "con"
        // Todos os elementos com classe "con" devem ser processados, mesmo quando aninhados
        // A lógica anterior estava impedindo o processamento de elementos aninhados
        
        if ($tipo === 'imagem') {
            // Buscar tag <img> com classe .con - múltiplos padrões para maior flexibilidade
            $patterns = [
                // Padrão padrão: class="... con ..."
                '/<img([^>]*class="[^"]*\bcon\b[^"]*"[^>]*)\/?>/i',
                // Padrão alternativo: class='... con ...'
                '/<img([^>]*class=\'[^\']*\bcon\b[^\']*\'[^>]*)\/?>/i',
                // Padrão para tags sem aspas ou com espaços extras
                '/<img([^>]*\bclass\s*=\s*["\'][^"\']*\bcon\b[^"\']*["\'][^>]*)\/?>/i',
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $html, $matches, PREG_OFFSET_CAPTURE)) {
                    $inicio = $offsetInicial + $matches[0][1];
                    $fim = $inicio + strlen($matches[0][0]);
                    $tagHtml = $matches[0][0];
                    $atributos = $matches[1][0];
                    
                    // Extrair classes e outros atributos
                    $classes = '';
                    if (preg_match('/class=["\']([^"\']*)["\']/', $atributos, $classMatch)) {
                        $classes = $classMatch[1];
                    }
                    
                    // Extrair todos os atributos exceto src e alt (que serão dinâmicos)
                    $atributosPreservar = [];
                    // Regex melhorado para capturar atributos com hífens, dois pontos, etc.
                    preg_match_all('/([a-zA-Z0-9_-]+(?:::[a-zA-Z0-9_-]+)*)=["\']([^"\']*)["\']/', $atributos, $attrMatches, PREG_SET_ORDER);
                    foreach ($attrMatches as $attrMatch) {
                        $attrName = $attrMatch[1]; // Manter o nome original
                        $attrNameLower = strtolower($attrName);
                        // Preservar todos os atributos exceto src e alt (que serão dinâmicos)
                        if ($attrNameLower !== 'src' && $attrNameLower !== 'alt') {
                            $atributosPreservar[$attrName] = $attrMatch[2];
                        }
                    }
                    
                    Log::info("Tag img encontrada: offset={$offsetInicial}, inicio={$inicio}, fim={$fim}, classes={$classes}");
                    
                    return [
                        'inicio' => $inicio,
                        'fim' => $fim,
                        'classes' => $classes,
                        'tagHtml' => $tagHtml,
                        'atributos' => $atributosPreservar
                    ];
                }
            }
            
            Log::warning("Tag img com classe 'con' não encontrada a partir do offset {$offsetInicial}");
        } elseif ($tipo === 'link') {
            // Buscar tag <a> com classe .con - múltiplos padrões para maior flexibilidade
            $patterns = [
                // Padrão padrão: class="... con ..."
                '/<a([^>]*class="[^"]*\bcon\b[^"]*"[^>]*)>(.*?)<\/a>/is',
                // Padrão alternativo: class='... con ...'
                '/<a([^>]*class=\'[^\']*\bcon\b[^\']*\'[^>]*)>(.*?)<\/a>/is',
                // Padrão para tags sem aspas ou com espaços extras
                '/<a([^>]*\bclass\s*=\s*["\'][^"\']*\bcon\b[^"\']*["\'][^>]*)>(.*?)<\/a>/is',
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $html, $matches, PREG_OFFSET_CAPTURE)) {
                    $inicio = $offsetInicial + $matches[0][1];
                    $fim = $inicio + strlen($matches[0][0]);
                    $tagHtml = $matches[0][0];
                    $atributos = $matches[1][0];
                    $conteudo = isset($matches[2]) ? $matches[2][0] : '';
                    
                    // Extrair classes
                    $classes = '';
                    if (preg_match('/class=["\']([^"\']*)["\']/', $atributos, $classMatch)) {
                        $classes = $classMatch[1];
                    }
                    
                    // Extrair todos os atributos exceto href e title/alt (que serão dinâmicos)
                    $atributosPreservar = [];
                    // Regex melhorado para capturar atributos com hífens, dois pontos, etc. (data-w-id, data-wf--primary-button--variant)
                    preg_match_all('/([a-zA-Z0-9_-]+(?:::[a-zA-Z0-9_-]+)*)=["\']([^"\']*)["\']/', $atributos, $attrMatches, PREG_SET_ORDER);
                    foreach ($attrMatches as $attrMatch) {
                        $attrName = $attrMatch[1]; // Manter o nome original (não converter para lowercase para preservar data-w-id, etc.)
                        $attrNameLower = strtolower($attrName);
                        // Preservar todos os atributos exceto href, alt_link e alt (que serão dinâmicos)
                        if ($attrNameLower !== 'href' && $attrNameLower !== 'alt_link' && $attrNameLower !== 'alt') {
                            $atributosPreservar[$attrName] = $attrMatch[2];
                        }
                    }
                    
                    Log::info("Tag a encontrada: offset={$offsetInicial}, inicio={$inicio}, fim={$fim}, classes={$classes}");
                    
                    return [
                        'inicio' => $inicio,
                        'fim' => $fim,
                        'classes' => $classes,
                        'tagHtml' => $tagHtml,
                        'atributos' => $atributosPreservar,
                        'conteudo' => $conteudo,
                        'atributosOriginais' => $atributos
                    ];
                }
            }
            
            Log::warning("Tag a com classe 'con' não encontrada a partir do offset {$offsetInicial}");
        } else {
            // Para texto, título, parágrafo - buscar tags com classe .con
            // Usar múltiplos padrões para capturar diferentes formatos
            $patterns = [
                'titulo' => [
                    '/<(h[1-6])([^>]*class="[^"]*\bcon\b[^"]*"[^>]*)>(.*?)<\/\1>/is',
                    '/<(h[1-6])([^>]*class=\'[^\']*\bcon\b[^\']*\'[^>]*)>(.*?)<\/\1>/is',
                ],
                'paragrafo' => [
                    '/<(p)([^>]*class="[^"]*\bcon\b[^"]*"[^>]*)>(.*?)<\/\1>/is',
                    '/<(p)([^>]*class=\'[^\']*\bcon\b[^\']*\'[^>]*)>(.*?)<\/\1>/is',
                ],
                'texto' => [
                    // Priorizar tags strong primeiro (para capturar mesmo quando aninhadas)
                    '/<(strong)([^>]*class="[^"]*\bcon\b[^"]*"[^>]*)>(.*?)<\/\1>/is',
                    '/<(strong)([^>]*class=\'[^\']*\bcon\b[^\']*\'[^>]*)>(.*?)<\/\1>/is',
                    // Depois outras tags
                    '/<(div|span|p|h[1-6])([^>]*class="[^"]*\bcon\b[^"]*"[^>]*)>(.*?)<\/\1>/is',
                    '/<(div|span|p|h[1-6])([^>]*class=\'[^\']*\bcon\b[^\']*\'[^>]*)>(.*?)<\/\1>/is',
                ],
            ];
            
            $patternsParaTipo = $patterns[$tipo] ?? $patterns['texto'];
            
            foreach ($patternsParaTipo as $pattern) {
                if (preg_match($pattern, $html, $matches, PREG_OFFSET_CAPTURE)) {
                    $inicio = $offsetInicial + $matches[0][1];
                    $fim = $inicio + strlen($matches[0][0]);
                    $tagHtml = $matches[0][0];
                $tagName = $matches[1][0];
                $atributos = $matches[2][0];
                $conteudo = $matches[3][0];
                
                // Extrair classes - suportar aspas duplas e simples
                $classes = '';
                if (preg_match('/class=["\']([^"\']*)["\']/', $atributos, $classMatch)) {
                    $classes = $classMatch[1];
                }
                
                Log::info("Tag {$tipo} encontrada: offset={$offsetInicial}, inicio={$inicio}, fim={$fim}, tagName={$tagName}, classes={$classes}");
                
                return [
                    'inicio' => $inicio,
                    'fim' => $fim,
                    'classes' => $classes,
                    'tagHtml' => $tagHtml,
                    'tagName' => $tagName,
                    'atributos' => $atributos,
                    'conteudo' => $conteudo
                ];
                }
            }
            
            Log::warning("Tag {$tipo} com classe 'con' não encontrada a partir do offset {$offsetInicial}");
        }
        
        return null;
    }
    
    /**
     * Gerar código Blade do helper correspondente
     */
    private function gerarHelperBlade($tema, $pagina, $campoNome, $tipo, $classes = '', $tagHtml = '', $atributosPreservar = [], $conteudoInterno = null)
    {
        $classesEscapadas = addslashes($classes);
        
        if ($tipo === 'imagem') {
            // Gerar tag <img> preservando a ordem original dos atributos
            // Apenas src e alt serão dinâmicos, os demais atributos mantêm seus valores originais
            
            // Reconstruir a tag preservando a ordem original dos atributos
            $atributosOrdenados = [];
            
            // Primeiro, extrair todos os atributos da tag original na ordem em que aparecem
            if (!empty($tagHtml)) {
                // Extrair a parte de atributos da tag original
                // Usar regex mais flexível para capturar atributos mesmo com código Blade
                if (preg_match('/<img\s+([^>]*)\/?>/i', $tagHtml, $matches)) {
                    $atributosOriginais = $matches[1];
                    
                    // Extrair todos os atributos na ordem original
                    // Regex melhorado para capturar atributos mesmo com {{ }} do Blade
                    preg_match_all('/([a-zA-Z0-9_-]+(?:::[a-zA-Z0-9_-]+)*)=(["\'])((?:[^"\']|{{[^}]*}})*?)\2/', $atributosOriginais, $attrMatches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
                    
                    // Se não encontrou com o padrão acima, tentar padrão mais simples
                    if (empty($attrMatches)) {
                        preg_match_all('/([a-zA-Z0-9_-]+(?:::[a-zA-Z0-9_-]+)*)=["\']([^"\']*)["\']/', $atributosOriginais, $attrMatches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
                    }
                    
                    // Ordenar por posição no texto original
                    if (!empty($attrMatches)) {
                        usort($attrMatches, function($a, $b) {
                            return $a[0][1] <=> $b[0][1];
                        });
                        
                        // Construir a lista de atributos na ordem original
                        foreach ($attrMatches as $attrMatch) {
                            $attrName = $attrMatch[1][0];
                            $attrValue = isset($attrMatch[3]) ? $attrMatch[3][0] : (isset($attrMatch[2]) ? $attrMatch[2][0] : '');
                            $attrNameLower = strtolower($attrName);
                            
                            // Se for src ou alt, marcar para ser dinâmico
                            if ($attrNameLower === 'src') {
                                // IMPORTANTE: Usar {!! !!} para não escapar o HTML e garantir que o Blade processe
                                $atributosOrdenados[] = [
                                    'nome' => 'src',
                                    'valor' => '{!! uploaded_assets(App\Helpers\ContentFormHelper::getCampo(\'' . $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\', \'src\')) !!}',
                                    'dinamico' => true
                                ];
                            } elseif ($attrNameLower === 'alt') {
                                $atributosOrdenados[] = [
                                    'nome' => 'alt',
                                    'valor' => '{{ App\Helpers\ContentFormHelper::getCampo(\'' . $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\', \'alt\') ?: \'\' }}',
                                    'dinamico' => true
                                ];
                            } else {
                                // Preservar o atributo original
                                $atributosOrdenados[] = [
                                    'nome' => $attrName,
                                    'valor' => $attrValue,
                                    'dinamico' => false
                                ];
                            }
                        }
                    }
                }
            }
            
            // Se não conseguiu extrair da tag original, usar os atributos preservados
            if (empty($atributosOrdenados)) {
                // Adicionar src dinâmico primeiro
                // IMPORTANTE: Usar {!! !!} para não escapar o HTML e garantir que o Blade processe
                $atributosOrdenados[] = [
                    'nome' => 'src',
                    'valor' => '{!! uploaded_assets(App\Helpers\ContentFormHelper::getCampo(\'' . $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\', \'src\')) !!}',
                    'dinamico' => true
                ];
                
                // Adicionar outros atributos preservados
                foreach ($atributosPreservar as $attrName => $attrValue) {
                    $atributosOrdenados[] = [
                        'nome' => $attrName,
                        'valor' => $attrValue,
                        'dinamico' => false
                    ];
                }
                
                // Adicionar alt dinâmico
                $atributosOrdenados[] = [
                    'nome' => 'alt',
                    'valor' => '{{ App\Helpers\ContentFormHelper::getCampo(\'' . $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\', \'alt\') ?: \'\' }}',
                    'dinamico' => true
                ];
                
                // Adicionar class se não estiver nos atributos preservados
                if (!isset($atributosPreservar['class']) && !empty($classes)) {
                    $atributosOrdenados[] = [
                        'nome' => 'class',
                        'valor' => $classes,
                        'dinamico' => false
                    ];
                } elseif (isset($atributosPreservar['class'])) {
                    // Se já existe class, garantir que "con" esteja presente
                    $classesAtuais = $atributosPreservar['class'];
                    if (strpos($classesAtuais, 'con') === false) {
                        $classesAtuais .= ' con';
                        // Atualizar o valor no array
                        foreach ($atributosOrdenados as &$attr) {
                            if ($attr['nome'] === 'class') {
                                $attr['valor'] = $classesAtuais;
                                break;
                            }
                        }
                        // Se não encontrou, adicionar
                        if (!isset($attr)) {
                            $atributosOrdenados[] = [
                                'nome' => 'class',
                                'valor' => $classesAtuais,
                                'dinamico' => false
                            ];
                        }
                    }
                } else {
                    // Se não há class, adicionar "con"
                    $atributosOrdenados[] = [
                        'nome' => 'class',
                        'valor' => 'con',
                        'dinamico' => false
                    ];
                }
            }
            
            // Construir a tag final preservando a ordem original
            $atributosStr = '';
            $temClass = false;
            foreach ($atributosOrdenados as $attr) {
                if ($attr['nome'] === 'class') {
                    $temClass = true;
                }
                if ($attr['dinamico']) {
                    // Para atributos dinâmicos (src e alt), o valor já contém {{ }}, então não escapar
                    // Formato: src="{{ uploaded_assets(...) }}"
                    // IMPORTANTE: O valor já é código Blade, então não precisa escapar
                    // Mas precisamos garantir que não há aspas que quebrem o HTML
                    $valorBlade = $attr['valor'];
                    // Remover aspas duplas do código Blade se existirem (não devem existir)
                    $valorBlade = str_replace('"', '&quot;', $valorBlade);
                    $atributosStr .= ' ' . htmlspecialchars($attr['nome'], ENT_QUOTES, 'UTF-8') . '="' . $valorBlade . '"';
                } else {
                    // Para atributos estáticos, escapar normalmente
                    $atributosStr .= ' ' . htmlspecialchars($attr['nome'], ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($attr['valor'], ENT_QUOTES, 'UTF-8') . '"';
                }
            }
            
            // Garantir que a classe "con" esteja sempre presente
            if (!$temClass) {
                $atributosStr .= ' class="con"';
            }
            
            // Garantir que a tag seja construída corretamente
            $helper = '<img' . $atributosStr . '>';
            
            // Log para debug (remover em produção se necessário)
            Log::info("Tag img gerada: " . substr($helper, 0, 200));
            
            return $helper;
        } elseif ($tipo === 'link') {
            // Gerar tag <a> preservando a ordem original dos atributos
            // Apenas href, title (ou alt) e texto serão dinâmicos
            $atributosOrdenados = [];
            
            // Primeiro, extrair todos os atributos da tag original na ordem em que aparecem
            if (!empty($tagHtml)) {
                // Extrair a parte de atributos da tag original
                if (preg_match('/<a\s+([^>]*)>(.*?)<\/a>/is', $tagHtml, $matches)) {
                    $atributosOriginais = $matches[1];
                    
                    // Extrair todos os atributos na ordem original
                    preg_match_all('/([a-zA-Z0-9_-]+(?:::[a-zA-Z0-9_-]+)*)=(["\'])((?:[^"\']|{{[^}]*}})*?)\2/', $atributosOriginais, $attrMatches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
                    
                    // Se não encontrou com o padrão acima, tentar padrão mais simples
                    if (empty($attrMatches)) {
                        preg_match_all('/([a-zA-Z0-9_-]+(?:::[a-zA-Z0-9_-]+)*)=["\']([^"\']*)["\']/', $atributosOriginais, $attrMatches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
                    }
                    
                    // Ordenar por posição no texto original
                    if (!empty($attrMatches)) {
                        usort($attrMatches, function($a, $b) {
                            return $a[0][1] <=> $b[0][1];
                        });
                        
                        // Construir a lista de atributos na ordem original
                        foreach ($attrMatches as $attrMatch) {
                            $attrName = $attrMatch[1][0];
                            $attrValue = isset($attrMatch[3]) ? $attrMatch[3][0] : (isset($attrMatch[2]) ? $attrMatch[2][0] : '');
                            $attrNameLower = strtolower($attrName);
                            
                            // Se for href, alt_link ou alt, marcar para ser dinâmico
                            if ($attrNameLower === 'href') {
                                $atributosOrdenados[] = [
                                    'nome' => 'href',
                                    'valor' => '{{ App\Helpers\ContentFormHelper::getCampo(\'' . $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\', \'href\') ?: \'#\' }}',
                                    'dinamico' => true
                                ];
                            } elseif ($attrNameLower === 'alt_link' || $attrNameLower === 'alt') {
                                $atributosOrdenados[] = [
                                    'nome' => 'alt_link',
                                    'valor' => '{{ App\Helpers\ContentFormHelper::getCampo(\'' . $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\', \'alt_link\') ?: \'\' }}',
                                    'dinamico' => true
                                ];
                            } else {
                                // Preservar o atributo original
                                $atributosOrdenados[] = [
                                    'nome' => $attrName,
                                    'valor' => $attrValue,
                                    'dinamico' => false
                                ];
                            }
                        }
                    }
                }
            }
            
            // Se não conseguiu extrair da tag original, usar os atributos preservados
            if (empty($atributosOrdenados)) {
                // Adicionar href dinâmico primeiro
                $atributosOrdenados[] = [
                    'nome' => 'href',
                    'valor' => '{{ App\Helpers\ContentFormHelper::getCampo(\'' . $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\', \'href\') ?: \'#\' }}',
                    'dinamico' => true
                ];
                
                // Adicionar outros atributos preservados
                foreach ($atributosPreservar as $attrName => $attrValue) {
                    $atributosOrdenados[] = [
                        'nome' => $attrName,
                        'valor' => $attrValue,
                        'dinamico' => false
                    ];
                }
                
                // Adicionar alt_link dinâmico
                $atributosOrdenados[] = [
                    'nome' => 'alt_link',
                    'valor' => '{{ App\Helpers\ContentFormHelper::getCampo(\'' . $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\', \'alt_link\') ?: \'\' }}',
                    'dinamico' => true
                ];
                
                // Adicionar class se não estiver nos atributos preservados
                if (!isset($atributosPreservar['class']) && !empty($classes)) {
                    $atributosOrdenados[] = [
                        'nome' => 'class',
                        'valor' => $classes,
                        'dinamico' => false
                    ];
                } elseif (isset($atributosPreservar['class'])) {
                    // Se já existe class, garantir que "con" esteja presente
                    $classesAtuais = $atributosPreservar['class'];
                    if (strpos($classesAtuais, 'con') === false) {
                        $classesAtuais .= ' con';
                        // Atualizar o valor no array
                        foreach ($atributosOrdenados as &$attr) {
                            if ($attr['nome'] === 'class') {
                                $attr['valor'] = $classesAtuais;
                                break;
                            }
                        }
                        // Se não encontrou, adicionar
                        if (!isset($attr)) {
                            $atributosOrdenados[] = [
                                'nome' => 'class',
                                'valor' => $classesAtuais,
                                'dinamico' => false
                            ];
                        }
                    }
                } else {
                    // Se não há class, adicionar "con"
                    $atributosOrdenados[] = [
                        'nome' => 'class',
                        'valor' => 'con',
                        'dinamico' => false
                    ];
                }
            }
            
            // Construir a tag final preservando a ordem original
            $atributosStr = '';
            $temClass = false;
            foreach ($atributosOrdenados as $attr) {
                if ($attr['nome'] === 'class') {
                    $temClass = true;
                }
                if ($attr['dinamico']) {
                    // Para atributos dinâmicos, o valor já contém {{ }}, então não escapar
                    $atributosStr .= ' ' . htmlspecialchars($attr['nome'], ENT_QUOTES, 'UTF-8') . '="' . $attr['valor'] . '"';
                } else {
                    // Para atributos estáticos, escapar normalmente
                    $atributosStr .= ' ' . htmlspecialchars($attr['nome'], ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($attr['valor'], ENT_QUOTES, 'UTF-8') . '"';
                }
            }
            
            // Garantir que a classe "con" esteja sempre presente
            if (!$temClass) {
                $atributosStr .= ' class="con"';
            }
            
            // Preservar o conteúdo interno da tag <a> (div, img, etc.)
            // Se não houver conteúdo interno fornecido, usar texto dinâmico do banco
            if ($conteudoInterno !== null && !empty($conteudoInterno)) {
                // Preservar o conteúdo HTML interno completo
                $conteudoFinal = $conteudoInterno;
            } else {
                // IMPORTANTE: Usar {!! !!} para preservar HTML quando há tags aninhadas
                // O valor do banco pode conter HTML (ex: <strong>Saiba Mais</strong>)
                $conteudoFinal = '{!! App\Helpers\ContentFormHelper::getCampo(\'' . 
                                $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\', \'texto\') ?: \'\' !!}';
            }
            
            // Gerar o formato correto: href e alt_link dinâmicos, conteúdo interno preservado
            $helper = '<a' . $atributosStr . '>' . $conteudoFinal . '</a>';
            
            return $helper;
        } else {
            // Para texto, título, parágrafo - preservar a tag e substituir apenas o conteúdo
            $tagName = '';
            $atributos = '';
            
            // Extrair tag name e atributos da tag HTML original
            if (preg_match('/<([a-zA-Z0-9]+)([^>]*)>/i', $tagHtml, $matches)) {
                $tagName = $matches[1];
                $atributos = $matches[2];
            }
            
            // Log para debug - verificar o que está sendo recebido
            Log::info("gerarHelperBlade INICIO para {$tipo} (campo={$campoNome}): tagHtml=" . substr($tagHtml, 0, 150) . ", classes recebidas='{$classes}', atributos extraídos=" . substr($atributos, 0, 100));
            
            // IMPORTANTE: Sempre preservar a classe "con" dos atributos originais
            // Priorizar as classes extraídas pelo método encontrarTagNoHtml que já garante a classe "con"
            if (!empty($classes)) {
                // Se temos classes extraídas (que já contêm "con"), usar essas classes
                Log::info("gerarHelperBlade: Usando classes extraídas '{$classes}' para campo {$campoNome}");
                if (preg_match('/class=["\']([^"\']*)["\']/', $atributos, $classMatch)) {
                    // Substituir a classe existente pelas classes extraídas (que garantem "con")
                    $atributos = preg_replace('/class=["\']([^"\']*)["\']/', 'class="' . $classes . '"', $atributos);
                    Log::info("gerarHelperBlade: Substituída classe existente pelas classes extraídas");
                } else {
                    // Se não há atributo class, adicionar as classes extraídas
                    $atributos .= ' class="' . $classes . '"';
                    Log::info("gerarHelperBlade: Adicionadas classes extraídas (não havia class antes)");
                }
            } else {
                // Se não temos classes extraídas, verificar se já existe class nos atributos
                Log::warning("gerarHelperBlade: Nenhuma classe extraída recebida para campo {$campoNome}, verificando atributos originais");
                if (preg_match('/class=["\']([^"\']*)["\']/', $atributos, $classMatch)) {
                    $classesAtuais = $classMatch[1];
                    Log::info("gerarHelperBlade: Classes encontradas nos atributos: '{$classesAtuais}'");
                    // Se a classe "con" não está presente, adicioná-la
                    if (strpos($classesAtuais, 'con') === false) {
                        $classesAtuais .= ' con';
                        $atributos = preg_replace('/class=["\']([^"\']*)["\']/', 'class="' . $classesAtuais . '"', $atributos);
                        Log::info("gerarHelperBlade: Adicionada classe 'con' às classes existentes");
                    } else {
                        Log::info("gerarHelperBlade: Classe 'con' já presente nos atributos");
                    }
                } else {
                    // Se não há atributo class, adicionar apenas "con"
                    $atributos .= ' class="con"';
                    Log::info("gerarHelperBlade: Adicionada apenas classe 'con' (não havia class antes)");
                }
            }
            
            // Log para debug - verificar o resultado final
            Log::info("gerarHelperBlade FIM para {$tipo} (campo={$campoNome}): tagName={$tagName}, atributos finais=" . substr($atributos, 0, 150));
            
            return '<' . $tagName . $atributos . '>{!! App\Helpers\ContentFormHelper::getCampo(\'' . $tema . '\', \'' . $pagina . '\', \'' . $campoNome . '\') !!}</' . $tagName . '>';
        }
    }
    
    /**
     * Extrair seções da configuração (suporta formato antigo e novo)
     */
    private function extrairSecoes($configuracao)
    {
        if (is_string($configuracao)) {
            $configuracao = json_decode($configuracao, true);
        }
        
        // Novo formato: { html_original: "...", secoes: [...] }
        if (isset($configuracao['secoes']) && is_array($configuracao['secoes'])) {
            return $configuracao['secoes'];
        }
        
        // Formato antigo: array direto de seções
        if (is_array($configuracao) && isset($configuracao[0]) && is_array($configuracao[0])) {
            return $configuracao;
        }
        
        return [];
    }
    
    /**
     * Reconstruir HTML original a partir dos valores salvos no banco
     */
    private function reconstruirHtmlOriginal($htmlAtual, $secoes)
    {
        try {
            $htmlResultado = $htmlAtual;
            
            // Coletar todas as substituições primeiro
            $substituicoes = [];
            
            // Processar cada seção e seus campos
            foreach ($secoes as $secao) {
                if (!isset($secao['campos'])) {
                    continue;
                }
                
                foreach ($secao['campos'] as $campo) {
                    if (!isset($campo['nome']) || !isset($campo['tipo'])) {
                        continue;
                    }
                    
                    $campoNome = $campo['nome'];
                    $tipo = $campo['tipo'];
                    $campoNomeEscapado = preg_quote($campoNome, '/');
                    
                    if ($tipo === 'imagem') {
                        $srcOriginal = $campo['src'] ?? null;
                        $altOriginal = $campo['alt'] ?? '';
                        
                        if ($srcOriginal) {
                            // Padrão flexível que busca o nome do campo em qualquer parâmetro
                            // Formato: {!! App\Helpers\ContentFormHelper::renderImagem('prestacon', 'home', 'imagem_4', 'primary-icon con') !!}
                            $pattern = '/\{!!\s*App\\\\?\\\\?Helpers\\\\?\\\\?ContentFormHelper::renderImagem\([^)]*' . $campoNomeEscapado . '[^)]*\)\s*!!\}/i';
                            
                            // Tentar encontrar classes originais se houver
                            $classes = 'con';
                            if (preg_match($pattern, $htmlResultado, $match)) {
                                // Extrair classes do helper se existirem (quarto parâmetro)
                                if (preg_match('/\'([^\']*' . preg_quote('con', '/') . '[^\']*)\'/', $match[0], $classMatch)) {
                                    $classes = $classMatch[1];
                                }
                            }
                            
                            // Reconstruir tag img
                            $replacement = '<img src="' . htmlspecialchars($srcOriginal, ENT_QUOTES, 'UTF-8') . '"' . 
                                         ($altOriginal ? ' alt="' . htmlspecialchars($altOriginal, ENT_QUOTES, 'UTF-8') . '"' : '') . 
                                         ' class="' . $classes . '">';
                            
                            $substituicoes[] = ['pattern' => $pattern, 'replacement' => $replacement, 'offset' => 0];
                        }
                    } elseif ($tipo === 'link') {
                        $hrefOriginal = $campo['href'] ?? null;
                        $textoOriginal = $campo['texto'] ?? '';
                        
                        if ($hrefOriginal) {
                            // Padrão flexível para renderLink
                            $pattern = '/\{!!\s*App\\\\?\\\\?Helpers\\\\?\\\\?ContentFormHelper::renderLink\([^)]*' . $campoNomeEscapado . '[^)]*\)\s*!!\}/i';
                            
                            $classes = 'con';
                            if (preg_match($pattern, $htmlResultado, $match)) {
                                if (preg_match('/\'([^\']*' . preg_quote('con', '/') . '[^\']*)\'/', $match[0], $classMatch)) {
                                    $classes = $classMatch[1];
                                }
                            }
                            
                            $replacement = '<a href="' . htmlspecialchars($hrefOriginal, ENT_QUOTES, 'UTF-8') . '" class="' . $classes . '">' . 
                                         htmlspecialchars($textoOriginal, ENT_QUOTES, 'UTF-8') . '</a>';
                            
                            $substituicoes[] = ['pattern' => $pattern, 'replacement' => $replacement, 'offset' => 0];
                        }
                    } else {
                        // Para texto, título, parágrafo
                        $valorOriginal = $campo['valor'] ?? null;
                        
                        if ($valorOriginal !== null) {
                            // Padrão flexível que busca o nome do campo em qualquer parâmetro
                            // Formato: {!! App\Helpers\ContentFormHelper::getCampo('prestacon', 'home', 'texto_1') !!}
                            $pattern = '/\{!!\s*App\\\\?\\\\?Helpers\\\\?\\\\?ContentFormHelper::getCampo\([^)]*' . $campoNomeEscapado . '[^)]*\)\s*!!\}/i';
                            
                            $replacement = htmlspecialchars($valorOriginal, ENT_QUOTES, 'UTF-8');
                            
                            $substituicoes[] = ['pattern' => $pattern, 'replacement' => $replacement, 'offset' => 0];
                        }
                    }
                }
            }
            
            // Aplicar substituições (processar na ordem inversa para não quebrar offsets)
            // Primeiro encontrar todas as posições
            $posicoes = [];
            foreach ($substituicoes as $index => $sub) {
                // Usar preg_match_all para encontrar todas as ocorrências
                $count = preg_match_all($sub['pattern'], $htmlResultado, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
                if ($count > 0) {
                    foreach ($matches as $match) {
                        $posicoes[] = [
                            'index' => $index,
                            'offset' => $match[0][1],
                            'length' => strlen($match[0][0]),
                            'pattern' => $sub['pattern'],
                            'replacement' => $sub['replacement'],
                            'match' => $match[0][0]
                        ];
                    }
                }
            }
            
            // Ordenar por offset (do final para o início)
            usort($posicoes, function($a, $b) {
                return $b['offset'] <=> $a['offset'];
            });
            
            // Aplicar substituições do final para o início
            foreach ($posicoes as $pos) {
                if ($pos['offset'] < strlen($htmlResultado)) {
                    $antes = substr($htmlResultado, 0, $pos['offset']);
                    $depois = substr($htmlResultado, $pos['offset'] + $pos['length']);
                    $htmlResultado = $antes . $pos['replacement'] . $depois;
                }
            }
            
            Log::info("HTML reconstruído: " . count($posicoes) . " substituições aplicadas");
            
            return $htmlResultado;
        } catch (\Exception $e) {
            Log::error('Erro ao reconstruir HTML original: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Excluir formulário de conteúdo e restaurar HTML original
     */
    public function destroyContentForm($pagina, $formularioId)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        try {
            // Buscar o formulário antes de excluir para obter o HTML original
            $formulario = DB::table('content_forms')
                ->where('id', $formularioId)
                ->where('tema', $temaAtivo)
                ->where('pagina', $pagina)
                ->first();
            
            if (!$formulario) {
                return back()->with('error', 'Formulário não encontrado.');
            }
            
            // IMPORTANTE: Restaurar HTML com os valores salvos no banco antes de excluir
            $configuracao = json_decode($formulario->configuracao, true);
            $secoes = $this->extrairSecoes($configuracao);
            
            $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
            $arquivoPagina = $temaViewsPath . '/' . $pagina . '.blade.php';
            
            $htmlRestaurado = false;
            
            if (File::exists($arquivoPagina)) {
                $htmlAtual = File::get($arquivoPagina);
                
                // Reconstruir HTML substituindo helpers pelos valores SALVOS NO BANCO
                // O método reconstruirHtmlOriginal já usa os valores do banco (vem de $secoes)
                $htmlRestaurado = $this->reconstruirHtmlOriginal($htmlAtual, $secoes);
                
                if ($htmlRestaurado) {
                    // Salvar HTML com valores do banco antes de excluir
                    File::put($arquivoPagina, $htmlRestaurado);
                    Log::info("HTML restaurado com valores salvos no banco para {$temaAtivo}/{$pagina}");
                } else {
                    Log::warning("Não foi possível restaurar HTML com valores salvos para {$temaAtivo}/{$pagina}");
                }
            } else {
                Log::warning("Arquivo não encontrado para restaurar: {$arquivoPagina}");
            }
            
            // Excluir o formulário do banco
            DB::table('content_forms')
                ->where('id', $formularioId)
                ->where('tema', $temaAtivo)
                ->where('pagina', $pagina)
                ->delete();
            
            return redirect()->route('dashboard.theme-pages.show', $pagina)
                ->with('success', 'Formulário excluído com sucesso! ' . ($htmlRestaurado ? 'O conteúdo da página foi restaurado com os valores salvos no banco de dados.' : ''));
                
        } catch (\Exception $e) {
            Log::error('Erro ao excluir formulário: ' . $e->getMessage());
            
            // Mensagem mais amigável para erros de permissão
            $mensagemErro = $e->getMessage();
            if (strpos($mensagemErro, 'Permission denied') !== false || strpos($mensagemErro, 'Failed to open stream') !== false) {
                $mensagemErro = 'Erro de permissão ao escrever no arquivo. Verifique as permissões do arquivo da página. O formulário foi excluído do banco de dados, mas o arquivo não pôde ser atualizado.';
            }
            
            return back()->with('error', 'Erro ao excluir formulário: ' . $mensagemErro);
        }
    }
}
