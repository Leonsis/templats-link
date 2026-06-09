<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LinkReplacerController;

class ReplaceHtmlLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'links:replace-html {tema? : Nome do tema (opcional, se não informado processa todos)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Substituir links .html pelas rotas corretas do Laravel nos arquivos do tema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tema = $this->argument('tema');
        $controller = new LinkReplacerController();

        $this->info('🔄 Iniciando substituição de links HTML...');
        $this->newLine();

        try {
            if ($tema) {
                $this->info("📁 Processando tema: {$tema}");
                $resultado = $controller->replaceHtmlLinks($tema);
                $this->processarResultado($resultado->getData());
            } else {
                $this->info('📁 Processando todos os temas...');
                $resultado = $controller->replaceAllThemes();
                $this->processarResultadoTodos($resultado->getData());
            }

        } catch (\Exception $e) {
            $this->error("❌ Erro: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Processar resultado para um tema específico
     */
    private function processarResultado($resultado)
    {
        if (isset($resultado->error)) {
            $this->error("❌ Erro: " . $resultado->error);
            return;
        }

        $this->info("✅ Tema: {$resultado->tema}");
        $this->info("📄 Arquivos processados: {$resultado->arquivos_processados}");
        $this->info("🔗 Links substituídos: {$resultado->links_substituidos}");
        
        if (!empty($resultado->detalhes)) {
            $this->newLine();
            $this->info("📋 Detalhes por arquivo:");
            
            foreach ($resultado->detalhes as $detalhe) {
                $this->line("   • {$detalhe->arquivo}: {$detalhe->links_substituidos} links");
            }
        }

        $this->newLine();
        $this->info("🎉 Substituição concluída com sucesso!");
    }

    /**
     * Processar resultado para todos os temas
     */
    private function processarResultadoTodos($resultado)
    {
        if (isset($resultado->error)) {
            $this->error("❌ Erro: " . $resultado->error);
            return;
        }

        $this->info("📊 Total de temas processados: {$resultado->total_temas}");
        $this->newLine();

        $totalArquivos = 0;
        $totalLinks = 0;

        foreach ($resultado->temas as $tema) {
            if (isset($tema->error)) {
                $this->error("❌ Erro no tema {$tema->tema}: " . $tema->error);
                continue;
            }

            $this->info("✅ Tema: {$tema->tema}");
            $this->line("   📄 Arquivos: {$tema->arquivos_processados}");
            $this->line("   🔗 Links: {$tema->links_substituidos}");
            
            $totalArquivos += $tema->arquivos_processados;
            $totalLinks += $tema->links_substituidos;
        }

        $this->newLine();
        $this->info("📊 RESUMO GERAL:");
        $this->info("   📄 Total de arquivos processados: {$totalArquivos}");
        $this->info("   🔗 Total de links substituídos: {$totalLinks}");
        $this->newLine();
        $this->info("🎉 Substituição concluída com sucesso!");
    }
}
