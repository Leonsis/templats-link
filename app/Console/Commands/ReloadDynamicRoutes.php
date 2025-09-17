<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ReloadDynamicRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:reload-dynamic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recarregar rotas dinâmicas dos temas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $rotasDinamicas = \DB::table('rotas_dinamicas')
                ->where('ativo', 1)
                ->orderBy('tema')
                ->orderBy('pagina')
                ->get();

            if ($rotasDinamicas->isEmpty()) {
                $this->info('Nenhuma rota dinâmica encontrada no banco de dados.');
                return;
            }

            $totalRotas = 0;
            $temaAtual = '';

            foreach ($rotasDinamicas as $rotaDinamica) {
                if ($temaAtual !== $rotaDinamica->tema) {
                    $temaAtual = $rotaDinamica->tema;
                    $this->info("Tema: {$temaAtual}");
                }
                
                $this->line("  - {$rotaDinamica->rota} → {$rotaDinamica->pagina}.blade.php");
                $totalRotas++;
            }

            $this->info("Total de rotas dinâmicas: {$totalRotas}");
            $this->info('Rotas dinâmicas carregadas com sucesso!');
            
        } catch (\Exception $e) {
            $this->error("Erro ao carregar rotas dinâmicas: " . $e->getMessage());
        }
    }
}
