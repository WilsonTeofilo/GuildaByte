<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /** Admin e root veem todos os projetos. Cliente só vê o seu. */
    public function view(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $project->client_id;
    }

    /** Somente Admin Root ou Admin podem criar projetos (ao aceitar pedido). */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /** Admin pode editar; cliente não pode alterar projeto diretamente. */
    public function update(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /** Nunca deletar projeto — apenas fechar via status (softDelete). */
    public function delete(User $user, Project $project): bool
    {
        return $user->user_type === 'root';
    }
}
