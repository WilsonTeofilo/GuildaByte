<?php

namespace App\Actions\Client;

use App\Models\Package;
use App\Models\Project;
use App\Models\User;

final class CreateProjectAction
{
    /**
     * Cria o projeto inicial com base nos dados do Wizard.
     * Busca os preÃ§os reais na base de dados atravÃ©s da versÃ£o atual do pacote.
     * Registra o Snapshot Financeiro inicial (imutÃ¡vel).
     */
    public static function run(User $client, array $data): Project
    {
        $packageSlug = $data['pack'] ?? 'core';
        
        // Buraco 1 Consertado: Buscar dados reais do banco
        $package = Package::where('slug', $packageSlug)->first();
        if (! $package) {
            $package = Package::where('slug', 'core')->firstOrFail();
        }
        
        $packageVersion = $package->currentVersion;
        $baseValue = $packageVersion ? $packageVersion->base_price : 3000.00; // fallback de seguranÃ§a

        $featuresList = ! empty($data['features']) ? implode(', ', $data['features']) : 'Nenhum objetivo especÃ­fico';
        $fullDescription = sprintf(
            "%s\n\n**Objetivos:** %s\n**Ref 1:** %s\n**Ref 2:** %s\n**Identidade:** %s",
            strip_tags($data['desc'] ?? ''),
            strip_tags($featuresList),
            strip_tags($data['ref1'] ?? 'N/A'),
            strip_tags($data['ref2'] ?? 'N/A'),
            strip_tags($data['has_id'] ?? 'N/A')
        );

        return Project::create([
            'client_id' => $client->id,
            'package_id' => $package->id,
            'package_version_id' => $packageVersion ? $packageVersion->id : null,
            'name' => strip_tags($data['name'] ?? 'Novo Projeto'),
            'description' => $fullDescription,
            'agreed_package_name' => strtoupper($package->slug),
            'agreed_package_version' => $packageVersion ? $packageVersion->version_number : 1,
            'agreed_base_value' => $baseValue,
            'agreed_discount_value' => 0.00,
            'agreed_final_value' => $baseValue,
            'guildabyte_fee_percent' => 20.00,
            'guildabyte_fee_value' => $baseValue * 0.20,
            'team_net_value' => $baseValue * 0.80,
            'status' => 'received',
            'financial_status' => 'pending',
        ]);
    }
}
