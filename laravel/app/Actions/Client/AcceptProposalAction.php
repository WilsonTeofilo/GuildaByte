<?php

namespace App\Actions\Client;

use App\Models\ContractAcceptance;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Registra o aceite digital de uma proposta.
 * NUNCA confia no front: IP e User-Agent são capturados no servidor.
 * Token é imutável após criação.
 */
final class AcceptProposalAction
{
    public static function run(Project $project, Request $request): ContractAcceptance
    {
        // Garante que o projeto pertence ao cliente autenticado
        if ($project->client_id !== $request->user()->id) {
            throw ValidationException::withMessages([
                'project' => 'Acesso negado a este projeto.',
            ]);
        }

        // Garante que não foi aceito anteriormente
        if ($project->contractAcceptances()->exists()) {
            throw ValidationException::withMessages([
                'project' => 'Esta proposta já foi aceita anteriormente.',
            ]);
        }

        // IP e User-Agent SEMPRE do servidor — nunca do body da request
        return ContractAcceptance::create([
            'project_id'       => $project->id,
            'user_id'          => $request->user()->id,
            'contract_version' => 1,
            'ip_address'       => $request->ip(),
            'user_agent'       => $request->userAgent(),
            'accepted_at'      => now(),
        ]);
    }
}
