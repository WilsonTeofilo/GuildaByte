<?php

namespace App\Actions\Admin;

use App\Models\Project;
use App\Models\ScopeAddendum;

class CreateAddendumAction
{
    public function execute(Project $project, array $data): ScopeAddendum
    {
        return ScopeAddendum::create([
            'project_id' => $project->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'additional_cost' => $data['additional_cost'],
            'additional_days' => $data['additional_days'],
            'status' => 'pending'
        ]);
    }
}
