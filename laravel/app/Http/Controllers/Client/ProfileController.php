<?php

namespace App\Http\Controllers\Client;

use App\Actions\Client\AcceptProposalAction;
use App\Actions\Client\ChangePasswordAction;
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
        $user    = $request->user()->load('clientProfile');
        $profile = $user->clientProfile;
        return view('client.profile', compact('user', 'profile'));
    }

    /** Atualiza dados de perfil (telefone, negócio, LGPD) */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone'               => ['nullable', 'string', 'max:20', 'regex:/^[\d\+\-\(\) ]{10,20}$/'],
            'business_type'       => ['nullable', 'string', 'max:100'],
            'business_name'       => ['nullable', 'string', 'max:100'],
            'instagram'           => ['nullable', 'string', 'max:100'],
            'website'             => ['nullable', 'url', 'max:255'],
            'marketing_email'     => ['nullable', 'boolean'],
            'marketing_whatsapp'  => ['nullable', 'boolean'],
        ]);

        UpdateProfileAction::run($request->user(), $data);
        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    // ── Troca de Senha via OTP (3 min, burn-after-use) ───────────────

    /**
     * Passo 1: Envia OTP de 6 dígitos para o e-mail do usuário.
     * Rate limited a 5 req/min na rota.
     */
    public function sendPasswordOtp(Request $request): RedirectResponse
    {
        try {
            ChangePasswordAction::sendOtp($request->user());
            return back()->with('success', 'Código enviado para ' . $request->user()->email . '. Válido por 3 minutos.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator);
        }
    }

    /**
     * Passo 2: Valida o OTP e efetiva a nova senha.
     * Token é incinerado no servidor independente do resultado.
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'otp_code'                  => ['required', 'string', 'size:6'],
            'new_password'              => ['required', 'string', 'min:8', 'confirmed'],
            'new_password_confirmation' => ['required', 'string'],
        ]);

        try {
            ChangePasswordAction::confirm(
                $request->user(),
                $data['otp_code'],
                $data['new_password'],
                $request->ip()
            );
            return back()->with('success', 'Senha alterada com sucesso!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator);
        }
    }

    // ── Aceite Digital de Proposta ────────────────────────────────────

    public function acceptProposal(Request $request, Project $project): RedirectResponse
    {
        try {
            AcceptProposalAction::run($project, $request->user(), $request->ip(), $request->userAgent());
            return back()->with('success', 'Proposta aceita! O contrato está registrado.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator);
        }
    }
}
