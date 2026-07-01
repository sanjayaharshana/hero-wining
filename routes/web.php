<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RaffleEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RaffleEntryController::class, 'index'])->name('home');
Route::post('/raffle-entries', [RaffleEntryController::class, 'store'])->name('raffle-entries.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit')->middleware('throttle:5,1');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/', [AdminController::class, 'index'])->name('entries')->middleware('admin.auth');
});
