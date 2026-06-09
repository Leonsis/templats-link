<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDetailBlogsRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:detail-blogs-route {tema?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Garantir que a rota detail_blogs/{slug} esteja criada para um tema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $temaNome = $this->argument('tema');
        
        // Se não foi fornecido, usar o tema ativo
        if (!$temaNome) {
            $temaAtivo = DB::table('temas')
                ->where('ativo', 1)
                ->where('nome', '!=', 'Lumialto')
                ->first();
            
            if (!$temaAtivo) {
                $this->error('❌ Nenhum tema ativo encontrado');
                return 1;
            }
            
            $temaNome = $temaAtivo->nome;
        }
        
        $this->info("🔍 Verificando rota detail_blogs para o tema: {$temaNome}");
        
        // Verificar se a rota já existe
        $rotaExistente = DB::table('rotas_dinamicas')
            ->where('tema', $temaNome)
            ->where('pagina', 'detail_blogs')
            ->first();
        
        if ($rotaExistente) {
            // Verificar se está ativa
            if ($rotaExistente->ativo) {
                $this->info("✅ Rota já existe e está ativa: {$rotaExistente->rota}");
                
                // Verificar se a rota está correta
                if ($rotaExistente->rota !== '/detail_blogs/{slug}') {
                    $this->warn("⚠️  Rota existe mas está incorreta: {$rotaExistente->rota}");
                    $this->info("🔄 Atualizando rota...");
                    
                    DB::table('rotas_dinamicas')
                        ->where('id', $rotaExistente->id)
                        ->update([
                            'rota' => '/detail_blogs/{slug}',
                            'updated_at' => now()
                        ]);
                    
                    $this->info("✅ Rota atualizada com sucesso!");
                }
            } else {
                $this->warn("⚠️  Rota existe mas está inativa");
                $this->info("🔄 Ativando rota...");
                
                DB::table('rotas_dinamicas')
                    ->where('id', $rotaExistente->id)
                    ->update([
                        'ativo' => 1,
                        'rota' => '/detail_blogs/{slug}',
                        'updated_at' => now()
                    ]);
                
                $this->info("✅ Rota ativada com sucesso!");
            }
        } else {
            // Criar a rota
            $this->info("📝 Criando rota detail_blogs/{slug}...");
            
            DB::table('rotas_dinamicas')->insert([
                'tema' => $temaNome,
                'pagina' => 'detail_blogs',
                'rota' => '/detail_blogs/{slug}',
                'nome_rota' => 'detail_blogs',
                'controller' => 'TemasController',
                'metodo' => 'renderizarPaginaDinamica',
                'ativo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $this->info("✅ Rota criada com sucesso!");
        }
        
        // Limpar cache de rotas
        $this->call('route:clear');
        $this->info("✅ Cache de rotas limpo");
        
        $this->info("🎉 Processo concluído! Execute 'php artisan route:list' para verificar.");
        
        return 0;
    }
}

