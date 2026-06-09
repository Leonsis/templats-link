<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\LeadController;
use App\Helpers\FloatingButtonHelper;
use App\Helpers\EmailHelper;

class TestFormSubmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:form-submission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test form submission and email sending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testando envio de formulário...');
        
        // Verificar email configurado
        $emailConfigurado = FloatingButtonHelper::getEmailFormulario();
        $this->info("📧 Email configurado: " . ($emailConfigurado ?: 'NENHUM'));
        
        if (empty($emailConfigurado)) {
            $this->error('❌ Nenhum email configurado no painel!');
            return;
        }
        
        // Simular dados do formulário
        $dadosTeste = [
            'nome' => 'João Silva',
            'email' => 'joao@teste.com',
            'telefone' => '11999999999'
        ];
        
        $this->info('📝 Dados do teste:');
        $this->table(['Campo', 'Valor'], [
            ['Nome', $dadosTeste['nome']],
            ['Email', $dadosTeste['email']],
            ['Telefone', $dadosTeste['telefone']],
            ['Email Destino', $emailConfigurado]
        ]);
        
        // Testar envio de email diretamente
        $this->info('📤 Testando envio de email...');
        $dadosLead = [
            'nome' => $dadosTeste['nome'],
            'email' => $dadosTeste['email'],
            'telefone' => $dadosTeste['telefone'],
            'origem' => 'Teste de Formulário'
        ];
        
        $resultado = EmailHelper::enviarNotificacaoLead($dadosLead, $emailConfigurado);
        
        if ($resultado) {
            $this->info('✅ Email enviado com sucesso!');
            $this->info('📋 Verifique o arquivo storage/logs/laravel.log para ver o email');
        } else {
            $this->error('❌ Erro ao enviar email!');
        }
        
        // Testar controller completo
        $this->info('🔄 Testando controller completo...');
        try {
            $request = new Request();
            $request->merge($dadosTeste);
            
            $controller = new LeadController();
            $response = $controller->store($request);
            
            $this->info('✅ Controller executado com sucesso!');
            $this->info('📋 Resposta: ' . $response->getContent());
            
        } catch (\Exception $e) {
            $this->error('❌ Erro no controller: ' . $e->getMessage());
        }
    }
}
