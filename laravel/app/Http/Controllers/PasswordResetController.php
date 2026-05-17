<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * PasswordResetController
 * Usa o sistema nativo do Laravel (password_reset_tokens).
 * Nunca confia no front — o token é verificado pelo backend.
 */
class PasswordResetController extends Controller
{
    /** Exibe o formulário de "Esqueci a senha" */
    public function showForgot(): View
    {
        return view('auth.forgot-password');
    }

    /** Envia o link de reset por e-mail */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /** Exibe o formulário de nova senha */
    public function showReset(string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /** Redefine a senha (backend revalida token + complexidade) */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email:rfc', 'max:255'],
            'password' => ['required', 'min:8', 'confirmed',
                           \Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()->numbers()],
        ], [
            'password.min'      => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed'=> 'As senhas não conferem.',
            'password.mixed'    => 'A senha deve ter maiúsculas e minúsculas.',
            'password.numbers'  => 'A senha deve ter pelo menos um número.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Senha redefinida com sucesso! Faça login.')
            : back()->withErrors(['email' => __($status)]);
    }
}
