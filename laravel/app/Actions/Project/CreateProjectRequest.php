<?php

namespace App\Actions\Project;

use App\Data\ProjectData;
use App\Models\Project;
use App\Models\PackageVersion;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;

/**
 * Cria um Pedido de Projeto com Snapshot Financeiro imutável.
 *
 * REGRA DE OURO: agreed_final_value NUNCA é recalculado após a criação.
 * Promoções futuras não afetam projetos já abertos.
 */
final class CreateProjectRequest
{
    public static function run(ProjectData $data): Project
    {
        return DB::transaction(function () use ($data) {
            $version   = PackageVersion::currentFor($data->packageId);
            $promotion = Promotion::activeFor($data->packageId);

            $baseValue  = $version->base_price;
            $discount   = $promotion ? $promotion->calculateDiscount($baseValue) : 0;
            $finalValue = $baseValue - $discount;
            $feePercent = 20.00;
            $feeValue   = round($finalValue * ($feePercent / 100), 2);

            return Project::create([
                // Relacionamentos (FKs — pode mudar, o snapshot não)
                'client_id'          => $data->clientId,
                'package_id'         => $data->packageId,
                'package_version_id' => $version->id,
                'promotion_id'       => $promotion?->id,

                // === SNAPSHOT FINANCEIRO IMUTÁVEL ===
                'agreed_package_name'    => $version->package->name,
                'agreed_package_version' => $version->version_number,
                'agreed_base_value'      => $baseValue,
                'agreed_discount_value'  => $discount,
                'agreed_final_value'     => $finalValue,
                'guildabyte_fee_percent' => $feePercent,
                'guildabyte_fee_value'   => $feeValue,
                'team_net_value'         => $finalValue - $feeValue,

                // Detalhes do pedido
                'status'          => 'received',
            ]);
        });
    }
}
