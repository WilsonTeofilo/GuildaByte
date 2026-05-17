<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criar o Admin Root (O Chefe)
        $adminEmail = 'admin@guildabyte.com';
        $admin = User::where('email', $adminEmail)->first();
        if (!$admin) {
            $adminId = DB::table('users')->insertGetId([
                'name' => 'Wilson (Root)',
                'email' => $adminEmail,
                'password' => Hash::make('guilda2026'),
                'user_type' => 'root',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::table('employee_profiles')->insert([
                'user_id' => $adminId,
                'role_title' => 'CEO / Admin Root',
                'seniority' => 'Lead',
                'availability_status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $adminId = $admin->id;
        }

        // 2. Criar Cliente de Teste
        $clientEmail = 'cliente@teste.com';
        if (!User::where('email', $clientEmail)->exists()) {
            $clientId = DB::table('users')->insertGetId([
                'name' => 'Cliente de Teste',
                'email' => $clientEmail,
                'password' => Hash::make('cliente123'),
                'user_type' => 'client',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('client_profiles')->insert([
                'user_id' => $clientId,
                'phone' => '11999999999',
                'business_type' => 'Barbearia',
                'business_name' => 'Barbearia Teste',
                'marketing_email' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Criar os Pacotes Iniciais (Start, Core, Custom)
        if (DB::table('packages')->count() === 0) {
            $packages = [
                [
                    'slug' => 'start',
                    'name' => 'Guilda Start',
                    'base_price' => 1000.00,
                    'deadline' => 15,
                    'desc' => 'Landing + Vitrine Simples. Ideal para presença digital rápida.'
                ],
                [
                    'slug' => 'core',
                    'name' => 'Guilda Core',
                    'base_price' => 3000.00,
                    'deadline' => 75, // ~2 meses e meio
                    'desc' => 'Sistema Completo Básico. Painel, pedidos/agendamentos, status.'
                ],
                [
                    'slug' => 'custom',
                    'name' => 'Guilda Custom',
                    'base_price' => 4000.00,
                    'deadline' => 120, // ~4 meses
                    'desc' => 'Sistema Personalizado. Cores, regras e módulos exclusivos.'
                ]
            ];

            foreach ($packages as $pkg) {
                $packageId = DB::table('packages')->insertGetId([
                    'slug' => $pkg['slug'],
                    'name' => $pkg['name'],
                    'is_active' => true,
                    'is_visible' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('package_versions')->insert([
                    'package_id' => $packageId,
                    'version_number' => 1,
                    'base_price' => $pkg['base_price'],
                    'setup_deadline_days' => $pkg['deadline'],
                    'description' => $pkg['desc'],
                    'is_current' => true,
                    'valid_from' => now(),
                    'created_by' => $adminId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
