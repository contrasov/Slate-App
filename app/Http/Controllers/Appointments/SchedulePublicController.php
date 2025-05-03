<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ScheduleService;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Services\PatientService;
use App\Services\AppointmentService;

class SchedulePublicController extends Controller
{
    protected $patientService;
    protected $appointmentService;

    public function __construct(PatientService $patientService, AppointmentService $appointmentService){
        $this->patientService = $patientService;
        $this->appointmentService = $appointmentService;
    }

    public function show($token){
        $user = User::where('schedule_token', $token)->firstOrFail();

        $scheduleService = new ScheduleService(new ScheduleRepository());
        $schedules = $scheduleService->getSchedulesByUserId($user->id);

        return Inertia::render('appointments/PublicSchedule', [
            'user' => $user,
            'schedules' => $schedules
        ]);
    }

    public function createAppointment(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'date' => 'required|date',
            'schedule_time' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $patient = $this->patientService->createPatient([
            'name' => $request->name,
            'phone' => $request->phone,
            'user_id' => $request->user_id
        ]);

        $appointment = $this->appointmentService->createAppointment([
            'patient_id' => $patient->id,
            'user_id' => $request->user_id,
            'appointment_time' => $request->schedule_time,
            'appointment_date' => $request->date,
            'phone' => $request->phone,
            'status' => 'A Confirmar'
        ]);

        return redirect()->route('schedule.public', $appointment->user->schedule_token);
        
    }

}