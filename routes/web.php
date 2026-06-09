<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SobreController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\TemasController;
use App\Http\Controllers\ThemePageController;
use App\Http\Controllers\FloatingButtonController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadManagementController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\AmpController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota para servir favicons
Route::get('/favicon/{filename}', function ($filename) {
    $path = storage_path('app/uploads/favicons/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    // Detectar tipo MIME baseado na extensão
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $contentTypeMap = [
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'ico' => 'image/x-icon'
    ];
    $contentType = $contentTypeMap[$extension] ?? 'image/webp';
    
    return response()->file($path, [
        'Content-Type' => $contentType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->name('favicon');

// Rota para servir logos (com otimização automática)
Route::get('/logo/{filename}', function ($filename) {
    $path = storage_path('app/uploads/logos/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    // Tentar servir versão otimizada se existir
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $optimizedPath = storage_path('app/uploads/logos/optimized/' . $filename);
    
    // Se é WebP e não existe versão otimizada, criar uma
    if (strtolower($extension) === 'webp' && !file_exists($optimizedPath)) {
        // Criar diretório se não existir
        $optimizedDir = dirname($optimizedPath);
        if (!is_dir($optimizedDir)) {
            mkdir($optimizedDir, 0755, true);
        }
        
        // Otimizar com qualidade 75 (mais compactação)
        if (\App\Helpers\ImageHelper::optimizeWebP($path, $optimizedPath, 75)) {
            $path = $optimizedPath;
        }
    } elseif (file_exists($optimizedPath)) {
        // Usar versão otimizada se existir
        $path = $optimizedPath;
    }
    
    return response()->file($path, [
        'Content-Type' => 'image/' . $extension,
        'Cache-Control' => 'public, max-age=31536000, immutable',
    ]);
})->name('logo');

// Rota para servir favicon.ico padrão
Route::get('/favicon.ico', function () {
    // Tentar usar o favicon padrão do sistema (SVG)
    $faviconPath = storage_path('app/uploads/favicons/favicon-main.svg');
    
    if (file_exists($faviconPath)) {
        // Cache de 1 ano (31536000 segundos)
        $maxAge = 31536000;
        $expires = gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT';
        
        return response()->file($faviconPath, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=' . $maxAge . ', immutable',
            'Expires' => $expires,
        ]);
    }
    
    // Fallback para favicon do tema Lumialto
    $faviconPath = public_path('temas/Lumialto/assets/images/favicon.png');
    
    if (file_exists($faviconPath)) {
        // Cache de 1 ano (31536000 segundos)
        $maxAge = 31536000;
        $expires = gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT';
        
        return response()->file($faviconPath, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=' . $maxAge . ', immutable',
            'Expires' => $expires,
        ]);
    }
    
    abort(404);
})->name('favicon.ico');

// Rota para servir robots.txt da raiz do projeto
Route::get('/robots.txt', function () {
    // Primeiro tentar na raiz do projeto
    $robotsPath = base_path('robots.txt');
    
    if (file_exists($robotsPath)) {
        return response()->file($robotsPath, [
            'Content-Type' => 'text/plain',
        ]);
    }
    
    // Fallback para public/robots.txt se existir
    $robotsPath = public_path('robots.txt');
    
    if (file_exists($robotsPath)) {
        return response()->file($robotsPath, [
            'Content-Type' => 'text/plain',
        ]);
    }
    
    abort(404);
})->name('robots.txt');

// Rota para servir assets CSS
Route::get('/css/{filename}', function ($filename) {
    $path = public_path('css/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    $contentType = 'text/css';
    if (pathinfo($filename, PATHINFO_EXTENSION) === 'css') {
        $contentType = 'text/css';
    }
    
    // Cache de 1 ano (31536000 segundos)
    $maxAge = 31536000;
    $expires = gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT';
    
    return response()->file($path, [
        'Content-Type' => $contentType,
        'Cache-Control' => 'public, max-age=' . $maxAge . ', immutable',
        'Expires' => $expires,
    ]);
})->name('css.asset');

// Rota para servir assets JS
Route::get('/js/{filename}', function ($filename) {
    $path = public_path('js/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    // Cache de 1 ano (31536000 segundos)
    $maxAge = 31536000;
    $expires = gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT';
    
    return response()->file($path, [
        'Content-Type' => 'application/javascript',
        'Cache-Control' => 'public, max-age=' . $maxAge . ', immutable',
        'Expires' => $expires,
    ]);
})->name('js.asset');

// Rota para servir assets de temas
Route::get('/temas/{tema}/assets/{type}/{filename}', function ($tema, $type, $filename) {
    $path = public_path("temas/{$tema}/assets/{$type}/{$filename}");
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    // Determinar Content-Type baseado na extensão
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $contentType = match($extension) {
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'ico' => 'image/x-icon',
        default => 'application/octet-stream'
    };
    
    // Cache de 1 ano (31536000 segundos)
    $maxAge = 31536000;
    $expires = gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT';
    
    return response()->file($path, [
        'Content-Type' => $contentType,
        'Cache-Control' => 'public, max-age=' . $maxAge . ', immutable',
        'Expires' => $expires,
    ]);
})->name('theme.asset');

// Rotas AMP (devem vir antes das rotas regulares)
Route::prefix('amp')->group(function () {
    Route::get('/', [AmpController::class, 'render'])->name('amp.home');
    Route::get('/{path}', [AmpController::class, 'render'])->where('path', '.*')->name('amp.page');
});

// Rota para validação AMP (admin)
Route::post('/amp/validate', [AmpController::class, 'validateAmp'])->name('amp.validate')->middleware('auth');

// Redirecionar /home para / (evitar conteúdo duplicado)
Route::get('/home', function () {
    return redirect('/', 301);
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sobre', [SobreController::class, 'index'])->name('sobre');
Route::get('/contato', [ContatoController::class, 'index'])->name('contato');
Route::post('/contato', [ContatoController::class, 'enviar'])->name('contato.enviar');

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas protegidas (requerem login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin', [DashboardController::class, 'admin'])->name('admin')->middleware('can:admin');
    
    // Rotas do Head (apenas para admins)
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/head', [HeadController::class, 'index'])->name('head');
        Route::put('/head', [HeadController::class, 'update'])->name('head.update');
        Route::get('/head/images', [HeadController::class, 'getImages'])->name('head.images');
        
        // Rotas da Navbar
        Route::get('/navbar', [NavbarController::class, 'index'])->name('navbar');
        Route::put('/navbar', [NavbarController::class, 'update'])->name('navbar.update');
        Route::get('/navbar/images', [NavbarController::class, 'getImages'])->name('navbar.images');
        
        // Rotas dos Temas
        Route::get('/temas', [TemasController::class, 'index'])->name('temas');
        Route::post('/temas', [TemasController::class, 'store'])->name('temas.store');
        Route::get('/temas/{nomeTema}/preview', [TemasController::class, 'preview'])->name('temas.preview');
        Route::get('/temas/{nomeTema}/preview/{pagina}', [TemasController::class, 'previewPage'])->name('temas.preview.page');
        Route::post('/temas/{nomeTema}/select', [TemasController::class, 'select'])->name('temas.select');
        Route::put('/temas/{nomeTema}/rename', [TemasController::class, 'rename'])->name('temas.rename');
        Route::post('/temas/{nomeTema}/duplicate', [TemasController::class, 'duplicate'])->name('temas.duplicate');
        Route::post('/temas/{nomeTema}/corrigir-rotas', [TemasController::class, 'corrigirRotasTema'])->name('temas.corrigir-rotas');
        Route::post('/temas/{nomeTema}/generate-sitemap', [TemasController::class, 'generateSitemap'])->name('temas.generate-sitemap');
        Route::post('/temas/{nomeTema}/generate-llms', [TemasController::class, 'generateLlms'])->name('temas.generate-llms');
        Route::delete('/temas/{nomeTema}', [TemasController::class, 'destroy'])->name('temas.destroy');
        
        // Rotas das Páginas dos Temas
        Route::get('/theme-pages', [ThemePageController::class, 'index'])->name('theme-pages');
        Route::get('/theme-pages/{pagina}', [ThemePageController::class, 'show'])->name('theme-pages.show');
        Route::put('/theme-pages/{pagina}', [ThemePageController::class, 'update'])->name('theme-pages.update');
        Route::put('/theme-pages/{pagina}/rename', [ThemePageController::class, 'rename'])->name('theme-pages.rename');
        Route::post('/theme-pages/{pagina}/duplicate', [ThemePageController::class, 'duplicate'])->name('theme-pages.duplicate');
        Route::delete('/theme-pages/{pagina}', [ThemePageController::class, 'destroy'])->name('theme-pages.destroy');
        
        // Rotas dos Formulários de Conteúdo
        Route::post('/theme-pages/{pagina}/content-form', [ThemePageController::class, 'createContentForm'])->name('theme-pages.content-form.create');
        Route::post('/theme-pages/{pagina}/content-form/allocate-classes', [ThemePageController::class, 'allocateClasses'])->name('theme-pages.content-form.allocate-classes');
        Route::post('/theme-pages/{pagina}/reprocessar-con', [ThemePageController::class, 'reprocessarElementosCon'])->name('theme-pages.reprocessar-con');
        Route::get('/theme-pages/{pagina}/content-form/{formularioId}/edit', [ThemePageController::class, 'editContentForm'])->name('theme-pages.content-form.edit');
        Route::put('/theme-pages/{pagina}/content-form/{formularioId}', [ThemePageController::class, 'updateContentForm'])->name('theme-pages.content-form.update');
        Route::delete('/theme-pages/{pagina}/content-form/{formularioId}', [ThemePageController::class, 'destroyContentForm'])->name('theme-pages.content-form.destroy');
        
        
        // Rotas de edição de páginas dos temas
        Route::get('/temas/home/edit', [TemasController::class, 'editHome'])->name('temas.home.edit');
        Route::get('/temas/about/edit', [TemasController::class, 'editAbout'])->name('temas.about.edit');
        Route::get('/temas/contact/edit', [TemasController::class, 'editContact'])->name('temas.contact.edit');
        
        // Rota de serviços (placeholder)
        Route::get('/servico', function() {
            return redirect()->route('dashboard.temas')->with('info', 'Página de serviços em desenvolvimento.');
        })->name('servico.index');
        
        // Rotas dos Botões Flutuantes
        Route::get('/floating-buttons', [FloatingButtonController::class, 'index'])->name('floating-buttons');
        Route::put('/floating-buttons', [FloatingButtonController::class, 'update'])->name('floating-buttons.update');
        
        // Rotas do Blog
        Route::get('/blog', [BlogController::class, 'index'])->name('blog');
        Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
        Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
        Route::get('/blog/{post}/edit', [BlogController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{post}', [BlogController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{post}', [BlogController::class, 'destroy'])->name('blog.destroy');
        Route::patch('/blog/{post}/toggle-status', [BlogController::class, 'toggleStatus'])->name('blog.toggle-status');
        
        // Rotas dos Leads
        Route::get('/leads', [LeadManagementController::class, 'index'])->name('leads');
        Route::get('/leads/{lead}', [LeadManagementController::class, 'show'])->name('leads.show');
        Route::delete('/leads/{lead}', [LeadManagementController::class, 'destroy'])->name('leads.destroy');
        
        // Rotas do Robots.txt
        Route::get('/robots/status', [RobotsController::class, 'status'])->name('robots.status');
        Route::post('/robots/enable', [RobotsController::class, 'enable'])->name('robots.enable');
        Route::post('/robots/disable', [RobotsController::class, 'disable'])->name('robots.disable');
    });
});

// Rota pública para captura de leads (não precisa de autenticação)
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');

// Rotas públicas do blog
// IMPORTANTE: Esta rota deve vir ANTES da rota dinâmica /blog/{slug} para ter prioridade
Route::get('/blog', [BlogController::class, 'publicIndex'])->name('blog.public.index');

// Rota para detail-blogs com query string (compatibilidade com URLs antigas)
// Esta rota deve vir ANTES das rotas dinâmicas para ter prioridade
Route::get('/detail-blogs', function() {
    $slug = request()->get('slug');
    
    if (!$slug) {
        abort(404, 'Slug não fornecido');
    }
    
    // Normalizar o slug (remover caracteres inválidos e corrigir problemas comuns)
    $slugNormalizado = \App\Helpers\SlugHelper::normalizarSlug($slug);
    
    // Verificar se existe um tema ativo
    $temaAtivo = \DB::table('temas')
        ->where('ativo', 1)
        ->where('nome', '!=', 'Lumialto')
        ->first();
    
    if ($temaAtivo) {
        // Verificar se existe rota dinâmica com {slug}
        $rotaComSlug = \DB::table('rotas_dinamicas')
            ->where('tema', $temaAtivo->nome)
            ->where('ativo', 1)
            ->where('rota', 'like', '%{slug}%')
            ->where(function($q) {
                $q->where('pagina', 'detail-blogs')
                  ->orWhere('pagina', 'detail_blogs'); // Compatibilidade
            })
            ->first();
        
        if ($rotaComSlug) {
            // Tentar encontrar o post com o slug normalizado
            $post = \App\Helpers\SlugHelper::buscarPostPorSlug($slugNormalizado, $slug);
            
            if ($post) {
                // Se encontrou o post, redirecionar para a URL correta (301 permanente)
                // Buscar a rota correta do banco
                $rotaAtual = $rotaComSlug->rota ?? '/detail-blogs/{slug}';
                $urlBase = str_replace('/{slug}', '', $rotaAtual);
                $urlCorreta = url($urlBase . '/' . $post->slug);
                return redirect($urlCorreta, 301);
            }
        }
    }
    
    // Se não encontrou, tentar buscar diretamente
    $post = \App\Helpers\SlugHelper::buscarPostPorSlug($slugNormalizado, $slug);
    
    if ($post) {
        // Redirecionar para a URL correta
        // Buscar a rota correta do banco
        $rotaAtual = '/detail-blogs/{slug}';
        $urlBase = str_replace('/{slug}', '', $rotaAtual);
        $urlCorreta = url($urlBase . '/' . $post->slug);
        return redirect($urlCorreta, 301);
    }
    
    abort(404, 'Post não encontrado');
})->name('detail-blogs.query');

// Rota para página single do post - verificar se deve usar tema dinâmico ou padrão
// Esta rota só será usada quando houver um slug válido (não conflita com /blog)
Route::get('/blog/{post:slug}', function(\App\Models\Post $post) {
    // Verificar se existe um tema ativo
    $temaAtivo = \DB::table('temas')
        ->where('ativo', 1)
        ->where('nome', '!=', 'Lumialto') // Excluir tema padrão
        ->first();
    
    if ($temaAtivo) {
        // Primeiro, verificar se existe rota dinâmica com {slug}
        $rotaComSlug = \DB::table('rotas_dinamicas')
            ->where('tema', $temaAtivo->nome)
            ->where('ativo', 1)
            ->where('rota', 'like', '%{slug}%')
            ->first();
        
        if ($rotaComSlug) {
            // Usar o TemasController para renderizar a página dinâmica
            $controller = new \App\Http\Controllers\TemasController();
            return $controller->renderizarPaginaDinamica($temaAtivo->nome, $rotaComSlug->pagina, $post->slug);
        }
        
        // Se não encontrou rota dinâmica, verificar se o tema tem a página blog.blade.php
        $temaViewsPath = resource_path('views/temas/' . $temaAtivo->nome);
        $arquivoBlog = $temaViewsPath . '/blog.blade.php';
        
        if (file_exists($arquivoBlog)) {
            // Usar o TemasController para renderizar a página blog do tema
            $controller = new \App\Http\Controllers\TemasController();
            return $controller->renderizarPaginaDinamica($temaAtivo->nome, 'blog', $post->slug);
        }
    }
    
    // Fallback para a rota padrão do blog (main-Thema)
    $blogController = new \App\Http\Controllers\BlogController();
    return $blogController->publicShow($post);
})->name('blog.public.show');

// Rota para servir imagens do blog
Route::get('/storage/posts/{filename}', function($filename) {
    $path = storage_path('app/public/posts/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    return response('Imagem não encontrada', 404);
})->name('blog.image');

// Rota alternativa para servir arquivos de storage quando o link simbólico não funciona
// Esta rota serve arquivos de storage/app/public diretamente
Route::get('/storage/{path}', function($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    // Verificar se o arquivo existe
    if (!file_exists($fullPath)) {
        abort(404, 'Arquivo não encontrado');
    }
    
    // Verificar se é um arquivo (não diretório)
    if (!is_file($fullPath)) {
        abort(404, 'Não é um arquivo');
    }
    
    // Determinar Content-Type baseado na extensão
    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $contentTypeMap = [
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'pdf' => 'application/pdf',
        'css' => 'text/css',
        'js' => 'application/javascript',
    ];
    $contentType = $contentTypeMap[$extension] ?? 'application/octet-stream';
    
    // Retornar o arquivo com headers apropriados
    return response()->file($fullPath, [
        'Content-Type' => $contentType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.file');

// Rota pública para gerar sitemap (para teste)
Route::get('/generate-sitemap/{nomeTema}', [TemasController::class, 'generateSitemapPublic'])->name('generate-sitemap-public');

// Rota para servir o sitemap.xml
Route::get('/sitemap.xml', function() {
    $sitemapPath = base_path('sitemap.xml');
    if (file_exists($sitemapPath)) {
        return response()->file($sitemapPath, [
            'Content-Type' => 'application/xml'
        ]);
    }
    return response('Sitemap não encontrado', 404);
})->name('sitemap');

// Rota de diagnóstico para uploaded_assets() (remover em produção)
Route::get('/debug/uploaded-assets', function() {
    return view('debug-uploaded-assets');
})->name('debug.uploaded-assets');
