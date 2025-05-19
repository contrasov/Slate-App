<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\User\LoginUserController;
use App\Http\Controllers\Patients\PatientsController;
use App\Http\Controllers\Form\FormController;
use App\Http\Controllers\Appointments\ScheduleController;
use App\Http\Controllers\Dashboard\DashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth'])->group(function(){
    Route::get('patients', [PatientsController::class, 'create'])
        ->name('patients');

    Route::get('patients/{id}', [PatientsController::class, 'showServicePatient'])
        ->name('patients.service');

    Route::get('dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    Route::delete('patients/{id}', [PatientsController::class, 'delete'])
        ->name('patients.delete');

    Route::post('patient/{id}/status', [ScheduleController::class, 'changeStatus'])
        ->name('patient.status');
});

/* fichas */
Route::get('forms', [FormController::class, 'create'])
    ->middleware(['auth'])
    ->name('forms');

Route::post('logout', [LoginUserController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');

Route::post('work-time', [ScheduleController::class, 'store'])
    ->middleware(['auth'])
    ->name('work-time.store');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/schedule.php';