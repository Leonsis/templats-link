<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Helpers\FloatingButtonHelper;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'test@example.com';
        
        $this->info('Testando envio de email...');
        $this->info('Email configurado no sistema: ' . FloatingButtonHelper::getEmailFormulario());
        
        try {
            // Forçar uso do driver log
            config(['mail.default' => 'log']);
            
            Mail::send([], [], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Teste de Email - Templats Link')
                    ->html("
                        <h2>Teste de Email</h2>
                        <p>Este é um email de teste enviado pelo sistema Templats Link.</p>
                        <p><strong>Data:</strong> " . now()->format('d/m/Y H:i:s') . "</p>
                    ");
            });
            
            $this->info('Email enviado com sucesso para: ' . $email);
            $this->info('Verifique o arquivo storage/logs/laravel.log para ver o email');
        } catch (\Exception $e) {
            $this->error('Erro ao enviar email: ' . $e->getMessage());
        }
    }
}
