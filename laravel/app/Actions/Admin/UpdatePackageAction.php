<?php

namespace App\Actions\Admin;

use App\Models\Package;
use App\Models\PackageVersion;

class UpdatePackageAction
{
    public function execute(Package $package, array $data): Package
    {
        $package->update([
            'name' => $data['name'],
        ]);

        $currentVersion = $package->currentVersion;

        // Verifica se houve mudança em preços ou features.
        // Se houver, desativa a versão atual e cria uma nova.
        if ($currentVersion->base_price != $data['base_price'] || $currentVersion->features !== $data['features']) {
            $currentVersion->update(['is_current' => false]);

            PackageVersion::create([
                'package_id' => $package->id,
                'version_number' => $currentVersion->version_number + 1,
                'base_price' => $data['base_price'],
                'features' => $data['features'],
                'is_current' => true,
            ]);
        }

        return $package;
    }
}
