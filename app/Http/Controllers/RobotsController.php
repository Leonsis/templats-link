<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Helpers\ThemeHelper;

class RobotsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Verificar status do robots.txt
     */
    public function status()
    {
        // Verificar se o arquivo robots.txt existe na raiz do projeto
        $robotsPath = base_path('robots.txt');
        $robotsExists = File::exists($robotsPath);
        
        return response()->json([
            'robots_exists' => $robotsExists,
            'has_index_meta' => false, // Não verifica mais meta tags do banco
            'tema_ativo' => ThemeHelper::getActiveTheme()
        ]);
    }

    /**
     * Criar robots.txt na raiz do projeto
     */
    public function enable()
    {
        try {
            // Criar robots.txt na raiz do projeto
            $this->createRobotsFile();
            
            return response()->json([
                'success' => true,
                'message' => 'Arquivo robots.txt criado com sucesso na raiz do projeto!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao habilitar indexação: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remover robots.txt
     */
    public function disable()
    {
        try {
            // Remover robots.txt
            $this->removeRobotsFile();
            
            return response()->json([
                'success' => true,
                'message' => 'Arquivo robots.txt removido com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao desabilitar indexação: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar arquivo robots.txt na raiz do projeto
     */
    private function createRobotsFile()
    {
        // Criar na raiz do projeto
        $robotsPath = base_path('robots.txt');
        
        // Obter a URL do site para o sitemap
        $appUrl = config('app.url', url('/'));
        $sitemapUrl = rtrim($appUrl, '/') . '/sitemap.xml';
        
        // Conteúdo do robots.txt conforme especificado pelo usuário
        $robotsContent = "User-agent: *\n";
        $robotsContent .= "Allow: /\n";
        $robotsContent .= "Sitemap: {$sitemapUrl}\n";
        
        File::put($robotsPath, $robotsContent);
    }

    /**
     * Remover arquivo robots.txt
     */
    private function removeRobotsFile()
    {
        $robotsPath = base_path('robots.txt');
        if (File::exists($robotsPath)) {
            File::delete($robotsPath);
        }
    }
}
