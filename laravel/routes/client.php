<?php

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\OrderWizardController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\ProjectController;
use Illuminate\Support\Facades\Route;

// Prefixo e middleware já aplicados em web.php: prefix('painel'), name('client.'), auth, role:client

// Dashboard
Route::get('/', [ClientController::class, 'index'])->name('dashboard');

// Novo Pedido (Wizard)
Route::get('/novo-pedido', [OrderWizardController::class, 'create'])->name('wizard');
Route::post('/novo-pedido', [OrderWizardController::class, 'store'])->name('wizard.store');

// Projetos (Route Model Binding automático via {project})
Route::get('/projetos', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projetos/{project}', [ProjectController::class, 'show'])->name('projects.show');

// Aceite Digital de Proposta (POST — imutável, IP+UA capturado no servidor)
Route::post('/projetos/{project}/aceitar', [ProfileController::class, 'acceptProposal'])->name('projects.accept');

// Perfil + LGPD
Route::get('/perfil', [ProfileController::class, 'show'])->name('profile');
Route::post('/perfil', [ProfileController::class, 'update'])->name('profile.update');
