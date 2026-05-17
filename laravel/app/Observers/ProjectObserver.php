<?php

namespace App\Observers;

use App\Models\FinancialEvent;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

/**
 * Observer do Projeto — log automÃ¡tico em financial_events.
 * Consertando o Buraco 3 da AnÃ¡lise EstratÃ©gica.
 */
class ProjectObserver
{
    public function created(Project $project): void
    {
        // Snapshot inicial financeiro imutÃ¡vel
        FinancialEvent::create([
            'project_id' => $project->id,
            'event_type' => 'price_created',
            'old_value' => null,
            'new_value' => $project->agreed_final_value,
            'reason' => 'Projeto criado via Wizard. Valor acordado base registrado.',
            'created_by' => Auth::id() ?? $project->client_id,
        ]);
    }

    public function updating(Project $project): void
    {
        // Se o status mudou
        if ($project->isDirty('status')) {
            FinancialEvent::create([
                'project_id' => $project->id,
                'event_type' => 'status_changed',
                'old_value' => null,
                'new_value' => null,
                'reason' => sprintf(
                    'Status alterado de "%s" para "%s"',
                    $project->getOriginal('status'),
                    $project->status
                ),
                'created_by' => Auth::id(),
            ]);
        }

        // Se o valor acordado mudou (sÃ³ deve ocorrer por admin root via scope addendum)
        if ($project->isDirty('agreed_final_value')) {
            FinancialEvent::create([
                'project_id' => $project->id,
                'event_type' => 'manual_adjustment',
                'old_value' => $project->getOriginal('agreed_final_value'),
                'new_value' => $project->agreed_final_value,
                'reason' => 'Ajuste manual de valor do projeto.',
                'created_by' => Auth::id(),
            ]);
        }
    }
}
