<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ScheduleService;
use App\Repositories\Eloquent\ScheduleRepository;

class SchedulePublicController extends Controller
{
    
    public function show($token){
        $user = User::where('schedule_token', $token)->firstOrFail();

        $scheduleService = new ScheduleService(new ScheduleRepository());
        $schedules = $scheduleService->getSchedulesByUserId($user->id);

        return Inertia::render('appointments/PublicSchedule', [
            'user' => $user,
            'schedules' => $schedules
        ]);
    }

}