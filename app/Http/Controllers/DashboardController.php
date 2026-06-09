<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usuario = Auth::user();
        $totalUsuarios = \App\Models\Usuario::count();
        $usuariosAtivos = \App\Models\Usuario::where('ativo', 1)->count();
        $admins = \App\Models\Usuario::where('tipo', 'admin')->count();
        $dataAtual = now()->format('d/m/Y');
        
        // Dados dos leads
        $totalLeads = \App\Models\Lead::count();
        $leadsRecentes = \App\Models\Lead::orderBy('created_at', 'desc')->limit(10)->get();
        
        $dados = [
            'usuario' => $usuario,
            'isAdmin' => $usuario->isAdmin(),
            'totalUsuarios' => $totalUsuarios,
            'usuariosAtivos' => $usuariosAtivos,
            'admins' => $admins,
            'dataAtual' => $dataAtual,
            'totalLeads' => $totalLeads,
            'leadsRecentes' => $leadsRecentes,
        ];

        return view('dashboard.index', compact('dados'));
    }


    /**
     * Show admin panel (only for admins).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function admin()
    {
        $this->authorize('admin'); // Verifica se é admin
        
        $usuarios = \App\Models\Usuario::orderBy('criado_em', 'desc')->get();
        
        return view('dashboard.admin', compact('usuarios'));
    }
}
