<?php

namespace App\Http\Controllers;

use App\Actions\Auth\RegisterClient;
use App\Actions\Auth\SendOtpAction;
use App\Actions\Auth\VerifyOtpAction;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login', ['selectedPlan' => $this->selectedPlan()]);
    }

    public function showRegister(): View
    {
        return view('auth.register', ['selectedPlan' => $this->selectedPlan()]);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'E-mail ou senha incorretos.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.dashboard');
    }

    public function register(RegisterClientRequest $request): RedirectResponse
    {
        $user = RegisterClient::run($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('client.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /** Recupera o plano selecionado da sessão (snapshot da landing page). */
    private function selectedPlan(): ?array
    {
        $key = session('selected_plan');

        return $key ? (config('landing.plans')[$key] ?? null) : null;
    }

    // --- OTP MAGIC LINK ---

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        try {
            SendOtpAction::run($request->email);
            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao enviar código.'], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code'  => 'required|string|size:6',
        ]);

        try {
            VerifyOtpAction::run($request->email, $request->code);
            
            $user = User::where('email', $request->email)->first();
            Auth::login($user);
            $request->session()->regenerate();

            return response()->json([
                'success' => true, 
                'redirect' => $user->isAdmin() ? route('admin.dashboard') : route('client.dashboard')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->validator->errors()->first('otp')], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao validar código.'], 500);
        }
    }
}
