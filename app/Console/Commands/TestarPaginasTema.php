<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ThemePageController;
use App\Helpers\ThemeHelper;
use Illuminate\Support\Facades\File;

class TestarPaginasTema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tema:testar-paginas {tema}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa se o sistema de Páginas do Tema está funcionando corretamente.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nomeTema = $this->argument('tema');
        $this->info("Testando sistema de Páginas do Tema para: {$nomeTema}");

        // Verificar se o tema existe
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        if (!File::exists($temaViewsPath)) {
            $this->error("Tema '{$nomeTema}' não encontrado em: {$temaViewsPath}");
            return Command::FAILURE;
        }

        // Testar detecção de páginas
        $this->info("\n=== Testando Detecção de Páginas ===");
        $controller = new ThemePageController();
        $reflection = new \ReflectionClass($controller);
        $metodo = $reflection->getMethod('obterPaginasDoTema');
        $metodo->setAccessible(true);
        
        $paginas = $metodo->invoke($controller, $nomeTema);
        
        if (empty($paginas)) {
            $this->error("Nenhuma página detectada no tema!");
            return Command::FAILURE;
        }

        $this->info("Páginas detectadas: " . count($paginas));
        foreach ($paginas as $pagina) {
            $this->line("  - {$pagina}");
        }

        // Testar configurações de meta tags
        $this->info("\n=== Testando Configurações de Meta Tags ===");
        $configuracoes = \DB::table('head_configs')
            ->where('tema', $nomeTema)
            ->get();

        if ($configuracoes->isEmpty()) {
            $this->warn("Nenhuma configuração de meta tags encontrada!");
            $this->info("Execute: php artisan tema:reprocessar {$nomeTema}");
            return Command::FAILURE;
        }

        $this->info("Configurações encontradas: " . $configuracoes->count());
        foreach ($configuracoes as $config) {
            $status = ($config->meta_title || $config->meta_description || $config->meta_keywords) ? '✓' : '○';
            $this->line("  {$status} {$config->pagina}");
        }

        // Testar HeadHelper
        $this->info("\n=== Testando HeadHelper ===");
        $headHelper = new \App\Helpers\HeadHelper();
        
        foreach ($paginas as $pagina) {
            $metaTitle = $headHelper::getMetaTitle($pagina, $nomeTema);
            $metaDescription = $headHelper::getMetaDescription($pagina, $nomeTema);
            $metaKeywords = $headHelper::getMetaKeywords($pagina, $nomeTema);
            
            $this->line("Página: {$pagina}");
            $this->line("  Title: " . ($metaTitle ?: 'Não definido'));
            $this->line("  Description: " . ($metaDescription ?: 'Não definido'));
            $this->line("  Keywords: " . ($metaKeywords ?: 'Não definido'));
            $this->line("");
        }

        $this->info("✓ Sistema de Páginas do Tema está funcionando corretamente!");
        $this->info("✓ Acesse: Dashboard → Páginas do Tema para editar as configurações");

        return Command::SUCCESS;
    }
}
