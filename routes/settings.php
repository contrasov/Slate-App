<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\SettingsUserController;



Route::middleware('auth')->group(function(){
    Route::get('settings/profile', [SettingsUserController::class, 'profile'])->name('profile.edit');

    Route::get('settings/password', [SettingsUserController::class, 'password'])->name('password.edit');

    Route::get('settings/appearance', [SettingsUserController::class, 'appearance'])->name('appearance.edit');
});