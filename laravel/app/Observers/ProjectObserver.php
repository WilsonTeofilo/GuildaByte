<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\FinancialEvent;
use Illuminate\Support\Facades\Auth;

/**
 * Observer do Projeto — log automático em financial_events a cada mudança de status.
 * Não contém lógica de negócio — apenas observa e registra.
 */
class ProjectObserver
{
    public function updating(Project $project): void
    {
        // Só loga se o status mudou
        if (!$project->isDirty('status')) {
            return;
        }

        FinancialEvent::create([
            'project_id' => $project->id,
            'event_type' => 'status_changed',
            'old_value'  => null,
            'new_value'  => null,
            'reason'     => sprintf(
                'Status alterado de "%s" para "%s"',
                $project->getOriginal('status'),
                $project->status
            ),
            'created_by' => Auth::id(),
        ]);
    }
}
