<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageVersion;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $start = Package::firstOrCreate(['slug' => 'start'], ['name' => 'Start', 'is_active' => true]);
        $core = Package::firstOrCreate(['slug' => 'core'], ['name' => 'Core', 'is_active' => true]);
        $custom = Package::firstOrCreate(['slug' => 'custom'], ['name' => 'Custom', 'is_active' => true]);

        PackageVersion::firstOrCreate(['package_id' => $start->id, 'version_number' => 1], [
            'base_price' => 1000.00,
            'setup_deadline_days' => 15,
            'is_current' => true,
        ]);

        PackageVersion::firstOrCreate(['package_id' => $core->id, 'version_number' => 1], [
            'base_price' => 3000.00,
            'setup_deadline_days' => 30,
            'is_current' => true,
        ]);

        PackageVersion::firstOrCreate(['package_id' => $custom->id, 'version_number' => 1], [
            'base_price' => 4000.00, // PreÃ§o base custom
            'setup_deadline_days' => 45,
            'is_current' => true,
        ]);
    }
}
