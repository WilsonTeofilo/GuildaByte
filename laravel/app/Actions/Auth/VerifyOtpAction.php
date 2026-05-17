<?php

namespace App\Actions\Auth;

use App\Models\OtpToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Verifica o OTP informado e o incinera imediatamente.
 * Burn-after-reading: token deletado seja no sucesso OU na falha.
 */
final class VerifyOtpAction
{
    /**
     * @throws ValidationException se o token for inválido ou expirado
     */
    public static function run(string $email, string $code): void
    {
        $token = OtpToken::where('email', $email)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        // Incinera independente do resultado (segurança)
        if ($token) {
            $token->delete();
        }

        if (! $token || ! Hash::check($code, $token->code)) {
            throw ValidationException::withMessages([
                'code' => 'Código inválido ou expirado. Solicite um novo.',
            ]);
        }
    }
}
