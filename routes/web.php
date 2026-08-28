<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Public Website Placeholder';
})->name('home');

Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/auth.php';
