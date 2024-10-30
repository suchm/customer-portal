<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileImageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ThreadController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/pricing', function () {
    return Inertia::render('Pricing');
})->name('pricing');

// Authenticated
Route::middleware('auth')->group(function () {

    // Prefix all routes with "my-account"
    Route::prefix('my-account')->group(function () {

        // My Account
        Route::get('/', function () {
            return Inertia::render('MyAccount/Index');
        })->name('my-account');

        // Subscriptions
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');

        // Chats
        Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
        Route::get('/chats/{chat:slug}', [ChatController::class, 'show'])->name('chats.show');
        Route::get('/chats/{chat:slug}/messages', [MessageController::class, 'show'])->name('chats.messages.show');
        Route::post('/chats/{chat:slug}/messages', [MessageController::class, 'store'])->name('chats.messages.store');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Profile Images
        Route::post('/profile_image', [ProfileImageController::class, 'uploadImage'])->name('profile-image.upload');
        Route::get('/profile_image/{filename}', [ProfileImageController::class, 'getProfileImage'])->name('profile-image.get'); // Required for images stored to local disk
        Route::delete('/profile_image', [ProfileImageController::class, 'destroy'])->name('profile-image.destroy');

        // TO DO
        Route::get('/orders', function () {
            return Inertia::render('Orders/Index');
        })->name('orders');
        Route::get('/help', function () {
            return Inertia::render('Help/index');
        })->name('help');
    });
});

require __DIR__.'/auth.php';
