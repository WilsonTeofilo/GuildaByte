<?php

// =====================================================================
// AVISO PARA PRODUÇÃO: deletar estes usuários de teste antes do deploy!
// =====================================================================
// cliente_teste@guildabyte.com / senha: cliente123
// admin_teste@guildabyte.com   / senha: admin123
// =====================================================================

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Só roda em ambiente local — proteção extra
        if (app()->isProduction()) {
            $this->command->error('🚫 DevUsersSeeder bloqueado em produção!');
            return;
        }

        // --- Cliente de Teste ---
        $client = User::firstOrCreate(
            ['email' => 'cliente@teste.com'],
            [
                'name'      => 'Cliente Teste',
                'password'  => Hash::make('cliente123'),
                'user_type' => 'client',
            ]
        );
        $client->clientProfile()->firstOrCreate(['user_id' => $client->id], [
            'business_name' => 'Empresa Teste LTDA',
            'phone'         => '11999999999',
        ]);

        // --- Admin Root de Teste ---
        User::firstOrCreate(
            ['email' => 'admin@teste.com'],
            [
                'name'      => 'Admin Root Teste',
                'password'  => Hash::make('admin123'),
                'user_type' => 'root',
            ]
        );

        $this->command->info('✅ Usuários de teste criados. LEMBRE DE DELETAR ANTES DO DEPLOY!');
    }
}
