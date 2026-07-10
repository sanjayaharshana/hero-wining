<?php

use App\Http\Controllers\Admin\WinnerDrawController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EngineQuizController;
use App\Http\Controllers\RaffleEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RaffleEntryController::class, 'index'])->name('home');
Route::post('/raffle-entries', [RaffleEntryController::class, 'store'])->name('raffle-entries.store');

Route::get('/engine/{stall}', [EngineQuizController::class, 'show'])
    ->whereIn('stall', ['01', '02', '03', '04'])
    ->name('engine.show');
Route::post('/engine/{stall}', [EngineQuizController::class, 'store'])
    ->whereIn('stall', ['01', '02', '03', '04'])
    ->name('engine.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit')->middleware('throttle:5,1');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('entries');
        Route::get('/export-csv', [AdminController::class, 'exportCsv'])->name('entries.export');
        Route::delete('/{entry}', [AdminController::class, 'destroy'])->name('entries.destroy');
        Route::get('/winner-draw', [WinnerDrawController::class, 'show'])->name('winner-draw');
        Route::post('/winner-draw/draw', [WinnerDrawController::class, 'draw'])->name('winner-draw.draw');
        Route::post('/winner-draw/reset', [WinnerDrawController::class, 'reset'])->name('winner-draw.reset');
    });
});
