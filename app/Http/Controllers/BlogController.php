<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct()
    {
        // Aplicar middleware de auth apenas aos métodos administrativos
        $this->middleware('auth')->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy', 'toggleStatus'
        ]);
    }

    /**
     * Listar todos os posts do blog
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        
        return view('dashboard.blog.index', compact('posts'));
    }

    /**
     * Mostrar formulário para criar novo post
     */
    public function create()
    {
        return view('dashboard.blog.create');
    }

    /**
     * Salvar novo post
     */
    public function store(Request $request)
    {

        $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'imagem_apresentacao' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'conteudo' => 'required|string'
        ]);

        $data = $request->all();

        // Gerar slug único
        if (!empty($data['slug'])) {
            // Usar slug personalizada fornecida pelo usuário
            $slug = Str::slug($data['slug']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Post::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        } else {
            // Gerar slug automaticamente baseado no título
            $slug = Str::slug($data['titulo']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Post::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        // Upload da imagem se fornecida
        if ($request->hasFile('imagem_apresentacao')) {
            $imagem = $request->file('imagem_apresentacao');
            $nomeImagem = time() . '_' . Str::slug($data['titulo']) . '.' . $imagem->getClientOriginalExtension();
            
            // Criar diretório se não existir
            $diretorio = 'public/posts';
            if (!Storage::exists($diretorio)) {
                Storage::makeDirectory($diretorio);
            }
            
            $imagem->storeAs($diretorio, $nomeImagem);
            $data['imagem_apresentacao'] = $nomeImagem;
        }

        // Converter ativo para boolean
        $data['ativo'] = $request->has('ativo') && $request->input('ativo') == '1' ? true : false;

        try {
            $post = Post::create($data);
            
            return redirect()->route('dashboard.blog')
                ->with('success', 'Post criado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao criar post: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar formulário para editar post
     */
    public function edit(Post $post)
    {
        return view('dashboard.blog.edit', compact('post'));
    }

    /**
     * Atualizar post
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'imagem_apresentacao' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'conteudo' => 'required|string',
            'ativo' => 'boolean'
        ]);

        $data = $request->all();

        // Gerar novo slug se necessário
        if (!empty($data['slug'])) {
            // Usar slug personalizada fornecida pelo usuário
            $slug = Str::slug($data['slug']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        } elseif ($post->titulo !== $data['titulo']) {
            // Gerar slug automaticamente se o título mudou
            $slug = Str::slug($data['titulo']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        // Upload da nova imagem se fornecida
        if ($request->hasFile('imagem_apresentacao')) {
            // Deletar imagem antiga se existir
            if ($post->imagem_apresentacao && Storage::exists('public/posts/' . $post->imagem_apresentacao)) {
                Storage::delete('public/posts/' . $post->imagem_apresentacao);
            }

            $imagem = $request->file('imagem_apresentacao');
            $nomeImagem = time() . '_' . Str::slug($data['titulo']) . '.' . $imagem->getClientOriginalExtension();
            
            $imagem->storeAs('public/posts', $nomeImagem);
            $data['imagem_apresentacao'] = $nomeImagem;
        }

        // Converter ativo para boolean
        $data['ativo'] = $request->has('ativo') && $request->input('ativo') == '1' ? true : false;

        $post->update($data);

        return redirect()->route('dashboard.blog')
            ->with('success', 'Post atualizado com sucesso!');
    }

    /**
     * Deletar post
     */
    public function destroy(Post $post)
    {
        // Deletar imagem se existir
        if ($post->imagem_apresentacao && Storage::exists('public/posts/' . $post->imagem_apresentacao)) {
            Storage::delete('public/posts/' . $post->imagem_apresentacao);
        }

        $post->delete();

        return redirect()->route('dashboard.blog')
            ->with('success', 'Post deletado com sucesso!');
    }

    /**
     * Alternar status ativo/inativo
     */
    public function toggleStatus(Post $post)
    {
        $post->update(['ativo' => !$post->ativo]);

        $status = $post->ativo ? 'ativado' : 'desativado';
        
        return redirect()->route('dashboard.blog')
            ->with('success', "Post {$status} com sucesso!");
    }

    /**
     * Listagem pública de posts (página do blog)
     */
    public function publicIndex()
    {
        // Verificar se existe um tema ativo que tenha a página blogs
        $temaAtivo = \DB::table('temas')
            ->where('ativo', 1)
            ->where('nome', '!=', 'Lumialto') // Excluir tema padrão
            ->first();
        
        if ($temaAtivo) {
            // Verificar se o tema tem a página blogs
            $temaViewsPath = resource_path('views/temas/' . $temaAtivo->nome);
            $arquivoBlogs = $temaViewsPath . '/blogs.blade.php';
            
            if (file_exists($arquivoBlogs)) {
                // Usar o TemasController para renderizar a página blogs do tema
                $controller = new \App\Http\Controllers\TemasController();
                return $controller->renderizarPaginaDinamica($temaAtivo->nome, 'blogs');
            }
        }
        
        // Fallback para a view padrão do main-Thema
        $posts = Post::ativo()
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('main-Thema.blog.index', compact('posts'));
    }

    /**
     * Exibir post individual (página pública)
     */
    public function publicShow(Post $post)
    {
        // Verificar se o post está ativo
        if (!$post->isAtivo()) {
            abort(404);
        }

        // Posts relacionados (últimos 3 posts ativos, excluindo o atual)
        $relatedPosts = Post::ativo()
            ->where('id', '!=', $post->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('main-Thema.blog.show', compact('post', 'relatedPosts'));
    }
}
