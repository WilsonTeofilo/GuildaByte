<?php

namespace App\Http\Controllers;

use App\Actions\Auth\RegisterClient;
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
}
