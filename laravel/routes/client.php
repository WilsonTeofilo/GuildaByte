<?php

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\OrderWizardController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\ProjectController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [ClientController::class, 'index'])->name('dashboard');

// Novo Pedido (Wizard)
Route::get('/novo-pedido', [OrderWizardController::class, 'create'])->name('wizard');
Route::post('/novo-pedido', [OrderWizardController::class, 'store'])->name('wizard.store');

// Projetos
Route::get('/projetos', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projetos/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/projetos/{project}/aceitar', [ProfileController::class, 'acceptProposal'])->name('projects.accept');

// Perfil
Route::get('/perfil', [ProfileController::class, 'show'])->name('profile');
Route::post('/perfil', [ProfileController::class, 'update'])->name('profile.update');

// Segurança — Troca de senha via OTP (3 min, burn-after-use)
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/perfil/senha/enviar-codigo', [ProfileController::class, 'sendPasswordOtp'])->name('profile.password.otp');
    Route::post('/perfil/senha/confirmar', [ProfileController::class, 'changePassword'])->name('profile.password.change');
});

Route::post('addendums/{addendum}/accept', [App\Http\Controllers\Client\ProjectController::class, 'acceptAddendum'])->name('client.addendums.accept');

// Calls
Route::get('/projetos/{project}/calls/agendar', [App\Http\Controllers\Client\CallController::class, 'create'])->name('projects.calls.create');
Route::post('/projetos/{project}/calls', [App\Http\Controllers\Client\CallController::class, 'store'])->name('projects.calls.store');
Route::get('/projetos/{project}/calls/historico', [App\Http\Controllers\Client\CallController::class, 'index'])->name('projects.calls.index');

// Chat
Route::get('/projetos/{project}/chat', [App\Http\Controllers\Client\ChatController::class, 'index'])->name('projects.chat.index');
Route::post('/projetos/{project}/chat', [App\Http\Controllers\Client\ChatController::class, 'store'])->name('projects.chat.store');
