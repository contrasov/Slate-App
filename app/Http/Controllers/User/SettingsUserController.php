<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ScheduleService;
use App\Repositories\Eloquent\ScheduleRepository;

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
            'schedules' => $userSchedules // Passar as schedules para a view
        ]);
    }

    public function password(Request $request){
        return Inertia::render('settings/Password');
    }

    public function appearance(Request $request){
        return Inertia::render('settings/Appearance');
    }
}
