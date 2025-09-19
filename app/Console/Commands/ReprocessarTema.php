<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\TemasController;

class ReprocessarTema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tema:reprocessar {nome_tema}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reprocessar um tema existente para aplicar correções de formulários';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nomeTema = $this->argument('nome_tema');
        
        $this->info("Reprocessando tema: {$nomeTema}");
        
        // Verificar se o tema existe
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        if (!File::exists($temaViewsPath)) {
            $this->error("Tema não encontrado: {$nomeTema}");
            return 1;
        }
        
        // Criar instância do controller
        $temasController = new TemasController();
        
        // Usar reflexão para acessar métodos privados
        $reflection = new \ReflectionClass($temasController);
        
        // Buscar todos os arquivos .blade.php do tema
        $arquivos = File::glob($temaViewsPath . '/*.blade.php');
        
        $totalProcessados = 0;
        $totalFormulariosCriados = 0;
        
        foreach ($arquivos as $arquivo) {
            $nomeArquivo = basename($arquivo);
            
            // Pular arquivos de layout e includes
            if (str_contains($arquivo, '/inc/') || str_contains($arquivo, '/layouts/')) {
                continue;
            }
            
            $this->info("Processando: {$nomeArquivo}");
            
            // Ler conteúdo do arquivo
            $conteudoOriginal = File::get($arquivo);
            
            // Aplicar conversão de elementos para Blade
            $metodoConverter = $reflection->getMethod('converterElementosParaBlade');
            $metodoConverter->setAccessible(true);
            
            $conteudoNovo = $metodoConverter->invoke($temasController, $conteudoOriginal);
            
            // Verificar se houve mudanças
            if ($conteudoOriginal !== $conteudoNovo) {
                File::put($arquivo, $conteudoNovo);
                $totalProcessados++;
                
                // Verificar se foi criado um formulário
                if (strpos($conteudoNovo, '<form') !== false && strpos($conteudoOriginal, '<form') === false) {
                    $totalFormulariosCriados++;
                    $this->info("  ✓ Formulário criado automaticamente");
                }
            }
        }
        
        $this->info("\n=== Resumo ===");
        $this->info("Arquivos processados: {$totalProcessados}");
        $this->info("Formulários criados: {$totalFormulariosCriados}");
        
        // Linkar formulários ao tema
        $metodoLinkarFormularios = $reflection->getMethod('linkarFormulariosAoTema');
        $metodoLinkarFormularios->setAccessible(true);
        $metodoLinkarFormularios->invoke($temasController, $temaViewsPath, $nomeTema);
        
        // Criar configurações de meta tags se não existirem
        $arquivosBlade = File::allFiles($temaViewsPath);
        $metodoCriarMetaTags = $reflection->getMethod('criarConfiguracoesMetaTags');
        $metodoCriarMetaTags->setAccessible(true);
        $metodoCriarMetaTags->invoke($temasController, $nomeTema, $arquivosBlade);
        
        if ($totalFormulariosCriados > 0) {
            $this->info("\n✓ Tema reprocessado com sucesso! Formulários foram linkados automaticamente.");
        } else {
            $this->info("\nℹ Nenhum formulário foi criado. O tema pode já estar correto ou não ter inputs soltos.");
        }
        
        $this->info("✓ Configurações de meta tags criadas/atualizadas automaticamente.");
        
        return 0;
    }
}