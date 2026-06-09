<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    /**
     * Mostra o formulário de login
     */
    public function showLoginForm()
    {
        // Login sempre usa main-Thema, independente do tema selecionado
        return view('main-Thema.auth.login');
    }

    /**
     * Processa o login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        ]);

        // Buscar usuário pelo email
        $usuario = Usuario::where('email', $request->email)
                         ->where('ativo', 1)
                         ->first();

        if ($usuario && Hash::check($request->password, $usuario->senha)) {
            // Login manual para usar a tabela usuarios
            Auth::login($usuario, $request->filled('remember'));
            $request->session()->regenerate();
            
            return redirect()->route('dashboard')->with('success', 'Login realizado com sucesso!');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Faz logout do usuário
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Logout realizado com sucesso!');
    }
}
