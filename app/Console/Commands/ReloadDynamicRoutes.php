<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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
        $this->info('🔄 Recarregando rotas dinâmicas...');

        try {
            // Limpar cache de rotas
            $this->call('route:clear');
            $this->info('✅ Cache de rotas limpo');

            // Carregar rotas dinâmicas do banco de dados
            $rotasDinamicas = DB::table('rotas_dinamicas')
                ->where('ativo', 1)
                ->get();

            $this->info("📊 Encontradas {$rotasDinamicas->count()} rotas dinâmicas");

            // Rotas principais que não devem ser sobrescritas
            $rotasPrincipais = ['/', '/sobre', '/contato', '/login', '/dashboard'];

            $rotasRegistradas = 0;
            $rotasIgnoradas = 0;

            foreach ($rotasDinamicas as $rotaDinamica) {
                // Verificar se a rota não conflita com rotas principais
                if (!in_array($rotaDinamica->rota, $rotasPrincipais)) {
                    // Registrar rota dinâmica seguindo o padrão Laravel
                    $rotaCompleta = $rotaDinamica->rota;

                    // Se a rota não começar com /, adicionar
                    if (!str_starts_with($rotaCompleta, '/')) {
                        $rotaCompleta = '/' . $rotaCompleta;
                    }

                    Route::get($rotaCompleta, [\App\Http\Controllers\TemasController::class, 'renderizarPaginaDinamica'])
                        ->defaults('tema', $rotaDinamica->tema)
                        ->defaults('pagina', $rotaDinamica->pagina)
                        ->name("tema.{$rotaDinamica->tema}.{$rotaDinamica->nome_rota}");

                    $this->line("   ✅ {$rotaCompleta} → {$rotaDinamica->tema}.{$rotaDinamica->pagina}");
                    $rotasRegistradas++;
                } else {
                    $this->line("   ⚠️  {$rotaDinamica->rota} (ignorada - conflito com rota principal)");
                    $rotasIgnoradas++;
                }
            }

            $this->info("🎉 Rotas recarregadas com sucesso!");
            $this->info("   ✅ {$rotasRegistradas} rotas registradas");
            $this->info("   ⚠️  {$rotasIgnoradas} rotas ignoradas");

        } catch (\Exception $e) {
            $this->error("❌ Erro ao recarregar rotas dinâmicas: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}