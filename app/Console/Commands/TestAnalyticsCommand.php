<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestAnalyticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o sistema de analytics do Templats-Link';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TESTE DO SISTEMA DE ANALYTICS ===');
        
        // 1. Verificar se os arquivos JavaScript existem
        $this->testJavaScriptFiles();
        
        // 2. Testar geolocalização
        $this->testGeolocation();
        
        // 3. Verificar estrutura de banco de dados
        $this->testDatabaseStructure();
        
        // 4. Verificar rotas
        $this->testRoutes();
        
        $this->info('=== FIM DO TESTE ===');
    }
    
    private function testJavaScriptFiles()
    {
        $this->info('📁 Verificando arquivos JavaScript...');
        
        $files = [
            'public/js/analytics.js',
            'public/js/test-analytics.js'
        ];
        
        foreach ($files as $file) {
            if (file_exists(base_path($file))) {
                $this->line("✅ {$file} - Encontrado");
            } else {
                $this->error("❌ {$file} - Não encontrado");
            }
        }
    }
    
    private function testGeolocation()
    {
        $this->info('🌍 Testando geolocalização...');
        
        try {
            $testIP = '8.8.8.8'; // Google DNS
            $response = Http::get("https://ipapi.co/{$testIP}/json/");
            $data = $response->json();
            
            if ($data && isset($data['country_name'])) {
                $this->line("✅ Geolocalização funcionando");
                $this->line("   País: {$data['country_name']}");
                $this->line("   Cidade: {$data['city']}");
            } else {
                $this->warn("⚠️ Geolocalização retornou dados inválidos");
            }
        } catch (\Exception $e) {
            $this->error("❌ Erro na geolocalização: " . $e->getMessage());
        }
    }
    
    private function testDatabaseStructure()
    {
        $this->info('🗄️ Verificando estrutura do banco de dados...');
        
        try {
            // Verificar se a tabela leads existe
            $leadsExists = \Schema::hasTable('leads');
            if ($leadsExists) {
                $this->line("✅ Tabela 'leads' existe");
                
                // Verificar colunas necessárias
                $columns = \Schema::getColumnListing('leads');
                $requiredColumns = ['nome', 'email', 'telefone', 'origem'];
                
                foreach ($requiredColumns as $column) {
                    if (in_array($column, $columns)) {
                        $this->line("   ✅ Coluna '{$column}' existe");
                    } else {
                        $this->error("   ❌ Coluna '{$column}' não encontrada");
                    }
                }
            } else {
                $this->error("❌ Tabela 'leads' não encontrada");
            }
        } catch (\Exception $e) {
            $this->error("❌ Erro ao verificar banco de dados: " . $e->getMessage());
        }
    }
    
    private function testRoutes()
    {
        $this->info('🛣️ Verificando rotas...');
        
        $routes = [
            'leads.store' => 'POST /leads',
            'contato' => 'GET /contato',
            'contato.enviar' => 'POST /contato'
        ];
        
        foreach ($routes as $name => $description) {
            try {
                $route = \Route::getRoutes()->getByName($name);
                if ($route) {
                    $this->line("✅ Rota '{$name}' - {$description}");
                } else {
                    $this->error("❌ Rota '{$name}' não encontrada");
                }
            } catch (\Exception $e) {
                $this->error("❌ Erro ao verificar rota '{$name}': " . $e->getMessage());
            }
        }
    }
}
