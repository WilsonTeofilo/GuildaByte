<?php

namespace App\Http\Controllers\Admin;

use App\Models\FinancialEvent;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Métricas reais do Dashboard
        $newOrdersCount   = Project::where('status', 'received')->count();
        $pendingProposals = Project::where('status', 'proposal_sent')->count();

        // Receita do mês: projetos pagos (status entregue/manutenção com agreed_final_value)
        $monthlyRevenue = Project::whereIn('status', ['delivered', 'maintenance'])
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('agreed_final_value');

        // Projetos em desenvolvimento que ultrapassaram o prazo combinado
        $overdueCount = Project::where('status', 'in_progress')
            ->whereNotNull('started_at')
            ->whereNotNull('agreed_deadline_days')
            ->get()
            ->filter(fn($p) =>
                Carbon::parse($p->started_at)
                    ->addDays((int) $p->agreed_deadline_days)
                    ->isPast()
            )
            ->count();

        $totalClients = User::where('user_type', 'client')->count();

        // Feed de atividade: últimos 8 financial_events com relações
        $activityFeed = FinancialEvent::with(['project.client', 'creator'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'newOrdersCount',
            'pendingProposals',
            'monthlyRevenue',
            'overdueCount',
            'totalClients',
            'activityFeed'
        ));
    }
}
