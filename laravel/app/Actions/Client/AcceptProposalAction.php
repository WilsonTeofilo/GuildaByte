<?php

namespace App\Actions\Client;

use App\Models\ContractAcceptance;
use App\Models\Project;
use App\Models\User;
use Illuminate\Validation\ValidationException;

/**
 * Registra o aceite digital de uma proposta.
 * Recebe IP e UserAgent separadamente.
 */
final class AcceptProposalAction
{
    public static function run(Project $project, User $user, string $ip, string $userAgent): ContractAcceptance
    {
        // Garante que o projeto pertence ao cliente
        if ($project->client_id !== $user->id) {
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

        $acceptance = ContractAcceptance::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'accepted_at' => now(),
        ]);

        $project->update(['status' => 'proposal_accepted']);

        return $acceptance;
    }
}
