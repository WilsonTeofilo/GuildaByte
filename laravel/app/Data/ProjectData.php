<?php

namespace App\Data;

/**
 * DTO para transporte de dados de projeto entre camadas.
 * Garante tipagem estrita — nenhum array mágico passando entre Controller e Action.
 */
final class ProjectData
{
    public function __construct(
        public readonly int $clientId,
        public readonly int $packageId,
        public readonly string $businessData,
        public readonly string $projectObjective,
        public readonly ?string $desiredFeatures,
        public readonly ?string $references,
        public readonly ?string $desiredDeadline,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            clientId: (int) $data['client_id'],
            packageId: (int) $data['package_id'],
            businessData: $data['business_data'],
            projectObjective: $data['project_objective'],
            desiredFeatures: $data['desired_features'] ?? null,
            references: $data['references'] ?? null,
            desiredDeadline: $data['desired_deadline'] ?? null,
        );
    }
}
