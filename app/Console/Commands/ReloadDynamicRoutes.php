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
        $rotasPath = base_path('routes/temas_dinamicas.php');
        
        if (!File::exists($rotasPath)) {
            $this->info('Nenhum arquivo de rotas dinâmicas encontrado.');
            return;
        }

        $rotasDinamicas = include $rotasPath;
        $totalRotas = 0;

        foreach ($rotasDinamicas as $nomeTema => $rotas) {
            $this->info("Tema: {$nomeTema}");
            foreach ($rotas as $nomeRota => $config) {
                $this->line("  - {$config['rota']} → {$config['pagina']}.blade.php");
                $totalRotas++;
            }
        }

        $this->info("Total de rotas dinâmicas: {$totalRotas}");
        $this->info('Rotas dinâmicas recarregadas com sucesso!');
    }
}
