<?php

use App\Http\Controllers\Appointments\SchedulePublicController;
use App\Http\Controllers\Appointments\ScheduleController;
use App\Http\Controllers\Patients\PatientsController;
use App\Http\Controllers\FreeDay\FreeDayController;
use Illuminate\Support\Facades\Route;

Route::get('schedule-public/{token}', [SchedulePublicController::class, 'show'])
    ->name('schedule.public');

Route::post('schedule-public/{token}', [SchedulePublicController::class, 'createAppointment'])
    ->name('schedule.public.create');

Route::middleware(['auth'])->group(function(){
    Route::get('schedule/availability', [ScheduleController::class, 'availability'])
        ->name('schedule.availability');

    Route::get('schedule', [ScheduleController::class, 'show'])
        ->name('schedule');

    Route::put('schedule/availability', [ScheduleController::class, 'saveAvailability'])
        ->name('schedule.availability.save');

    Route::post('schedule/closed-days', [FreeDayController::class, 'store'])
        ->name('schedule.closed-days.store');

    Route::delete('schedule/closed-days/{freeDay}', [FreeDayController::class, 'destroy'])
        ->name('schedule.closed-days.destroy');

});
