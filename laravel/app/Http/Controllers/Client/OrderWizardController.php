<?php

namespace App\Http\Controllers\Client;

use App\Actions\Client\CreateProjectAction;
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
        $validated = $request->validate([
            'pack' => ['required', 'string', 'in:start,core,custom'],
            'name' => ['required', 'string', 'max:255'],
            'desc' => ['nullable', 'string', 'max:5000'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:100'],
            'ref1' => ['nullable', 'url', 'max:255'],
            'ref2' => ['nullable', 'url', 'max:255'],
            'has_id' => ['nullable', 'string', 'max:20'],
        ]);

        CreateProjectAction::run($request->user(), $validated);

        return response()->json(['success' => true, 'redirect' => route('client.dashboard')]);
    }
}
