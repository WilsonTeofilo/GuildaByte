<?php

namespace App\Actions\Client;

use App\Models\Project;
use App\Models\User;

final class CreateProjectAction
{
    /**
     * Cria o projeto inicial com base nos dados do Wizard.
     * Registra o Snapshot Financeiro inicial (imutável).
     */
    public static function run(User $client, array $data): Project
    {
        $packageSlug = $data['pack'] ?? 'core';
        $packageName = strtoupper($packageSlug);
        
        $baseValue = match($packageSlug) {
            'start'  => 1000.00,
            'core'   => 3000.00,
            'custom' => 4000.00,
            default  => 3000.00,
        };

        return Project::create([
            'client_id'              => $client->id,
            'name'                   => strip_tags($data['name'] ?? 'Novo Projeto'),
            'description'            => strip_tags($data['desc'] ?? ''),
            'agreed_package_name'    => $packageName,
            'agreed_package_version' => 1,
            'agreed_base_value'      => $baseValue,
            'agreed_discount_value'  => 0.00,
            'agreed_final_value'     => $baseValue,
            'guildabyte_fee_percent' => 20.00,
            'guildabyte_fee_value'   => $baseValue * 0.20,
            'team_net_value'         => $baseValue * 0.80,
            'status'                 => 'received',
            'financial_status'       => 'pending',
        ]);
    }
}
