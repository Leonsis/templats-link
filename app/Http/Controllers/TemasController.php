<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use ZipArchive;

class TemasController extends Controller
{
    public function index()
    {
        $temas = $this->getTemasList();
        return view('dashboard.temas.index', compact('temas'));
    }

    public function store(Request $request)
    {
        // Debug: Log dos dados recebidos
        \Log::info('Upload de tema iniciado', [
            'nome_tema' => $request->input('nome_tema'),
            'arquivo_zip' => $request->hasFile('arquivo_zip') ? $request->file('arquivo_zip')->getClientOriginalName() : 'não enviado',
            'tamanho_arquivo_zip' => $request->hasFile('arquivo_zip') ? $request->file('arquivo_zip')->getSize() : 0,
            'arquivo_paginas' => $request->hasFile('arquivo_paginas') ? $request->file('arquivo_paginas')->getClientOriginalName() : 'não enviado',
            'tamanho_arquivo_paginas' => $request->hasFile('arquivo_paginas') ? $request->file('arquivo_paginas')->getSize() : 0
        ]);

        $request->validate([
            'nome_tema' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]+$/',
            'arquivo_zip' => 'required|file|max:10485760', // 10MB = 10 * 1024 * 1024 bytes
            'arquivo_paginas' => 'required|file|max:10485760' // 10MB = 10 * 1024 * 1024 bytes
        ], [
            'nome_tema.required' => 'O nome do tema é obrigatório.',
            'nome_tema.regex' => 'O nome do tema deve conter apenas letras, números, hífens e underscores.',
            'arquivo_zip.required' => 'O arquivo ZIP dos assets é obrigatório.',
            'arquivo_zip.max' => 'O arquivo ZIP dos assets não pode ser maior que 10MB.',
            'arquivo_paginas.required' => 'O arquivo ZIP das páginas é obrigatório.',
            'arquivo_paginas.max' => 'O arquivo ZIP das páginas não pode ser maior que 10MB.'
        ]);

        $nomeTema = $request->input('nome_tema');
        $arquivoZip = $request->file('arquivo_zip');
        $arquivoPaginas = $request->file('arquivo_paginas');
        
        // Verificar se os arquivos são ZIPs válidos
        $zip = new ZipArchive;
        
        // Validar ZIP dos assets
        $tempPathAssets = $arquivoZip->getPathname();
        if ($zip->open($tempPathAssets) !== TRUE) {
            return back()->withErrors(['arquivo_zip' => 'O arquivo ZIP dos assets não é válido ou está corrompido.']);
        }
        $zip->close();
        
        // Validar ZIP das páginas
        $tempPathPaginas = $arquivoPaginas->getPathname();
        if ($zip->open($tempPathPaginas) !== TRUE) {
            return back()->withErrors(['arquivo_paginas' => 'O arquivo ZIP das páginas não é válido ou está corrompido.']);
        }
        $zip->close();
        
        // Verificar se o tema já existe
        $temaPath = public_path('temas/' . $nomeTema);
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        if (File::exists($temaPath) || File::exists($temaViewsPath)) {
            return back()->withErrors(['nome_tema' => 'Já existe um tema com este nome.']);
        }

