<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\HeadHelper;
use App\Helpers\ThemeHelper;

class ThemePageController extends Controller
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
    
    /**
     * Listar todas as páginas do tema ativo
     */
    public function index()
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Verificar se é um tema personalizado
        if ($temaAtivo === 'main-Thema') {
            return redirect()->route('dashboard.temas')->with('info', 'As configurações de páginas são aplicadas apenas aos temas personalizados.');
        }
        
        // Verificar se o tema personalizado existe
        $temaViewsPath = resource_path('views/temas/' . $temaAtivo);
        if (!file_exists($temaViewsPath)) {
            return redirect()->route('dashboard.temas')->with('error', 'Tema personalizado não encontrado.');
        }
        
        // Obter páginas do tema
        $paginas = $this->obterPaginasDoTema($temaAtivo);
        
        // Obter configurações existentes
        $configuracoes = HeadHelper::getAllConfigs($temaAtivo);
        
        return view('dashboard.theme-pages.index', compact('paginas', 'configuracoes', 'temaAtivo'));
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
        
        // Obter configuração da página
        $configuracao = HeadHelper::getConfigs($pagina, $temaAtivo);
        
        return view('dashboard.theme-pages.show', compact('pagina', 'configuracao', 'temaAtivo'));
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
            'meta_description' => 'nullable|string|max:160',
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
            
            return redirect()->route('dashboard.theme-pages.show', $pagina)
                ->with('success', 'Configurações da página "' . ucfirst($pagina) . '" atualizadas com sucesso!');
                
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar configurações da página: ' . $e->getMessage());
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
        
        $paginas = collect(\File::files($temaViewsPath))
            ->filter(function($arquivo) {
                $nome = $arquivo->getFilename();
                return str_ends_with($nome, '.blade.php') && 
                       !str_contains($arquivo->getPathname(), 'inc') &&
                       !str_contains($arquivo->getPathname(), 'layouts') &&
                       !str_contains($arquivo->getPathname(), 'auth');
            })
            ->map(function($arquivo) {
                return strtolower(basename($arquivo->getFilename(), '.blade.php'));
            })
            ->sort()
            ->values()
            ->toArray();
        
        return $paginas;
    }
}
