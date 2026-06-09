<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailHelper
{
    /**
     * Enviar email de notificação de lead usando Laravel Mail com template simples
     */
    public static function enviarNotificacaoLead($dados, $emailDestino)
    {
        if (empty($emailDestino)) {
            Log::info('Email de destino não configurado para notificação de lead');
            return false;
        }

        try {
            // Preparar dados do email
            $nome = $dados['nome'] ?? '';
            $email = $dados['email'] ?? '';
            $telefone = $dados['telefone'] ?? '';
            $origem = $dados['origem'] ?? '';
            
            Log::info("Enviando email de notificação para: {$emailDestino}");
            Log::info("Dados do lead: " . json_encode($dados));
            
            // Usar Laravel Mail com template simples
            Mail::send([], [], function ($message) use ($dados, $emailDestino) {
                $nome = $dados['nome'] ?? '';
                $email = $dados['email'] ?? '';
                $telefone = $dados['telefone'] ?? '';
                $origem = $dados['origem'] ?? '';
                $faturamento = $dados['faturamento'] ?? '';
                $area_atuacao = $dados['area_atuacao'] ?? '';
                
                // Construir HTML do email
                $html = "<h2>Novo Lead</h2>";
                $html .= "<p><b>Nome:</b> {$nome}</p>";
                $html .= "<p><b>Telefone:</b> {$telefone}</p>";
                $html .= "<p><b>Email:</b> {$email}</p>";
                
                // Só adicionar faturamento se tiver valor
                if (!empty($faturamento)) {
                    $html .= "<p><b>Faturamento:</b> {$faturamento}</p>";
                }
                
                // Só adicionar área de atuação se tiver valor
                if (!empty($area_atuacao)) {
                    $html .= "<p><b>Área de Atuação:</b> {$area_atuacao}</p>";
                }
                
                $html .= "<p><b>Origem:</b> {$origem}</p>";
                $html .= "<p><b>Data:</b> " . now()->format('d/m/Y H:i:s') . "</p>";
                
                $message->to($emailDestino)
                    ->subject('Novo lead recebido')
                    ->html($html);
            });
            
            Log::info("Email enviado com sucesso para: {$emailDestino}");
            return true;
            
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de notificação: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }
    
}
