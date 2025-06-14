<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


// Welcome page - public access
Route::get('/', function () {
    return view('welcome');
})->name('welcome');



// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

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
;

    // Reply routes
    Route::post('/topic/{topic}/reply', [ReplyController::class, 'store'])->name('reply.store');




    // User dashboard routes
    Route::prefix('dashboard')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'dashboard'])->name('dashboard');

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
