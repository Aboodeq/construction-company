<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.test-layout');
})->name('dashboard');
