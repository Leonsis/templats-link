<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GerenciarMetaTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meta:gerenciar {tema} {--pagina=} {--title=} {--description=} {--keywords=} {--listar} {--criar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gerencia meta tags dinamicamente para temas. Use --listar para ver configurações, --criar para criar novas.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tema = $this->argument('tema');
        
        if ($this->option('listar')) {
            return $this->listarConfiguracoes($tema);
        }
        
        if ($this->option('criar')) {
            return $this->criarConfiguracao($tema);
        }
        
        if ($this->option('pagina')) {
            return $this->atualizarConfiguracao($tema);
        }
        
        $this->error('Use --listar, --criar ou especifique --pagina com --title, --description, --keywords');
        return 1;
    }
    
    private function listarConfiguracoes($tema)
    {
        $this->info("=== Configurações de Meta Tags para o tema: {$tema} ===");
        
        $configuracoes = DB::table('head_configs')
            ->where('tema', $tema)
            ->orderBy('pagina')
            ->get();
            
        if ($configuracoes->isEmpty()) {
            $this->warn("Nenhuma configuração encontrada para o tema: {$tema}");
            return 0;
        }
        
        $headers = ['Página', 'Meta Title', 'Meta Description', 'Meta Keywords'];
        $rows = [];
        
        foreach ($configuracoes as $config) {
            $rows[] = [
                $config->pagina,
                $config->meta_title ?: 'Não definido',
                $config->meta_description ?: 'Não definido',
                $config->meta_keywords ?: 'Não definido'
            ];
        }
        
        $this->table($headers, $rows);
        return 0;
    }
    
    private function criarConfiguracao($tema)
    {
        $this->info("=== Criando configuração para o tema: {$tema} ===");
        
        $pagina = $this->ask('Nome da página (ex: home, contato, sobre)');
        $title = $this->ask('Meta Title (máximo 60 caracteres)');
        $description = $this->ask('Meta Description (máximo 160 caracteres)');
        $keywords = $this->ask('Meta Keywords (separadas por vírgula)');
        
        // Validar se já existe
        $existente = DB::table('head_configs')
            ->where('tema', $tema)
            ->where('pagina', $pagina)
            ->first();
            
        if ($existente) {
            $this->error("Configuração já existe para a página '{$pagina}' no tema '{$tema}'");
            return 1;
        }
        
        // Criar configuração
        DB::table('head_configs')->insert([
            'pagina' => $pagina,
            'tema' => $tema,
            'meta_title' => $title,
            'meta_description' => $description,
            'meta_keywords' => $keywords,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->info("✓ Configuração criada com sucesso para a página '{$pagina}'");
        return 0;
    }
    
    private function atualizarConfiguracao($tema)
    {
        $pagina = $this->option('pagina');
        $title = $this->option('title');
        $description = $this->option('description');
        $keywords = $this->option('keywords');
        
        if (!$title && !$description && !$keywords) {
            $this->error('Especifique pelo menos um campo para atualizar (--title, --description, --keywords)');
            return 1;
        }
        
        // Verificar se existe
        $existente = DB::table('head_configs')
            ->where('tema', $tema)
            ->where('pagina', $pagina)
            ->first();
            
        if (!$existente) {
            $this->error("Configuração não encontrada para a página '{$pagina}' no tema '{$tema}'");
            return 1;
        }
        
        // Preparar dados para atualização
        $updateData = ['updated_at' => now()];
        
        if ($title) $updateData['meta_title'] = $title;
        if ($description) $updateData['meta_description'] = $description;
        if ($keywords) $updateData['meta_keywords'] = $keywords;
        
        // Atualizar
        DB::table('head_configs')
            ->where('tema', $tema)
            ->where('pagina', $pagina)
            ->update($updateData);
            
        $this->info("✓ Configuração atualizada com sucesso para a página '{$pagina}'");
        return 0;
    }
}
