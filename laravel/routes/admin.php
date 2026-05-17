<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [AdminController::class, 'index'])->name('dashboard');

// Fila de Projetos (CRUD parcial)
Route::get('/projetos', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projetos/{project}', [ProjectController::class, 'show'])->name('projects.show');

// Transição de status com validação de cadeia
Route::post('/projetos/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.status');

// Criação de proposta formal
Route::post('/projetos/{project}/proposta', [ProjectController::class, 'storeProposal'])->name('projects.proposal.store');

Route::resource('packages', App\Http\Controllers\Admin\PackageController::class)->except(['create', 'show', 'edit', 'destroy']);


Route::get('promotions', [App\Http\Controllers\Admin\PromotionController::class, 'index'])->name('promotions.index');
Route::post('promotions', [App\Http\Controllers\Admin\PromotionController::class, 'store'])->name('promotions.store');
Route::post('promotions/{promotion}/toggle', [App\Http\Controllers\Admin\PromotionController::class, 'toggle'])->name('promotions.toggle');

Route::post('projects/{project}/addendum', [App\Http\Controllers\Admin\ProjectController::class, 'storeAddendum'])->name('projects.addendum.store');

// Calls
Route::get('calls', [App\Http\Controllers\Admin\CallController::class, 'index'])->name('calls.index');
Route::post('calls/{call}/confirm', [App\Http\Controllers\Admin\CallController::class, 'confirm'])->name('calls.confirm');
Route::post('calls/{call}/reject', [App\Http\Controllers\Admin\CallController::class, 'reject'])->name('calls.reject');
Route::post('calls/{call}/start', [App\Http\Controllers\Admin\CallController::class, 'start'])->name('calls.start');
Route::get('calls/{call}/active', [App\Http\Controllers\Admin\CallController::class, 'active'])->name('calls.active');
Route::post('calls/{call}/complete', [App\Http\Controllers\Admin\CallController::class, 'complete'])->name('calls.complete');

// Slots de agenda
Route::get('calls/slots', [App\Http\Controllers\Admin\CallController::class, 'slots'])->name('calls.slots');
Route::post('calls/slots', [App\Http\Controllers\Admin\CallController::class, 'storeSlot'])->name('calls.slots.store');
Route::delete('calls/slots/{slot}', [App\Http\Controllers\Admin\CallController::class, 'destroySlot'])->name('calls.slots.destroy');

// Chat por projeto
Route::get('chat/{project}', [App\Http\Controllers\Admin\CallController::class, 'chat'])->name('chat.index');
Route::post('chat/{project}', [App\Http\Controllers\Admin\CallController::class, 'sendMessage'])->name('chat.send');

// Guilda Board (Missão 9)
Route::get('projects/{project}/board', [App\Http\Controllers\Admin\GuildaBoardController::class, 'show'])->name('board.show');
Route::post('boards/{board}/columns', [App\Http\Controllers\Admin\GuildaBoardController::class, 'storeColumn'])->name('board.columns.store');
Route::post('columns/{column}/cards', [App\Http\Controllers\Admin\GuildaBoardController::class, 'storeCard'])->name('board.cards.store');
Route::post('cards/{card}/move', [App\Http\Controllers\Admin\GuildaBoardController::class, 'moveCard'])->name('board.cards.move');
Route::post('cards/{card}/comments', [App\Http\Controllers\Admin\GuildaBoardController::class, 'storeComment'])->name('board.cards.comments.store');

