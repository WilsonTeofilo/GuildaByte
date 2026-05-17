<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\OrderWizardController;

Route::middleware(['auth', 'verified'])->prefix('painel')->name('client.')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('dashboard');
    
    // Wizard de Novo Pedido
    Route::get('/novo-pedido', [OrderWizardController::class, 'create'])->name('wizard');
    Route::post('/novo-pedido', [OrderWizardController::class, 'store'])->name('wizard.store');
});
