<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes - Masjid Al-Hakim Transparansi Kas
|--------------------------------------------------------------------------
*/

Route::get('/transactions', [TransactionController::class, 'index'])->name('api.transactions.index');
Route::post('/transactions', [TransactionController::class, 'store'])->name('api.transactions.store');
