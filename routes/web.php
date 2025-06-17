<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Welcome page - public access
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Debug route (remove in production)
Route::get('/debug/user', function () {
    if (!Auth::check()) {
        return 'Not logged in';
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();
    return [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'isAdmin' => $user->isAdmin(),
        'created_at' => $user->created_at,
    ];
})->middleware('auth');


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
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');

        // User profile routes
    Route::get('/profile/{user}', [UserController::class, 'showProfile'])->name('user.profile');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.show'); // Current user's profile
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Topic routes - all require auth
    Route::get('/topic/{topic}', [TopicController::class, 'show'])->name('topic.show');
    Route::get('/create-topic', [TopicController::class, 'create'])->name('topic.create');
    Route::post('/topics', [TopicController::class, 'store'])->name('topic.store');
    Route::get('/topic/{topic}/edit', [TopicController::class, 'edit'])->name('topic.edit');
    Route::put('/topic/{topic}', [TopicController::class, 'update'])->name('topic.update');
    Route::delete('/topic/{topic}', [TopicController::class, 'destroy'])->name('topic.destroy');
    // Add these routes to your existing routes
    Route::delete('/topics/{topic}/remove-image', [TopicController::class, 'removeImage'])->name('topic.remove-image');
    Route::delete('/replies/{reply}/remove-image', [ReplyController::class, 'removeImage'])->name('reply.remove-image');

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

    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('profile');
        Route::post('/password', [SettingsController::class, 'updatePassword'])->name('password');
        Route::post('/profile-picture', [SettingsController::class, 'updateProfilePicture'])->name('profile-picture');
        Route::delete('/profile-picture', [SettingsController::class, 'removeProfilePicture'])->name('remove-profile-picture');
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
