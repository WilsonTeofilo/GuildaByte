<?php

namespace App\Actions\Client;

use App\Models\ClientProfile;
use Illuminate\Http\Request;

/**
 * Atualiza o perfil do cliente.
 * Backend sempre re-valida e sanitiza — NUNCA confia no front.
 */
final class UpdateProfileAction
{
    public static function run(Request $request): ClientProfile
    {
        $data = $request->validate([
            'phone'               => ['nullable', 'string', 'max:20'],
            'business_type'       => ['nullable', 'string', 'max:100'],
            'business_name'       => ['nullable', 'string', 'max:100'],
            'instagram'           => ['nullable', 'string', 'max:100'],
            'website'             => ['nullable', 'url', 'max:255'],
            'marketing_email'     => ['nullable', 'boolean'],
            'marketing_whatsapp'  => ['nullable', 'boolean'],
            'delete_account'      => ['nullable', 'boolean'],
        ]);

        $profile = $request->user()->clientProfile ?? $request->user()->clientProfile()->create([
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
