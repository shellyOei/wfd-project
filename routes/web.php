<?php

use App\Http\Controllers\Admin\DayAvailableController;
use App\Http\Controllers\Admin\PracticeScheduleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
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

    // form.blade.php
    Route::get('/doctor/{doctor}/{patient}/book', [BookingController::class, 'showBookingForm'])->name('booking.show');
    Route::get('/doctor/{doctor}/select-patient', [BookingController::class, 'selectPatient'])->name('booking.selectPatient');
    Route::post('/book/store', [BookingController::class, 'store'])->name('booking.store');
    
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
Route::get('/', function () {
    return view('user.profile.index');
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


        // Route::get('/users', [UserController::class, 'users'])->name('users');
        // Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        // Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        // Route::delete('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.destroy');
        // Route::put('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        // Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

        // Route::get('/manage', [AdminController::class, 'manageAdmins'])->name('manage');
        // Route::get('/manage/store', [AdminController::class, 'manageAdmins'])->name('manage.store');
        // Route::delete('/manage/{admin}/deactivate', [AdminController::class, 'deactivate'])->name('manage.destroy');
        // Route::put('/manage/{admin}/activate', [AdminController::class, 'activate'])->name('manage.activate');
        // Route::delete('/manage/{admin}', [AdminController::class, 'destroy'])->name('manage.destroy');
        // Route::get('/doctors/search', [AdminController::class, 'manageAdmins'])->name('doctors.search');

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
