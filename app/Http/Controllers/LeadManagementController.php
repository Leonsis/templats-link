<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadManagementController extends Controller
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
     * Display a listing of leads.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $query = Lead::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('origem')) {
            $query->where('origem', $request->origem);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginação
        $leads = $query->paginate(10);

        // Estatísticas
        $totalLeads = Lead::count();
        $leadsHoje = Lead::whereDate('created_at', today())->count();
        $leadsSemana = Lead::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $leadsMes = Lead::whereMonth('created_at', now()->month)->count();

        // Origens disponíveis para filtro
        $origens = Lead::select('origem')->distinct()->pluck('origem');

        return view('dashboard.leads.index', compact(
            'leads', 
            'totalLeads', 
            'leadsHoje', 
            'leadsSemana', 
            'leadsMes', 
            'origens'
        ));
    }

    /**
     * Display the specified lead.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Lead $lead)
    {
        return view('dashboard.leads.show', compact('lead'));
    }

    /**
     * Remove the specified lead from storage.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        
        return redirect()->route('dashboard.leads')
            ->with('success', 'Lead removido com sucesso!');
    }
}
