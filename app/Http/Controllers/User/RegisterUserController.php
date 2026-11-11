<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\User;

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

    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^\S+\s+\S+.*$/u'],
            'email' => 'required|string|email|max:255|unique:users',
            'account_type' => 'required|in:professional,clinic',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],
        [
            'name.regex' => 'Informe seu nome e sobrenome.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.'
        ]
        );

        do {
            $token = Str::random(6);
        } while (User::where('schedule_token', $token)->exists());

        $user = $this->userService->create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'médico',
            'account_type' => $request->account_type,
            'onboarding_complete' => false,
            'password' => $request->password,
            'schedule_token' => $token
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('onboarding.edit');
    }
}
