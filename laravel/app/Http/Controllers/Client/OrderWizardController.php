<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderWizardController extends Controller
{
    /**
     * Exibe a interface do Wizard.
     */
    public function create(Request $request): View
    {
        $plans = config('landing.plans');
        
        // Verifica se o usuário já escolheu algum pacote na landing page e está salvo na sessão
        $preSelectedPlan = session('selected_plan', 'start');

        return view('client.wizard', [
            'plans' => $plans,
            'preSelectedPlan' => $preSelectedPlan,
        ]);
    }

    /**
     * Processa a criação do Projeto e do primeiro Snapshot após o Wizard.
     */
    public function store(Request $request)
    {
        // Aqui entra a chamada para a Action (Missão 6.2 - Snapshot Financeiro)
        // Por agora, vamos apenas simular o retorno de sucesso
        return response()->json(['success' => true, 'redirect' => route('client.dashboard')]);
    }
}
