<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContatoController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        $viewPath = \App\Helpers\ThemeHelper::getThemeViewPath('contato');
        return view($viewPath);
    }

    /**
     * Handle contact form submission.
     */
    public function enviar(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'mensagem' => 'required|string|max:1000',
        ]);

        // Aqui você pode adicionar lógica para enviar email ou salvar no banco
        // Por exemplo: Mail::to('contato@templats-link.com')->send(new ContactMail($request->all()));

        // Obter dados de geolocalização para eventos
        $geoData = $this->getGeoDataByIP($request->ip());
        $nameParts = explode(' ', trim($request->nome));
        $firstName = $nameParts[0] ?? '';
        $lastName = implode(' ', array_slice($nameParts, 1)) ?? '';

        return redirect()->route('contato')->with([
            'success' => 'Mensagem enviada com sucesso!',
            'event_data' => [
                'name' => $firstName,
                'surname' => $lastName,
                'email' => $request->email,
                'phone' => $request->telefone,
                'country' => $geoData['country'],
                'cep' => $geoData['zip'],
                'region' => $geoData['region'],
                'city' => $geoData['city'],
                'street' => $geoData['street'],
                'service' => 'contact',
                'source' => 'footer_form'
            ]
        ]);
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
}
