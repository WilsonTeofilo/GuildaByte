<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria ou atualiza o perfil de administrador principal (Root)
        User::updateOrCreate(
            ['email' => 'admin@guildabyte.com'],
            [
                'name' => 'Admin GuildaByte',
                'password' => Hash::make('guilda2026'),
                'user_type' => 'admin', 
                // Assumindo que você tem um enum ou campo string para role
            ]
        );
        
        $this->command->info('Admin criado! Email: admin@guildabyte.com | Senha: guilda2026');
    }
}
