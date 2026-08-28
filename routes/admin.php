<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectImageController;
use App\Http\Controllers\Admin\QuoteRequestController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceImageController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;

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

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogPostController::class, 'index'])->name('index');
    Route::get('/create', [BlogPostController::class, 'create'])->name('create');
    Route::post('/', [BlogPostController::class, 'store'])->name('store');
    Route::get('/{blogPost}/edit', [BlogPostController::class, 'edit'])->name('edit');
    Route::put('/{blogPost}', [BlogPostController::class, 'update'])->name('update');
    Route::delete('/{blogPost}', [BlogPostController::class, 'destroy'])->name('destroy');
    Route::patch('/{blogPost}/toggle-published', [BlogPostController::class, 'togglePublished'])->name('toggle-published');
});

Route::prefix('testimonials')->name('testimonials.')->group(function () {
    Route::get('/', [TestimonialController::class, 'index'])->name('index');
    Route::get('/create', [TestimonialController::class, 'create'])->name('create');
    Route::post('/', [TestimonialController::class, 'store'])->name('store');
    Route::get('/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('edit');
    Route::put('/{testimonial}', [TestimonialController::class, 'update'])->name('update');
    Route::delete('/{testimonial}', [TestimonialController::class, 'destroy'])->name('destroy');
    Route::patch('/{testimonial}/toggle-featured', [TestimonialController::class, 'toggleFeatured'])->name('toggle-featured');
    Route::patch('/{testimonial}/toggle-published', [TestimonialController::class, 'togglePublished'])->name('toggle-published');
});

Route::prefix('faqs')->name('faqs.')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('index');
    Route::get('/create', [FaqController::class, 'create'])->name('create');
    Route::post('/', [FaqController::class, 'store'])->name('store');
    Route::get('/{faq}/edit', [FaqController::class, 'edit'])->name('edit');
    Route::put('/{faq}', [FaqController::class, 'update'])->name('update');
    Route::delete('/{faq}', [FaqController::class, 'destroy'])->name('destroy');
    Route::patch('/{faq}/toggle-published', [FaqController::class, 'togglePublished'])->name('toggle-published');
});

Route::prefix('team')->name('team.')->group(function () {
    Route::get('/', [TeamMemberController::class, 'index'])->name('index');
    Route::get('/create', [TeamMemberController::class, 'create'])->name('create');
    Route::post('/', [TeamMemberController::class, 'store'])->name('store');
    Route::get('/{teamMember}/edit', [TeamMemberController::class, 'edit'])->name('edit');
    Route::put('/{teamMember}', [TeamMemberController::class, 'update'])->name('update');
    Route::delete('/{teamMember}', [TeamMemberController::class, 'destroy'])->name('destroy');
    Route::patch('/{teamMember}/toggle-published', [TeamMemberController::class, 'togglePublished'])->name('toggle-published');
});

Route::prefix('quote-requests')->name('quote-requests.')->group(function () {
    Route::get('/', [QuoteRequestController::class, 'index'])->name('index');
    Route::get('/export', [QuoteRequestController::class, 'export'])->name('export');
    Route::get('/{quoteRequest}', [QuoteRequestController::class, 'show'])->name('show');
    Route::patch('/{quoteRequest}/status', [QuoteRequestController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{quoteRequest}', [QuoteRequestController::class, 'destroy'])->name('destroy');
});

Route::prefix('bookings')->name('bookings.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::get('/{booking}/edit', [BookingController::class, 'edit'])->name('edit');
    Route::put('/{booking}', [BookingController::class, 'update'])->name('update');
    Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('destroy');
});

Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
    Route::get('/', [ContactMessageController::class, 'index'])->name('index');
    Route::get('/{contactMessage}', [ContactMessageController::class, 'show'])->name('show');
    Route::patch('/{contactMessage}/toggle-replied', [ContactMessageController::class, 'toggleReplied'])->name('toggle-replied');
    Route::delete('/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('destroy');
});

Route::prefix('chats')->name('chats.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
    Route::post('/{conversation}/reply', [ChatController::class, 'reply'])->name('reply');
    Route::get('/{conversation}/poll', [ChatController::class, 'poll'])->name('poll');
    Route::patch('/{conversation}/toggle-status', [ChatController::class, 'toggleStatus'])->name('toggle-status');
    Route::delete('/{conversation}', [ChatController::class, 'destroy'])->name('destroy');
});

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::patch('/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('toggle-active');
});

Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
});

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
