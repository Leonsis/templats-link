<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\HeadHelper;

class NavbarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Acesso negado. Você não tem permissão de administrador.');
            }
            return $next($request);
        });
    }
    
    public function index()
    {
        // Buscar configurações da navbar
        $navbarConfigs = HeadHelper::getNavbarConfigs();
        
        return view('dashboard.navbar.index', compact('navbarConfigs'));
    }
    
    public function getImages()
    {
        try {
            $logoDir = storage_path('app/uploads/logos');
            $faviconDir = storage_path('app/uploads/favicons');

            $images = [];
            $processedFiles = []; // Para evitar duplicatas

            // Buscar logos
            if (is_dir($logoDir)) {
                $logoFiles = glob($logoDir . '/*.{webp,png,jpg,jpeg}', GLOB_BRACE);
                foreach ($logoFiles as $file) {
                    $filename = basename($file);
                    if (!in_array($filename, $processedFiles)) {
                        $images[] = [
                            'filename' => $filename,
                            'url' => route('logo', ['filename' => $filename]),
                            'type' => 'logo',
                            'size' => filesize($file),
                            'modified' => filemtime($file)
                        ];
                        $processedFiles[] = $filename;
                    }
                }
            }

            // Buscar favicons
            if (is_dir($faviconDir)) {
                $faviconFiles = glob($faviconDir . '/*.{webp,png,jpg,jpeg}', GLOB_BRACE);
                foreach ($faviconFiles as $file) {
                    $filename = basename($file);
                    if (!in_array($filename, $processedFiles)) {
                        $images[] = [
                            'filename' => $filename,
                            'url' => route('favicon', ['filename' => $filename]),
                            'type' => 'favicon',
                            'size' => filesize($file),
                            'modified' => filemtime($file)
                        ];
                        $processedFiles[] = $filename;
                    }
                }
            }

            // Ordenar por data de modificação (mais recentes primeiro)
            usort($images, function($a, $b) {
                return $b['modified'] - $a['modified'];
            });

            return response()->json($images);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:webp|max:2048',
            'logo_filename' => 'nullable|string|max:255',
            'logo_footer' => 'nullable|image|mimes:webp|max:2048',
            'logo_footer_filename' => 'nullable|string|max:255',
            'descricao_footer' => 'nullable|string|max:1000',
            'copyright_footer' => 'nullable|string|max:255',
            'email_contato' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'endereco' => 'nullable|string|max:500',
            'horario_atendimento' => 'nullable|string|max:500',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
        ], [
            'logo.image' => 'O arquivo deve ser uma imagem.',
            'logo.mimes' => 'O arquivo deve ser WebP.',
            'logo.max' => 'O arquivo deve ter no máximo 2MB.',
            'email_contato.email' => 'Digite um email válido.',
            'facebook.url' => 'Digite uma URL válida para o Facebook.',
            'instagram.url' => 'Digite uma URL válida para o Instagram.',
            'twitter.url' => 'Digite uma URL válida para o Twitter.',
            'linkedin.url' => 'Digite uma URL válida para o LinkedIn.',
            'youtube.url' => 'Digite uma URL válida para o YouTube.',
        ]);
        
        try {
            $updateData = [
                'descricao_footer' => $request->descricao_footer,
                'copyright_footer' => $request->copyright_footer,
                'email_contato' => $request->email_contato,
                'telefone' => $request->telefone,
                'whatsapp' => $request->whatsapp,
                'endereco' => $request->endereco,
                'horario_atendimento' => $request->horario_atendimento,
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'twitter' => $request->twitter,
                'linkedin' => $request->linkedin,
                'youtube' => $request->youtube,
                'updated_at' => now(),
            ];
            
            // Se for apenas para salvar o logo
            if ($request->has('save_logo')) {
                $updateData = ['updated_at' => now()];
            }
            
            // Se for apenas para salvar o logo footer
            if ($request->has('save_logo_footer')) {
                $updateData = ['updated_at' => now()];
            }
            
            // Processar upload do logo se fornecido
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/logos', $filename, 'local');
                $updateData['logo'] = $path;
            } elseif ($request->logo_filename) {
                // Usar logo existente selecionado da galeria
                $filename = $request->logo_filename;
                $logoPath = 'uploads/logos/' . $filename;
                $faviconPath = 'uploads/favicons/' . $filename;
                
                // Verificar se existe em logos ou favicons
                if (file_exists(storage_path('app/' . $logoPath))) {
                    $updateData['logo'] = $logoPath;
                } elseif (file_exists(storage_path('app/' . $faviconPath))) {
                    // Se está em favicons, mover para logos
                    $sourcePath = storage_path('app/' . $faviconPath);
                    $targetPath = storage_path('app/' . $logoPath);
                    
                    // Criar diretório de destino se não existir
                    if (!is_dir(dirname($targetPath))) {
                        mkdir(dirname($targetPath), 0755, true);
                    }
                    
                    // Copiar arquivo para logos
                    if (copy($sourcePath, $targetPath)) {
                        $updateData['logo'] = $logoPath;
                    } else {
                        // Se não conseguir copiar, usar o caminho original
                        $updateData['logo'] = $faviconPath;
                    }
                }
            }
            
            // Processar upload do logo footer se fornecido
            if ($request->hasFile('logo_footer')) {
                $file = $request->file('logo_footer');
                $filename = 'logo_footer_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/logos', $filename, 'local');
                $updateData['logo_footer'] = $path;
            } elseif ($request->logo_footer_filename) {
                // Usar logo footer existente selecionado da galeria
                $filename = $request->logo_footer_filename;
                $logoPath = 'uploads/logos/' . $filename;
                $faviconPath = 'uploads/favicons/' . $filename;
                
                // Verificar se existe em logos ou favicons
                if (file_exists(storage_path('app/' . $logoPath))) {
                    $updateData['logo_footer'] = $logoPath;
                } elseif (file_exists(storage_path('app/' . $faviconPath))) {
                    // Se está em favicons, copiar para logos
                    $sourcePath = storage_path('app/' . $faviconPath);
                    $targetPath = storage_path('app/' . $logoPath);
                    
                    // Criar diretório de destino se não existir
                    if (!is_dir(dirname($targetPath))) {
                        mkdir(dirname($targetPath), 0755, true);
                    }
                    
                    // Copiar arquivo para logos
                    if (copy($sourcePath, $targetPath)) {
                        $updateData['logo_footer'] = $logoPath;
                    } else {
                        // Se não conseguir copiar, usar o caminho original
                        $updateData['logo_footer'] = $faviconPath;
                    }
                }
            }
            
            DB::table('head_configs')->updateOrInsert(
                ['pagina' => 'global'],
                $updateData
            );
            
            // Limpar cache do helper
            if (method_exists(HeadHelper::class, 'clearCache')) {
                HeadHelper::clearCache('global');
            }
            
            if ($request->has('save_logo')) {
                return redirect()->route('dashboard.navbar')->with('success', 'Logo Navbar salvo com sucesso!');
            } elseif ($request->has('save_logo_footer')) {
                return redirect()->route('dashboard.navbar')->with('success', 'Logo Footer salvo com sucesso!');
            } else {
                return redirect()->route('dashboard.navbar')->with('success', 'Configurações da Navbar/Footer salvas com sucesso!');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('dashboard.navbar')->with('error', 'Erro ao atualizar configurações: ' . $e->getMessage());
        }
    }
}
