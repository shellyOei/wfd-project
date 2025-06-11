<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// user login
Route::get('/login', [LoginController::class, 'showUserLogin'])->name('login');
Route::post('/login', [LoginController::class, 'loginAsUser'])->name('login.post');
// user registration
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.index');
Route::post('/register', [RegisterController::class, 'registerUser'])->name('register.post');

Route::middleware(['user'])->group(function () {
    // protected routes for user
    Route::get('/dashboard', [AdminController::class, 'index'])->name('user.dashboard');
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

        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});

// Route::get('/filterdokter', [BookingController::class, 'filterDokter'])->name('filterdokter');
Route::get('/listdokter', [BookingController::class, 'listDokter'])->name('listdokter');
Route::get('/detaildokter', [BookingController::class, 'detailDokter'])->name('detaildokter');
Route::get('/booking', [BookingController::class, 'booking'])->name('appointment.booking');
Route::get('/uploadfile', [BookingController::class, 'uploadFile'])->name('uploadfile');



// Route to the page where users can pick a specialization
Route::get('/doctors/filter', [DokterController::class, 'showSpecializations'])->name('doctors.filter');

// Route for the main list of all doctors
Route::get('/doctors', [DokterController::class, 'index'])->name('doctors.index');

// Route for the list of doctors filtered by a specialization
Route::get('/specializations/{specialization}/doctors', [DokterController::class, 'doctorsBySpecialization'])->name('doctors.by_specialization');

// Route for a single doctor's detail page
Route::get('/doctors/{doctor}', [DokterController::class, 'show'])->name('doctors.show');