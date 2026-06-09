<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ThemeHelper;
use App\Helpers\FloatingButtonHelper;

class FloatingButtonController extends Controller
{
    /**
     * Exibir a página de configuração dos botões flutuantes
     */
    public function index()
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        // Buscar configurações dos botões flutuantes
        $configs = DB::table('head_configs')
            ->where('pagina', 'global')
            ->where('tema', $temaAtivo)
            ->first();
        
        // Se não existir configuração, criar uma padrão
        if (!$configs) {
            $configs = (object) [
                'telefone' => '',
                'whatsapp' => '',
                'email_formulario' => '',
                'tema' => $temaAtivo
            ];
        }
        
        return view('dashboard.floating-buttons.index', compact('configs', 'temaAtivo'));
    }
    
    /**
     * Atualizar as configurações dos botões flutuantes
     */
    public function update(Request $request)
    {
        $temaAtivo = ThemeHelper::getActiveTheme();
        
        $request->validate([
            'telefone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email_formulario' => 'nullable|email|max:255',
        ]);
        
        try {
            // Verificar se já existe configuração global para o tema
            $configExistente = DB::table('head_configs')
                ->where('pagina', 'global')
                ->where('tema', $temaAtivo)
                ->first();
            
            if ($configExistente) {
                // Atualizar configuração existente
                DB::table('head_configs')
                    ->where('id', $configExistente->id)
                    ->update([
                        'telefone' => $request->input('telefone'),
                        'whatsapp' => $request->input('whatsapp'),
                        'email_formulario' => $request->input('email_formulario'),
                        'updated_at' => now(),
                    ]);
            } else {
                // Criar nova configuração global
                DB::table('head_configs')->insert([
                    'pagina' => 'global',
                    'tema' => $temaAtivo,
                    'telefone' => $request->input('telefone'),
                    'whatsapp' => $request->input('whatsapp'),
                    'email_formulario' => $request->input('email_formulario'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Limpar cache das configurações
            FloatingButtonHelper::clearCache($temaAtivo);
            
            return redirect()->route('dashboard.floating-buttons')
                ->with('success', 'Configurações dos botões flutuantes atualizadas com sucesso!');
                
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar botões flutuantes: ' . $e->getMessage());
            
            return redirect()->route('dashboard.floating-buttons')
                ->with('error', 'Erro ao atualizar configurações. Tente novamente.');
        }
    }
}
