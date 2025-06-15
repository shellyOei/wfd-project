<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// user login
Route::get('/login', [LoginController::class, 'showUserLogin'])->name('login');
Route::post('/login', [LoginController::class, 'loginAsUser'])->name('login.post');
// user registration
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.index');
Route::post('/register', [RegisterController::class, 'registerUser'])->name('register.post');

Route::middleware(['user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

    // patient registration
    Route::post('/register-patient', [PatientController::class, 'registerPatient'])->name('register.patient.post');
    Route::get('/register-patient', [PatientController::class, 'showPatientRegistrationForm'])->name('register.patient');
    Route::post('/link-patient', [ProfileController::class, 'linkPatient'])->name('link-patient.post');
    Route::get('/link-patient', [PatientController::class, 'showExistingPatientRegistrationForm'])->name('link-patient');


    // Profile Nav
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Edit Akun
    Route::get('/account/edit', [UserController::class, 'showEditAccount'])->name('update');
    Route::put('/account/edit', [UserController::class, 'updateSelf'])->name('update.post');
    Route::put('/account/deactivate', [UserController::class, 'deactivateSelf'])->name('deactivate');
    Route::delete('/account/delete', [UserController::class, 'destroySelf'])->name('delete');

    // List patient yang terkonek
    Route::get('/patients', [ProfileController::class, 'showEditPatient'])->name('patients');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'showEditForm'])->name('patients.edit.form');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{id}/disconnect', [ProfileController::class, 'disconnect'])->name('patients.disconnect');

    // Mini History
    Route::get('/mini-history', [ProfileController::class, 'miniHistory'])->name('miniHistory');
    Route::get('/mini-history/data/{patientId}', [PatientController::class, 'getAppointments'])->name('miniHistory.data');
});
Route::post('/logout', [LoginController::class, 'logoutAsUser'])->name('logout');

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


        Route::get('/users', [UserController::class, 'users'])->name('users');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::delete('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.destroy');
        Route::put('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('/manage', [AdminController::class, 'manageAdmins'])->name('manage');
        Route::get('/manage/store', [AdminController::class, 'manageAdmins'])->name('manage.store');
        Route::delete('/manage/{admin}/deactivate', [AdminController::class, 'deactivate'])->name('manage.destroy');
        Route::put('/manage/{admin}/activate', [AdminController::class, 'activate'])->name('manage.activate');
        Route::delete('/manage/{admin}', [AdminController::class, 'destroy'])->name('manage.destroy');
        Route::get('/doctors/search', [AdminController::class, 'manageAdmins'])->name('doctors.search');

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