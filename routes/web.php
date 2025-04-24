<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\User\RegisterUserController;
use App\Http\Controllers\User\LoginUserController;
use App\Http\Controllers\Patients\PatientsController;
use App\Http\Controllers\Form\FormController;
use App\Http\Controllers\Appointments\ScheduleController;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Rota agendamento
Route::get('schedule', [ScheduleController::class, 'show'])
    ->middleware(['auth'])
    ->name('schedule');

 Route::get('schedule-public', [ScheduleController::class, 'schedule'])
    ->name('schedule.create');

// Rota com auth
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('patients', [PatientsController::class, 'create'])
    ->middleware(['auth'])
    ->name('patients');

Route::get('forms', [FormController::class, 'create'])
    ->middleware(['auth'])
    ->name('forms');

Route::post('logout', [LoginUserController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');

// Rotas de Login/Registro
Route::get('register', 
    [RegisterUserController::class, 'create'])
    ->name('user.register');

Route::post('register', 
    [RegisterUserController::class, 'register'])
    ->name('user.register.store');

Route::get('login', 
    [LoginUserController::class, 'create'])
    ->name('login');
    
Route::post('login', 
    [LoginUserController::class, 'login'])
    ->name('login.submit');

Route::post('work-time', [ScheduleController::class, 'store'])
    ->middleware(['auth'])
    ->name('work-time.store');

require __DIR__.'/settings.php';
