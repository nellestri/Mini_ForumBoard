<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


// Welcome page - public access
Route::get('/', function () {
    return view('welcome');
})->name('welcome');



// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);


    // Password reset routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');


});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// All forum routes require authentication
Route::middleware('auth')->group(function () {
    // Forum index - now requires auth


    // Topic routes - all require auth
    Route::get('/topic/{topic}', [TopicController::class, 'show'])->name('topic.show');
    Route::get('/create-topic', [TopicController::class, 'create'])->name('topic.create');
    Route::post('/topics', [TopicController::class, 'store'])->name('topic.store');
    Route::get('/topic/{topic}/edit', [TopicController::class, 'edit'])->name('topic.edit');
    Route::put('/topic/{topic}', [TopicController::class, 'update'])->name('topic.update');
    Route::delete('/topic/{topic}', [TopicController::class, 'destroy'])->name('topic.destroy');
;

    // Reply routes
    Route::post('/topic/{topic}/reply', [ReplyController::class, 'store'])->name('reply.store');
    Route::get('/reply/{reply}/edit', [ReplyController::class, 'edit'])->name('reply.edit');
    Route::put('/reply/{reply}', [ReplyController::class, 'update'])->name('reply.update');
    Route::delete('/reply/{reply}', [ReplyController::class, 'destroy'])->name('reply.destroy');




    // User dashboard routes
    Route::prefix('dashboard')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/topics', [UserController::class, 'topics'])->name('topics');
        Route::get('/replies', [UserController::class, 'replies'])->name('replies');

    });



    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/toggle-role', [AdminController::class, 'toggleUserRole'])->name('users.toggle-role');
        Route::post('/topics/{topic}/toggle-pin', [AdminController::class, 'toggleTopicPin'])->name('topics.toggle-pin');
        Route::post('/topics/{topic}/toggle-lock', [AdminController::class, 'toggleTopicLock'])->name('topics.toggle-lock');
    });
});
