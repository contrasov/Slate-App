<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ScheduleService;
use App\Services\AppointmentService;
use App\Services\FreeDayService;
use App\Repositories\Eloquent\ScheduleRepository;
use Illuminate\Support\Facades\Auth;


class ScheduleController extends Controller
{
    protected $freeDayService;
    protected $scheduleService;
    protected $appointmentService;

    public function __construct(scheduleService $scheduleService, AppointmentService $appointmentService, FreeDayService $freeDayService){
        $this->scheduleService = $scheduleService;
        $this->appointmentService = $appointmentService;
        $this->freeDayService = $freeDayService;
    }

    public function show(Request $request){

        $appointments = $this->appointmentService->getAppointmentsByDate()->load('patient');
        $schedules = $this->scheduleService->getAllSchedules();
        $freeDays = $this->freeDayService->getAllFreeDays();

        /* return response()->json($schedules); */

        return Inertia::render('appointments/Schedule', [
            'appointments' => $appointments,
            'schedules' => $schedules,
            'freeDays' => $freeDays
        ]);
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
                'user_id' => Auth::id(),
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

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in: Pendente,Confirmado,A Confirmar,Cancelado,Finalizado,Não Compareceu',
        ]);

        $appointment = $this->appointmentService->changeStatusAppointment($id, $request->status);
        
        if(!$appointment) {
            return response()->json(['Message' => 'Appointment not foun'], 404);
        }

        
    }
}
