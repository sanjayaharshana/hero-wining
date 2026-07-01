<?php

use App\Http\Controllers\RaffleEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RaffleEntryController::class, 'index'])->name('home');
Route::post('/raffle-entries', [RaffleEntryController::class, 'store'])->name('raffle-entries.store');
