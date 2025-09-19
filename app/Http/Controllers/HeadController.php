<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\HeadHelper;

class HeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Acesso negado. Você não tem permissão de administrador.');
            }
            return $next($request);
        });
    }
    
    public function index()
    {
        // Obter tema ativo
        $temaAtivo = \App\Helpers\ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado (não main-Thema)
        if ($temaAtivo === 'main-Thema') {
            return redirect()->route('dashboard.temas')->with('info', 'As configurações de Head são aplicadas apenas aos temas personalizados. O tema principal (main-Thema) usa dados fixos.');
        }
        
        // Verificar se o tema personalizado existe
        $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
        if (!file_exists($temaViewsPath)) {
            return redirect()->route('dashboard.temas')->with('error', 'Tema personalizado não encontrado.');
        }
        
        // Criar configurações específicas do tema se não existirem
        $this->criarConfiguracoesTemaSeNecessario($temaAtivo);
        
        // Buscar todas as configurações de páginas para o tema ativo
        $allConfigs = HeadHelper::getAllConfigs($temaAtivo);
        
        // Definir páginas disponíveis
        $paginas = [
            // Páginas removidas conforme solicitado: Página Inicial, Sobre Nós, Contato, Login
        ];
        
        return view('dashboard.head.index', compact('allConfigs', 'paginas', 'temaAtivo'));
    }
    
    public function update(Request $request)
    {
        // Verificar se é um tema personalizado
        $temaAtivo = \App\Helpers\ThemeHelper::getActiveTheme();
        if ($temaAtivo === 'main-Thema') {
            return redirect()->route('dashboard.temas')->with('error', 'As configurações de Head são aplicadas apenas aos temas personalizados.');
        }
        
        $request->validate([
            'pagina' => 'required|string|max:50',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'gtm_head' => 'nullable|string',
            'gtm_body' => 'nullable|string',
            'favicon' => 'nullable|image|mimes:webp|max:2048',
            'favicon_filename' => 'nullable|string',
        ], [
            'meta_title.max' => 'O título deve ter no máximo 60 caracteres.',
            'meta_description.max' => 'A descrição deve ter no máximo 160 caracteres.',
            'meta_keywords.max' => 'As palavras-chave devem ter no máximo 255 caracteres.',
            'favicon.image' => 'O arquivo deve ser uma imagem.',
            'favicon.mimes' => 'O arquivo deve ser no formato WebP.',
            'favicon.max' => 'O arquivo deve ter no máximo 2MB.',
        ]);
        
        try {
            $updateData = [
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'gtm_head' => $request->gtm_head,
                'gtm_body' => $request->gtm_body,
                'updated_at' => now(),
            ];
            
            // Processar upload do favicon se fornecido
            if ($request->hasFile('favicon')) {
                $file = $request->file('favicon');
                $filename = 'favicon_' . time() . '.webp';
                
                // Usar o driver local em vez de public
                $path = $file->storeAs('uploads/favicons', $filename, 'local');
                $updateData['favicon'] = $path;
            } elseif ($request->favicon_filename) {
                // Usar favicon existente selecionado da galeria
                $filename = $request->favicon_filename;
                $faviconPath = 'uploads/favicons/' . $filename;
                $logoPath = 'uploads/logos/' . $filename;
                
                // Verificar se existe em favicons ou logos
                if (file_exists(storage_path('app/' . $faviconPath))) {
                    $updateData['favicon'] = $faviconPath;
                } elseif (file_exists(storage_path('app/' . $logoPath))) {
                    // Se está em logos, copiar para favicons
                    $sourcePath = storage_path('app/' . $logoPath);
                    $targetPath = storage_path('app/' . $faviconPath);
                    
                    // Criar diretório de destino se não existir
                    if (!is_dir(dirname($targetPath))) {
                        mkdir(dirname($targetPath), 0755, true);
                    }
                    
                    // Copiar arquivo para favicons
                    if (copy($sourcePath, $targetPath)) {
                        $updateData['favicon'] = $faviconPath;
                    } else {
                        // Se não conseguir copiar, usar o caminho original
                        $updateData['favicon'] = $logoPath;
                    }
                }
            }
            
            // Obter tema ativo
            $temaAtivo = \App\Helpers\ThemeHelper::getActiveTheme();
            
            // Adicionar tema aos dados
            $updateData['tema'] = $temaAtivo;
            
            DB::table('head_configs')->updateOrInsert(
                ['pagina' => $request->pagina, 'tema' => $temaAtivo],
                $updateData
            );
            
            // Limpar cache do helper
            if (method_exists(HeadHelper::class, 'clearCache')) {
                HeadHelper::clearCache($request->pagina, $temaAtivo);
            }
            
            $paginaNome = $this->getPaginaNome($request->pagina);
            return redirect()->route('dashboard.head')->with('success', "Configurações da página '{$paginaNome}' salvas com sucesso!");
            
        } catch (\Exception $e) {
            return redirect()->route('dashboard.head')->with('error', 'Erro ao atualizar configurações: ' . $e->getMessage());
        }
    }
    
    public function getImages()
    {
        try {
            $faviconDir = storage_path('app/uploads/favicons');
            $logoDir = storage_path('app/uploads/logos');

            $images = [];
            $processedFiles = []; // Para evitar duplicatas

            // Buscar favicons
            if (is_dir($faviconDir)) {
                $faviconFiles = glob($faviconDir . '/*.{webp,png,jpg,jpeg}', GLOB_BRACE);
                foreach ($faviconFiles as $file) {
                    $filename = basename($file);
                    if (!in_array($filename, $processedFiles)) {
                        $images[] = [
                            'filename' => $filename,
                            'url' => route('favicon', ['filename' => $filename]),
                            'type' => 'favicon',
                            'size' => filesize($file),
                            'modified' => filemtime($file)
                        ];
                        $processedFiles[] = $filename;
                    }
                }
            }

            // Buscar logos
            if (is_dir($logoDir)) {
                $logoFiles = glob($logoDir . '/*.{webp,png,jpg,jpeg}', GLOB_BRACE);
                foreach ($logoFiles as $file) {
                    $filename = basename($file);
                    if (!in_array($filename, $processedFiles)) {
                        $images[] = [
                            'filename' => $filename,
                            'url' => route('logo', ['filename' => $filename]),
                            'type' => 'logo',
                            'size' => filesize($file),
                            'modified' => filemtime($file)
                        ];
                        $processedFiles[] = $filename;
                    }
                }
            }

            // Ordenar por data de modificação (mais recentes primeiro)
            usort($images, function($a, $b) {
                return $b['modified'] - $a['modified'];
            });

            return response()->json($images);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    private function getPaginaNome($pagina)
    {
        $nomes = [
            'home' => 'Página Inicial',
            'sobre' => 'Sobre Nós',
            'contato' => 'Contato',
            'login' => 'Login'
        ];
        
        return $nomes[$pagina] ?? ucfirst($pagina);
    }
    
    private function criarConfiguracoesTemaSeNecessario($tema)
    {
        $paginas = ['global', 'home', 'sobre', 'contato', 'login'];
        
        foreach ($paginas as $pagina) {
            // Verificar se já existe configuração específica do tema
            $existe = DB::table('head_configs')
                ->where('pagina', $pagina)
                ->where('tema', $tema)
                ->exists();
            
            if (!$existe) {
                // Buscar configuração global para usar como base
                $configGlobal = DB::table('head_configs')
                    ->where('pagina', $pagina)
                    ->where('tema', 'global')
                    ->first();
                
                if ($configGlobal) {
                    // Criar configuração específica do tema baseada na global
                    $configTema = (array) $configGlobal;
                    $configTema['tema'] = $tema;
                    $configTema['created_at'] = now();
                    $configTema['updated_at'] = now();
                    unset($configTema['id']); // Remove o ID para criar novo registro
                    
                    DB::table('head_configs')->insert($configTema);
                }
            }
        }
    }
}