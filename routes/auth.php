<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginUserController;
use App\Http\Controllers\User\RegisterUserController;
use App\Http\Controllers\User\OnboardingController;

Route::get('register', [RegisterUserController::class, 'create'])
    ->name('user.register');

Route::post('register', [RegisterUserController::class, 'register'])
    ->name('user.register.store');

Route::middleware('auth')->group(function () {
    Route::get('onboarding', [OnboardingController::class, 'edit'])->name('onboarding.edit');
    Route::post('onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
});

Route::get('login', [LoginUserController::class, 'create'])
    ->name('login.page');
        
Route::post('login', [LoginUserController::class, 'login'])
    ->name('login.submit');
