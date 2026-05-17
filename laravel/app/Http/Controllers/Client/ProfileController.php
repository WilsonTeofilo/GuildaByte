<?php

namespace App\Http\Controllers\Client;

use App\Actions\Client\AcceptProposalAction;
use App\Actions\Client\UpdateProfileAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /** Exibe o perfil do cliente */
    public function show(Request $request): View
    {
        $user    = $request->user()->load('clientProfile');
        $profile = $user->clientProfile;

        return view('client.profile', compact('user', 'profile'));
    }

    /** Salva alterações no perfil (backend re-valida tudo) */
    public function update(Request $request): RedirectResponse
    {
        UpdateProfileAction::run($request);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    /** Registra o aceite digital de uma proposta (IP + User-Agent do servidor) */
    public function acceptProposal(Request $request, Project $project): RedirectResponse
    {
        try {
            AcceptProposalAction::run($project, $request);
            return back()->with('success', 'Proposta aceita com sucesso! O contrato está registrado.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator);
        }
    }
}
