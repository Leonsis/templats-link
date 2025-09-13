<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContatoController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        return view('main-Thema.contato');
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

        return redirect()->route('contato')->with('success', 'Mensagem enviada com sucesso!');
    }
}
