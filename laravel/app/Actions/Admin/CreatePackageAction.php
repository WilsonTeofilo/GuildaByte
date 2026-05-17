<?php

namespace App\Actions\Admin;

use App\Models\Package;
use App\Models\PackageVersion;

class CreatePackageAction
{
    public function execute(array $data): Package
    {
        $package = Package::create([
            'slug' => $data['slug'],
            'name' => $data['name'],
            'is_active' => true,
            'is_visible' => true,
        ]);

        PackageVersion::create([
            'package_id' => $package->id,
            'version_number' => 1,
            'base_price' => $data['base_price'],
            'features' => $data['features'],
            'is_current' => true,
        ]);

        return $package;
    }
}
