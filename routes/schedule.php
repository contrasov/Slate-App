<?php

use App\Http\Controllers\Appointments\SchedulePublicController;
use App\Http\Controllers\Appointments\ScheduleController;
use App\Http\Controllers\Patients\PatientsController;
use Illuminate\Support\Facades\Route;

Route::get('schedule-public/{token}', [SchedulePublicController::class, 'show'])
    ->name('schedule.public');

Route::post('schedule-public/{token}', [SchedulePublicController::class, 'createAppointment'])
    ->name('schedule.public.create');

Route::middleware(['auth'])->group(function(){
    Route::get('schedule', [ScheduleController::class, 'show'])
        ->name('schedule');

    Route::delete('schedule-public/{id}', [ScheduleController::class, 'destroy'])
        ->name('schedule.destroy');
});