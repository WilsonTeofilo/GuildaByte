<?php

namespace App\Actions\Client;

use App\Mail\SendOtpMail;
use App\Models\OtpToken;
use App\Models\SecurityEvent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Troca de senha via ciclo OTP (3 minutos, burn-after-use).
 * Passo 1: Envia código de 6 dígitos para o e-mail do usuário.
 * Passo 2: Valida o código e efetiva a troca.
 */
final class ChangePasswordAction
{
    /**
     * Passo 1: Dispara OTP para o e-mail do usuário.
     * Reutiliza a infra de OTP existente (mesma tabela otp_tokens).
     */
    public static function sendOtp(User $user): void
    {
        // Rate limit: 1 OTP por minuto por e-mail
        $recent = OtpToken::where('email', $user->email)
            ->where('created_at', '>=', now()->subMinute())
            ->first();

        if ($recent) {
            throw ValidationException::withMessages([
                'otp' => 'Aguarde 1 minuto antes de solicitar um novo código.',
            ]);
        }

        // Limpa tokens anteriores do mesmo e-mail
        OtpToken::where('email', $user->email)->delete();

        // Gera código de 6 dígitos, guarda hash
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        OtpToken::create([
            'email'      => $user->email,
            'code'       => Hash::make($code),
            'expires_at' => now()->addMinutes(3),
        ]);

        Mail::to($user->email)->send(new SendOtpMail($code, 3));
    }

    /**
     * Passo 2: Valida OTP e efetiva a nova senha.
     * Token é incinerado independente do resultado (burn-after-reading).
     */
    public static function confirm(User $user, string $code, string $newPassword, string $ip): void
    {
        $token = OtpToken::where('email', $user->email)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        // Incinera antes de validar (segurança contra timing attacks)
        if ($token) {
            $token->delete();
        }

        if (! $token || ! Hash::check($code, $token->code)) {
            SecurityEvent::create([
                'user_id'    => $user->id,
                'event_type' => 'failed_password_change',
                'ip_address' => $ip,
            ]);
            throw ValidationException::withMessages([
                'otp_code' => 'Código inválido ou expirado. Solicite um novo.',
            ]);
        }

        // Nova senha não pode ser idêntica à atual
        if (Hash::check($newPassword, $user->password)) {
            throw ValidationException::withMessages([
                'new_password' => 'A nova senha não pode ser igual à senha atual.',
            ]);
        }

        $user->update(['password' => Hash::make($newPassword)]);

        SecurityEvent::create([
            'user_id'    => $user->id,
            'event_type' => 'password_changed',
            'ip_address' => $ip,
        ]);
    }
}
