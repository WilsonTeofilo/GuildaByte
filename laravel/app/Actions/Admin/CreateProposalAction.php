<?php

namespace App\Actions\Admin;

use App\Models\Contract;
use App\Models\FinancialEvent;
use App\Models\Project;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class CreateProposalAction
{
    /**
     * Cria a proposta e o contrato associado.
     * Atualiza o projeto para 'proposal_sent'.
     */
    public static function run(Project $project, array $data): Proposal
    {
        return DB::transaction(function () use ($project, $data) {
            // Cria a proposta
            $proposal = Proposal::create([
                'project_id' => $project->id,
                'client_id' => $project->client_id,
                'scope' => strip_tags($data['scope'] ?? 'Escopo nÃ£o definido.'),
                'proposed_value' => $data['proposed_value'] ?? $project->agreed_base_value,
                'proposed_deadline_days' => $data['proposed_deadline_days'] ?? 30,
                'includes' => $data['includes'] ?? [],
                'excludes' => $data['excludes'] ?? [],
                'status' => 'pending',
            ]);

            // Cria o contrato vinculado Ã  proposta
            Contract::create([
                'project_id' => $project->id,
                'proposal_id' => $proposal->id,
                'terms' => 'Termos do contrato baseados na proposta.',
                'version' => 1,
            ]);

            // Se o valor proposto for diferente do valor base gravado, precisamos registrar um evento financeiro
            // de ajuste manual (embora o agreed_final_value nÃ£o mude automaticamente aqui)
            // Futuramente, quando o cliente aceitar a proposta, o agreed_final_value poderÃ¡ ser atualizado
            // e registrarÃ¡ o observer.

            // Atualiza status do projeto para proposta enviada
            $project->update([
                'status' => 'proposal_sent',
                'agreed_deadline_days' => $proposal->proposed_deadline_days,
            ]);

            return $proposal;
        });
    }
}
