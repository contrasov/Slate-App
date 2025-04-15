<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\User\RegisterUserController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/* Rota para Usuário(User) */
Route::get('/user/register', [RegisterUserController::class, 'create'])->name('user.register');
Route::post('/user/register', [RegisterUserController::class, 'register'])->name('user.register.store');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
