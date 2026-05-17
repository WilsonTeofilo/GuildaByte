<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;

Route::get('/', [ClientController::class, 'index'])->name('dashboard');
