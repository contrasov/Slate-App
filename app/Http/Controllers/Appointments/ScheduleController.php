<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Services\ScheduleService;
use App\Repositories\Eloquent\ScheduleRepository;


class ScheduleController extends Controller
{
    public function show(Request $request){
        return Inertia::render('appointments/Schedule');
    }

    public function schedule(Request $rquest){
        return Inertia::render('appointments/PublicSchedule');
    }

    public function store(Request $request)
    {
        $request->validate([
            'work_times' => 'required|array',
            'work_times.*.weekday' => 'required|integer|min:0|max:6',
            'work_times.*.start_time' => 'required|date_format:H:i',
            'work_times.*.end_time' => 'required|date_format:H:i',
            'duration' => 'required|date_format:H:i'
        ]);

        $scheduleService = new ScheduleService(new ScheduleRepository());
        
        foreach ($request->work_times as $workTime) {
            $data = [
                'user_id' => auth()->id(),  /* verificar isso depois  */
                'weekday' => $workTime['weekday'],
                'start_time' => $workTime['start_time'],
                'end_time' => $workTime['end_time'],
                'duration' => $request->duration
            ];
            $scheduleService->createSchedule($data);
        }

        return back()->with('success', 'Horários salvos com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        $scheduleService = new ScheduleService(new ScheduleRepository());
        $scheduleService->deleteSchedule($id);

        return back()->with('success', 'Horário deletado com sucesso!');
    }
}
