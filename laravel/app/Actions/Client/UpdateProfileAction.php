<?php

namespace App\Actions\Client;

use App\Models\ClientProfile;
use App\Models\User;

/**
 * Atualiza o perfil do cliente.
 * Recebe dados puros, desacoplado do Request.
 */
final class UpdateProfileAction
{
    public static function run(User $user, array $data): ClientProfile
    {
        $profile = $user->clientProfile ?? $user->clientProfile()->create([
            'phone' => null,
        ]);

        // Sanitização server-side (NUNCA confiar no front)
        $profile->update([
            'phone'              => preg_replace('/[^0-9+\-()\s]/', '', $data['phone'] ?? ''),
            'business_type'      => strip_tags($data['business_type'] ?? ''),
            'business_name'      => strip_tags($data['business_name'] ?? ''),
            'instagram'          => strip_tags($data['instagram'] ?? ''),
            'website'            => filter_var($data['website'] ?? '', FILTER_SANITIZE_URL),
            'marketing_email'    => (bool) ($data['marketing_email'] ?? false),
            'marketing_whatsapp' => (bool) ($data['marketing_whatsapp'] ?? false),
        ]);

        return $profile->fresh();
    }
}
