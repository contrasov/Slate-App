<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\User\RegisterUserController;
use App\Http\Controllers\User\LoginUserController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


// Rota com auth
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('profile/settings', [ProfileUserController::class, 'setting'])
    ->middleware(['auth'])
    ->name('profile.edit');

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
