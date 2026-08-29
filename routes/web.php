<?php

use App\Http\Controllers\ChatWidgetController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('chat')->name('chat.')->middleware('throttle:30,1')->group(function () {
    Route::post('/start', [ChatWidgetController::class, 'start'])->name('start');
    Route::post('/{conversation}/send', [ChatWidgetController::class, 'send'])->name('send');
    Route::get('/{conversation}/poll', [ChatWidgetController::class, 'poll'])->name('poll');
});

Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/auth.php';
