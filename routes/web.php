<?php

use App\Http\Controllers\Admin\DayAvailableController;
use App\Http\Controllers\Admin\PracticeScheduleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

// user login
Route::get('/login', [LoginController::class, 'showUser'])->name('login');
Route::post('/login', [LoginController::class, 'loginAsUser'])->name('login.post');
// user registration
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.index');
Route::post('/register', [RegisterController::class, 'registerUser'])->name('register.post');


Route::middleware('user')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

    // patient registration
    Route::post('/register-patient', [PatientController::class, 'registerPatient'])->name('register.patient.post');
    Route::get('/register-patient', [PatientController::class, 'showPatientRegistrationForm'])->name('register.patient');

    // form.blade.php
    Route::get('/doctor/{doctor}/{patient}/book', [BookingController::class, 'showBookingForm'])->name('booking.show');
    Route::get('/doctor/{doctor}/select-patient', [BookingController::class, 'selectPatient'])->name('booking.selectPatient');
});

// admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showAdmin'])->name('login');
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

        // Doctor routes
        // Doctors Management
        Route::resource('doctors', DoctorController::class);


        // Day Availables (Master Availability)
        Route::resource('day-availables', DayAvailableController::class);

        // Practice Schedules (Generated Slots & Reservations View)
        Route::get('practice-schedules', [PracticeScheduleController::class, 'index'])->name('practice-schedules.index');
        // Route::get('practice-schedules/generate', [PracticeScheduleController::class, 'createGenerate'])->name('practice-schedules.generate.create');
        // Route::post('practice-schedules/generate', [PracticeScheduleController::class, 'storeGenerate'])->name('practice-schedules.generate.store');
        Route::delete('practice-schedules/{practiceSchedule}', [PracticeScheduleController::class, 'destroy'])->name('practice-schedules.destroy');

        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});

// ga harus login bisa liat
// filter.blade.php
Route::get('/doctors/filter', [DoctorController::class, 'showSpecializations'])->name('doctors.filter');
Route::get('/doctor-suggestions', [DoctorController::class, 'getDoctorSuggestions'])->name('doctor_suggestions');


// list.blade.php
Route::get('/specializations/{specialization}/doctors', [DoctorController::class, 'doctorsBySpecialization'])->name('doctors.by_specialization');
Route::get('/doctors', [DoctorController::class, 'listDoctor'])->name('doctors.listDoctors');
Route::get('/doctors-search', [DoctorController::class, 'searchDoctorsAjax'])->name('doctors.search');

// detail.blade.php
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
