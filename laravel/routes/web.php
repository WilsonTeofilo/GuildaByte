<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;

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
