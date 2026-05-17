<?php

namespace App\Http\Controllers\Client;

use App\Actions\Client\AcceptProposalAction;
use App\Actions\Client\UpdateProfileAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /** Exibe o perfil do cliente */
    public function show(Request $request): View
    {
        $user = $request->user()->load('clientProfile');
        $profile = $user->clientProfile;

        return view('client.profile', compact('user', 'profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\+\-\(\) ]{10,20}$/'],
            'business_type' => ['nullable', 'string', 'max:100'],
            'business_name' => ['nullable', 'string', 'max:100'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'marketing_email' => ['nullable', 'boolean'],
            'marketing_whatsapp' => ['nullable', 'boolean'],
            'delete_account' => ['nullable', 'boolean'],
        ]);

        UpdateProfileAction::run($request->user(), $data);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    public function acceptProposal(Request $request, Project $project): RedirectResponse
    {
        try {
            AcceptProposalAction::run($project, $request->user(), $request->ip(), $request->userAgent());

            return back()->with('success', 'Proposta aceita com sucesso! O contrato está registrado.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator);
        }
    }
}
