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
    
    return response()->file($path, [
        'Content-Type' => 'image/webp',
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->name('favicon');

// Rota para servir logos
Route::get('/logo/{filename}', function ($filename) {
    $path = storage_path('app/uploads/logos/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path, [
        'Content-Type' => 'image/' . pathinfo($filename, PATHINFO_EXTENSION),
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->name('logo');

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
        Route::delete('/temas/{nomeTema}', [TemasController::class, 'destroy'])->name('temas.destroy');
        
        // Rotas das Páginas dos Temas
        Route::get('/theme-pages', [ThemePageController::class, 'index'])->name('theme-pages');
        Route::get('/theme-pages/{pagina}', [ThemePageController::class, 'show'])->name('theme-pages.show');
        Route::put('/theme-pages/{pagina}', [ThemePageController::class, 'update'])->name('theme-pages.update');
        
        
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
    });
});