        try {
            // Criar diretórios do tema
            File::makeDirectory($temaPath, 0755, true);
            File::makeDirectory($temaViewsPath, 0755, true);

            // Processar ZIP dos assets
            $zipPathAssets = $temaPath . '/' . $arquivoZip->getClientOriginalName();
            $arquivoZip->move($temaPath, $arquivoZip->getClientOriginalName());

            $zip = new ZipArchive;
            if ($zip->open($zipPathAssets) === TRUE) {
                $zip->extractTo($temaPath);
                $zip->close();
                
                // Deletar o arquivo ZIP dos assets após descompactação
                File::delete($zipPathAssets);
            } else {
                // Se falhou ao abrir o ZIP dos assets, limpar os diretórios criados
                File::deleteDirectory($temaPath);
                File::deleteDirectory($temaViewsPath);
                return back()->withErrors(['arquivo_zip' => 'Erro ao processar o arquivo ZIP dos assets. Verifique se o arquivo não está corrompido.']);
            }

            // Processar ZIP das páginas
            $zipPathPaginas = $temaViewsPath . '/' . $arquivoPaginas->getClientOriginalName();
            $arquivoPaginas->move($temaViewsPath, $arquivoPaginas->getClientOriginalName());

            if ($zip->open($zipPathPaginas) === TRUE) {
                $zip->extractTo($temaViewsPath);
                $zip->close();
                
                // Deletar o arquivo ZIP das páginas após descompactação
                File::delete($zipPathPaginas);
                
                return redirect()->route('dashboard.temas')->with('success', 'Tema "' . $nomeTema . '" instalado com sucesso! Assets e páginas processados.');
            } else {
                // Se falhou ao abrir o ZIP das páginas, limpar os diretórios criados
                File::deleteDirectory($temaPath);
                File::deleteDirectory($temaViewsPath);
                return back()->withErrors(['arquivo_paginas' => 'Erro ao processar o arquivo ZIP das páginas. Verifique se o arquivo não está corrompido.']);
            }
        } catch (\Exception $e) {
            // Em caso de erro, limpar os diretórios criados
            if (File::exists($temaPath)) {
                File::deleteDirectory($temaPath);
            }
            if (File::exists($temaViewsPath)) {
                File::deleteDirectory($temaViewsPath);
            }
            return back()->withErrors(['arquivo_zip' => 'Erro ao processar o tema: ' . $e->getMessage()]);
        }
    }

    public function destroy($nomeTema)
    {
        $temaPath = public_path('temas/' . $nomeTema);
        $temaViewsPath = resource_path('views/temas/' . $nomeTema);
        
        if (!File::exists($temaPath) && !File::exists($temaViewsPath)) {
            return back()->withErrors(['tema' => 'Tema não encontrado.']);
        }

        try {
            // Remover assets do tema
            if (File::exists($temaPath)) {
                File::deleteDirectory($temaPath);
            }
            
            // Remover templates do tema
            if (File::exists($temaViewsPath)) {
                File::deleteDirectory($temaViewsPath);
            }
            
            return redirect()->route('dashboard.temas')->with('success', 'Tema "' . $nomeTema . '" removido com sucesso! Assets e páginas removidos.');
        } catch (\Exception $e) {
            return back()->withErrors(['tema' => 'Erro ao remover o tema: ' . $e->getMessage()]);
        }
    }

    private function getTemasList()
    {
        $temasPath = public_path('temas');
        $temas = [];

        if (File::exists($temasPath)) {
            $diretorios = File::directories($temasPath);
            
            foreach ($diretorios as $diretorio) {
                $nomeTema = basename($diretorio);
                $arquivos = File::allFiles($diretorio);
                
                // Verificar se existem páginas do tema
                $temaViewsPath = resource_path('views/temas/' . $nomeTema);
                $temPaginas = File::exists($temaViewsPath);
                $arquivosPaginas = $temPaginas ? File::allFiles($temaViewsPath) : [];
                
                $temas[] = [
                    'nome' => $nomeTema,
                    'caminho' => $diretorio,
                    'arquivos' => count($arquivos),
                    'tamanho' => $this->getDirectorySize($diretorio),
                    'criado_em' => date('d/m/Y H:i', filemtime($diretorio)),
                    'tem_paginas' => $temPaginas,
                    'arquivos_paginas' => count($arquivosPaginas)
                ];
            }
        }

        return $temas;
    }

    private function getDirectorySize($directory)
    {
        $size = 0;
        foreach (File::allFiles($directory) as $file) {
            $size += $file->getSize();
        }
        
        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }
}
