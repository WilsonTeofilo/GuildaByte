<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

// ── Landing ────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::post('/select-plan', [LandingController::class, 'selectPlan'])->name('select-plan');

// ── Auth ───────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('throttle:login')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // OTP / Magic Link
    Route::post('/login/otp/send', [AuthController::class, 'sendOtp'])->name('otp.send');
    Route::post('/login/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');

    // Recuperação de senha
    Route::get('/esqueci-a-senha', [PasswordResetController::class, 'showForgot'])->name('password.request');
    Route::post('/esqueci-a-senha', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/redefinir-senha/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/redefinir-senha', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// ── Área do Cliente ────────────────────────────────────────────
Route::middleware(['auth', 'role:client'])
    ->prefix('painel')
    ->name('client.')
    ->group(base_path('routes/client.php'));

// ── Área do Admin ──────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,root'])
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));
