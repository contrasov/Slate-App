<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\User\LoginUserController;
use App\Http\Controllers\Patients\PatientsController;
use App\Http\Controllers\Form\FormController;
use App\Http\Controllers\Appointments\ScheduleController;
use App\Http\Controllers\Appointments\SchedulePublicController;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('schedule-public/{token}', [SchedulePublicController::class, 'show'])
    ->name('schedule.public');

Route::middleware(['auth'])->group(function(){
    Route::get('schedule', [ScheduleController::class, 'show'])
        ->name('schedule');

    Route::delete('schedule-public/{id}', [ScheduleController::class, 'destroy'])
        ->name('schedule.destroy');
    
    Route::get('patients', [PatientsController::class, 'create'])
        ->name('patients');
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
