<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ScheduleService;

class SettingsUserController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function profile(Request $request)
    {
        $userSchedules = $this->scheduleService->getAllSchedules();
        return Inertia::render('settings/Profile', [
            'schedules' => $userSchedules,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^\S+\s+\S+.*$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'public_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'specialty' => ['required', 'string', 'max:120'],
            'timezone' => ['required', 'timezone'],
        ], [
            'name.regex' => 'Informe seu nome e sobrenome.',
        ]);

        $request->user()->update($validated);

        return back()->with('success', 'Perfil atualizado.');
    }

    public function password(Request $request){
        return Inertia::render('settings/Password');
    }

    public function appearance(Request $request){
        return Inertia::render('settings/Appearance');
    }
}
