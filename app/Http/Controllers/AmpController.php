<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Helpers\AMPHelper;
use App\Helpers\HeadHelper;
use App\Helpers\ThemeHelper;

class AmpController extends Controller
{
    /**
     * Renderiza uma página em versão AMP
     * 
     * @param Request $request
     * @param string|null $tema
     * @param string|null $pagina
     * @param string|null $slug
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function render(Request $request, $tema = null, $pagina = null, $slug = null)
    {
        try {
            // Se não foi especificado tema e página, tentar detectar da URL
            if (!$tema || !$pagina) {
                $path = $request->path();
                
                // Remover /amp do início
                if (str_starts_with($path, 'amp/')) {
                    $path = substr($path, 4);
                } elseif (str_starts_with($path, 'amp')) {
                    $path = substr($path, 3);
                }
                
                // Remover barra inicial
                $path = ltrim($path, '/');
                
                // Tentar obter tema ativo
                $temaAtivo = config('tema_principal.tema_principal');
                
                if (!$temaAtivo) {
                    abort(404, 'Tema não configurado');
                }
                
                $tema = $temaAtivo;
                
                // Determinar página baseada no path
                if (empty($path) || $path === '/') {
                    $pagina = 'home';
                } else {
                    // Verificar se é uma rota dinâmica
                    $rotaDinamica = DB::table('rotas_dinamicas')
                        ->where('tema', $tema)
                        ->where('rota', '/' . $path)
                        ->where('ativo', 1)
                        ->first();
                    
                    if ($rotaDinamica) {
                        $pagina = $rotaDinamica->pagina;
                    } else {
                        // Tentar mapear path para página
                        $pagina = $this->mapPathToPage($path);
                    }
                }
            }
            
            // Verificar se o tema existe
            if (!ThemeHelper::themeExists($tema)) {
                abort(404, 'Tema não encontrado');
            }
            
            // Verificar se o tema suporta AMP
            if (!AMPHelper::themeSupportsAMP($tema)) {
                // Se não suporta AMP, redirecionar para versão normal
                $canonicalUrl = AMPHelper::getCanonicalUrl($request->fullUrl());
                return redirect($canonicalUrl, 301);
            }
            
            // Verificar se a página existe
            $temaViewsPath = resource_path('views/temas/' . $tema);
            $arquivoBlade = $temaViewsPath . '/' . $pagina . '.blade.php';
            
            if (!File::exists($arquivoBlade)) {
                abort(404, 'Página não encontrada');
            }
            
            // Preparar dados para a view
            $dados = [
                'currentPage' => $pagina,
                'isAMP' => true,
                'canonicalUrl' => AMPHelper::getCanonicalUrl($request->fullUrl())
            ];
            
            // Para a página de blogs, buscar posts do banco
            if ($pagina === 'blogs') {
                $posts = \App\Models\Post::where('ativo', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(9); // 9 posts por página
                
                $dados['blogs'] = $posts->map(function($post) use ($tema) {
                    return [
                        'title' => $post->titulo,
                        'slug' => $post->slug ?? \Str::slug($post->titulo),
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
            $rotaComSlug = DB::table('rotas_dinamicas')
                ->where('tema', $tema)
                ->where('pagina', $pagina)
                ->where('rota', 'like', '%{slug}%')
                ->where('ativo', 1)
                ->first();
            
            if ($rotaComSlug) {
                $postSlug = $slug ?? $request->route('slug') ?? $request->get('slug');
                
                if ($postSlug) {
                    $post = DB::table('posts')
                        ->where('ativo', 1)
                        ->where(function($query) use ($postSlug) {
                            $query->where('slug', $postSlug)
                                  ->orWhere('slug', \Str::slug($postSlug));
                        })
                        ->first();
                    
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
                            'meta_title' => $post->meta_title ?? $post->titulo,
                            'meta_description' => $post->meta_description ?? \Str::limit(strip_tags($post->conteudo), 160),
                            'meta_keywords' => $post->meta_keywords ?? ''
                        ];
                    } else {
                        abort(404, 'Post não encontrado');
                    }
                } else {
                    abort(404, 'Slug do post não fornecido');
                }
            }
            
            // Renderizar a página AMP
            $viewPath = 'temas.' . $tema . '.' . $pagina;
            
            // Verificar se existe view AMP específica
            $ampViewPath = resource_path("views/temas/{$tema}/{$pagina}.amp.blade.php");
            if (File::exists($ampViewPath)) {
                // Usar view AMP específica
                $viewPath = 'temas.' . $tema . '.' . $pagina . '.amp';
            }
            
            // Verificar se existe layout AMP específico do tema
            $ampLayoutPath = resource_path("views/temas/{$tema}/layouts/amp.blade.php");
            if (File::exists($ampLayoutPath)) {
                // Adicionar layout AMP aos dados para a view usar
                $dados['ampLayout'] = "temas.{$tema}.layouts.amp";
            } else {
                // Usar layout AMP genérico
                $dados['ampLayout'] = 'layouts.amp';
            }
            
            // Renderizar view (a view deve usar @extends com $ampLayout se necessário)
            return view($viewPath, $dados);
            
        } catch (\Exception $e) {
            \Log::error("Erro ao renderizar página AMP: " . $e->getMessage(), [
                'tema' => $tema,
                'pagina' => $pagina,
                'slug' => $slug,
                'trace' => $e->getTraceAsString()
            ]);
            
            abort(500, 'Erro ao renderizar página AMP');
        }
    }
    
    /**
     * Mapeia um path para o nome da página
     * 
     * @param string $path
     * @return string
     */
    private function mapPathToPage($path)
    {
        $mapping = [
            '' => 'home',
            '/' => 'home',
            'home' => 'home',
            'sobre' => 'sobre',
            'contato' => 'contato',
            'blog' => 'blogs',
            'blogs' => 'blogs',
        ];
        
        // Remover barra inicial e final
        $path = trim($path, '/');
        
        return $mapping[$path] ?? $path;
    }
    
    /**
     * Valida uma página AMP
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateAmp(Request $request)
    {
        $url = $request->input('url');
        
        if (!$url) {
            return response()->json([
                'valid' => false,
                'error' => 'URL não fornecida'
            ], 400);
        }
        
        try {
            // Buscar conteúdo da página
            $content = file_get_contents($url);
            
            if (!$content) {
                return response()->json([
                    'valid' => false,
                    'error' => 'Não foi possível buscar o conteúdo da página'
                ], 400);
            }
            
            // Validar AMP
            $validation = AMPHelper::validateAMP($content);
            
            return response()->json($validation);
            
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

