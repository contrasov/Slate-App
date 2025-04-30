<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginUserController;
use App\Http\Controllers\User\RegisterUserController;

Route::get('register', [RegisterUserController::class, 'create'])
    ->name('user.register');

Route::post('register', [RegisterUserController::class, 'register'])
    ->name('user.register.store');

Route::get('login', [LoginUserController::class, 'create'])
    ->name('login');
        
Route::post('login', [LoginUserController::class, 'login'])
    ->name('login.submit');