<?php

namespace App\Actions\Auth;

use App\Mail\SendOtpMail;
use App\Models\OtpToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class SendOtpAction
{
    /**
     * @throws ValidationException se já houver OTP recente
     */
    public static function run(string $email): void
    {
        // Rate Limiting: previne spam (ex: 1 por minuto por email)
        // Isso normalmente é feito em RateLimiter ou no controller,
        // mas vamos adicionar uma checagem básica no banco também
        $recent = OtpToken::where('email', $email)
            ->where('created_at', '>=', now()->subMinutes(1))
            ->first();

        if ($recent) {
            throw ValidationException::withMessages([
                'email' => 'Aguarde 1 minuto antes de solicitar um novo código.',
            ]);
        }

        // Limpa tokens antigos não usados desse email
        OtpToken::where('email', $email)->delete();

        // Gera código de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Salva hash
        OtpToken::create([
            'email' => $email,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(3),
        ]);

        // Dispara email
        Mail::to($email)->queue(new SendOtpMail($code, 3));
    }
}
