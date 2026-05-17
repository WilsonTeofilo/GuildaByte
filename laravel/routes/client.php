<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\OrderWizardController;
use App\Http\Controllers\Client\ProjectController;

Route::middleware(['auth', 'verified'])->prefix('painel')->name('client.')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('dashboard');
    
    // Wizard de Novo Pedido
    Route::get('/novo-pedido', [OrderWizardController::class, 'create'])->name('wizard');
    Route::post('/novo-pedido', [OrderWizardController::class, 'store'])->name('wizard.store');

    // Projetos
    Route::get('/projetos', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projetos/{id}', [ProjectController::class, 'show'])->name('projects.show');
});
