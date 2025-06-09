<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// user
Route::get('/login', [LoginController::class, 'showUserLogin'])->name('user.login');
Route::post('/login', [LoginController::class, 'loginAsUser'])->name('user.login.post');
Route::middleware(['user'])->group(function () {
    // protected routes for user
    // Route::get('/dashboard', [AdminController::class, 'index'])->name('user.dashboard');
});
Route::get('/', function () {
    return view('user.profile.index');
});


// admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showAdminLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'loginAsAdmin'])->name('login.post');
    Route::middleware(['admin'])->group(function () {
        // protected routes for admin
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Patient routes
        Route::get('/patients', [PatientController::class, 'index'])->name('patients');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
        Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
        Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
        Route::get('/patients/{patient}/medical-history', [PatientController::class, 'getMedicalHistory'])->name('patients.medical-history');


        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::put('/users/{user}', [UserController::class,'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class,'destroy'])->name('users.destroy');
        Route::delete('/users/{user}/deactivate', [UserController::class,'deactivate'])->name('users.destroy');
        Route::put('/users/{user}/activate', [UserController::class,'activate'])->name('users.activate');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});
