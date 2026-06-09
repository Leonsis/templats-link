<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Helpers\FloatingButtonHelper;
use App\Helpers\EmailHelper;
use Illuminate\Support\Facades\Http;

class LeadController extends Controller
{
    /**
     * Store a new lead from WhatsApp floating button.
     */
    public function store(Request $request)
    {
        \Log::info('=== LEAD CONTROLLER CALLED ===');
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'required|string|max:20',
            'faturamento' => 'nullable|string|max:255',
            'area_atuacao' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            // Se for requisição AJAX, retorna JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Se for requisição normal, redireciona com erro
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Determinar origem baseada no User-Agent ou referrer
            $origem = 'formulario_contato';
            $userAgent = $request->header('User-Agent', '');
            $referer = $request->header('Referer', '');
            $fromWhatsapp = $request->has('from_whatsapp');
            
            // Debug: Log dos dados recebidos
            \Log::info('=== LEAD CONTROLLER DEBUG ===');
            \Log::info('User Agent: ' . $userAgent);
            \Log::info('Referer: ' . $referer);
            \Log::info('From WhatsApp: ' . ($fromWhatsapp ? 'YES' : 'NO'));
            \Log::info('All Request Data: ' . json_encode($request->all()));
            \Log::info('Has from_whatsapp: ' . ($request->has('from_whatsapp') ? 'YES' : 'NO'));
            \Log::info('from_whatsapp value: ' . $request->input('from_whatsapp', 'NOT_FOUND'));
            
            // Se vier do floatingButton (WhatsApp), usar origem específica
            if ($fromWhatsapp || $request->input('from_whatsapp') == '1') {
                $origem = 'whatsapp_flutuante';
                \Log::info('Origem definida como whatsapp_flutuante - from_whatsapp detectado');
            } else {
                \Log::info('Origem definida como formulario_contato - from_whatsapp NÃO detectado');
            }
            
            $lead = Lead::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->tel,
                'origem' => $origem,
                'observacoes' => 'Lead capturado através do ' . ($origem === 'whatsapp_flutuante' ? 'botão flutuante do WhatsApp' : 'formulário de contato') . '. Faturamento: ' . ($request->faturamento ?? '') . ', Área: ' . ($request->area_atuacao ?? '')
            ]);

            // Enviar email de notificação se estiver configurado
            $emailFormulario = FloatingButtonHelper::getEmailFormulario();
            if (!empty($emailFormulario)) {
                $dadosLead = [
                    'nome' => $request->nome,
                    'email' => $request->email,
                    'telefone' => $request->tel,
                    'faturamento' => $request->faturamento ?? '',
                    'area_atuacao' => $request->area_atuacao ?? '',
                    'origem' => $origem === 'whatsapp_flutuante' ? 'Botão Flutuante WhatsApp' : 'Formulário de Contato'
                ];
                
                EmailHelper::enviarNotificacaoLead($dadosLead, $emailFormulario);
            } else {
                \Log::info('Email do formulário não configurado');
            }

            // Obter dados de geolocalização para eventos
            $geoData = $this->getGeoDataByIP($request->ip());
            $nameParts = explode(' ', trim($request->nome));
            $firstName = $nameParts[0] ?? '';
            $lastName = implode(' ', array_slice($nameParts, 1)) ?? '';

            // Preparar dados completos para analytics
            $analyticsData = [
                'name' => $firstName,
                'surname' => $lastName,
                'email' => $request->email,
                'phone' => $request->tel,
                'country' => $geoData['country'],
                'cep' => $geoData['zip'],
                'region' => $geoData['region'],
                'city' => $geoData['city'],
                'street' => $geoData['street'],
                'service' => $origem === 'whatsapp_flutuante' ? 'whatsapp' : 'contact',
                'source' => $origem === 'whatsapp_flutuante' ? 'floating_button' : 'footer_form',
                'lead_id' => $lead->id,
                'timestamp' => now()->toISOString(),
                'user_agent' => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
                'referrer' => $request->header('Referer'),
                'session_data' => $this->getSessionData($request)
            ];

            // Se for requisição AJAX, retorna JSON com dados de evento
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lead salvo com sucesso!',
                    'lead_id' => $lead->id,
                    'event_data' => $analyticsData
                ]);
            }
            
            // Se for requisição normal, redireciona com sucesso e dados para analytics
            return redirect()->route('home')->with([
                'success' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.',
                'event_data' => $analyticsData
            ]);

        } catch (\Exception $e) {
            // Se for requisição AJAX, retorna JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao salvar lead: ' . $e->getMessage()
                ], 500);
            }
            
            // Se for requisição normal, redireciona com erro
            return redirect()->route('home')->with('error', 'Erro ao enviar mensagem. Tente novamente.');
        }
    }

    /**
     * Obter dados de geolocalização via IP
     */
    private function getGeoDataByIP($ipAddress)
    {
        try {
            $response = Http::get("https://ipapi.co/{$ipAddress}/json/");
            $data = $response->json();

            return [
                'country' => $data['country_name'] ?? 'Brasil',
                'region' => $data['region'] ?? 'Distrito Federal',
                'city' => $data['city'] ?? 'Brasília',
                'zip' => $data['postal'] ?? '70000-000',
                'street' => '',
                'ip_address' => $ipAddress,
                'user_agent' => request()->header('User-Agent'),
                'timestamp' => now()->toISOString()
            ];
        } catch (\Exception $e) {
            return [
                'country' => 'Brasil',
                'region' => 'Distrito Federal',
                'city' => 'Brasília',
                'zip' => '70000-000',
                'street' => '',
                'ip_address' => $ipAddress,
                'user_agent' => request()->header('User-Agent'),
                'timestamp' => now()->toISOString()
            ];
        }
    }

    /**
     * Obter dados da sessão para analytics
     */
    private function getSessionData(Request $request)
    {
        return [
            'session_id' => $request->session()->getId(),
            'user_agent' => $request->header('User-Agent'),
            'referrer' => $request->header('Referer'),
            'language' => $request->header('Accept-Language'),
            'timezone' => $request->header('X-Timezone', 'America/Sao_Paulo'),
            'device_type' => $this->getDeviceType($request->header('User-Agent')),
            'browser' => $this->getBrowser($request->header('User-Agent')),
            'os' => $this->getOperatingSystem($request->header('User-Agent')),
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Detectar tipo de dispositivo
     */
    private function getDeviceType($userAgent)
    {
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/Tablet|iPad/', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Detectar navegador
     */
    private function getBrowser($userAgent)
    {
        if (preg_match('/Chrome/', $userAgent)) return 'Chrome';
        if (preg_match('/Firefox/', $userAgent)) return 'Firefox';
        if (preg_match('/Safari/', $userAgent)) return 'Safari';
        if (preg_match('/Edge/', $userAgent)) return 'Edge';
        if (preg_match('/Opera/', $userAgent)) return 'Opera';
        return 'Unknown';
    }

    /**
     * Detectar sistema operacional
     */
    private function getOperatingSystem($userAgent)
    {
        if (preg_match('/Windows/', $userAgent)) return 'Windows';
        if (preg_match('/Mac/', $userAgent)) return 'macOS';
        if (preg_match('/Linux/', $userAgent)) return 'Linux';
        if (preg_match('/Android/', $userAgent)) return 'Android';
        if (preg_match('/iOS/', $userAgent)) return 'iOS';
        return 'Unknown';
    }
}
