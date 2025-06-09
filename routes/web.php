<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// user
Route::get('/login', [LoginController::class, 'showUserLogin'])->name('user.login');
Route::post('/login', [LoginController::class, 'loginAsUser'])->name('user.login.post');
Route::middleware(['user'])->group(function () {
    // protected routes for user
    // Route::get('/dashboard', [AdminController::class, 'index'])->name('user.dashboard');
});

// admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showAdminLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'loginAsAdmin'])->name('login.post');
    Route::middleware(['admin'])->group(function () {
        // protected routes for admin
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});
