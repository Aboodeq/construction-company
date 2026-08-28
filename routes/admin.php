<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectImageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceImageController;

Route::get('/', function () {
    return view('admin.test-layout');
})->name('dashboard');

Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/create', [ServiceController::class, 'create'])->name('create');
    Route::post('/', [ServiceController::class, 'store'])->name('store');
    Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
    Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
    Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
    Route::patch('/{service}/toggle-featured', [ServiceController::class, 'toggleFeatured'])->name('toggle-featured');
    Route::patch('/{service}/toggle-published', [ServiceController::class, 'togglePublished'])->name('toggle-published');
    Route::delete('/{service}/images/{image}', [ServiceImageController::class, 'destroy'])->name('images.destroy');
});

Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/create', [ProjectController::class, 'create'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
    Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
    Route::patch('/{project}/toggle-featured', [ProjectController::class, 'toggleFeatured'])->name('toggle-featured');
    Route::patch('/{project}/toggle-published', [ProjectController::class, 'togglePublished'])->name('toggle-published');
    Route::delete('/{project}/images/{image}', [ProjectImageController::class, 'destroy'])->name('images.destroy');
});

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
