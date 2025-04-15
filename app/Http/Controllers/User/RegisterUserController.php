<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;


class RegisterUserController extends Controller
{
    protected $userService;

    /* criando um construtor pra classe */
    public function __construct(UserService $userService){  
        $this->userService = $userService;
    }

    public function create(){
        return Inertia::render('auth/Register');
    }

    /* lançando a função de registro */
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $this->userService->create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'médico',
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }
}
