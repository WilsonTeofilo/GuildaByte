<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class RegisterClient
{
    public static function run(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => strip_tags($data['name']),
                'email' => strtolower(trim($data['email'])),
                'password' => $data['password'], // cast 'hashed' no User model faz o Hash::make automaticamente
                'user_type' => 'client',
            ]);

            $user->clientProfile()->create([
                'phone' => $data['phone'] ?? null,
                'business_type' => $data['business_type'] ?? null,
                'business_name' => strip_tags($data['business_name'] ?? ''),
            ]);

            if (! empty($data['business_name'])) {
                $user->clientBusinesses()->create([
                    'name' => strip_tags($data['business_name']),
                ]);
            }

            return $user;
        });
    }
}
