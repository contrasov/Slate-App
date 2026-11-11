<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\FreeDay;
use App\Models\WeeklyAvailability;
use App\Services\PatientService;
use App\Services\AppointmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;

class SchedulePublicController extends Controller
{
    protected $patientService;
    protected $appointmentService;

    public function __construct(PatientService $patientService, AppointmentService $appointmentService){
        $this->patientService = $patientService;
        $this->appointmentService = $appointmentService;
    }

    public function show(Request $request, $token) {
        $user = User::where('schedule_token', $token)->firstOrFail();
    
        $weeklyAvailability = WeeklyAvailability::where('user_id', $user->id)->orderBy('weekday')->get();
        $freeDays = FreeDay::where('user_id', $user->id)
            ->orderBy('free_day')
            ->get()
            ->map(fn (FreeDay $freeDay) => $freeDay->free_day->format('Y-m-d'))
            ->values();
        $appointments = Appointment::where('user_id', $user->id)
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->where('status', '!=', 'Cancelado')
            ->get(['appointment_date', 'appointment_time']);
    
        return Inertia::render('appointments/PublicSchedule', [
            'user' => $user,
            'weeklyAvailability' => $weeklyAvailability,
            'freeDays' => $freeDays,
            'appointments' => $appointments->map(function($appointment) {
                return [
                    'appointment_date' => $appointment->appointment_date,
                    'appointment_time' => $appointment->appointment_time
                ];
            }),
            'bookingSuccess' => $request->session()->get('booking_success'),
        ]);

    }

    public function createAppointment(Request $request, string $token): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'date' => ['required', 'date', 'after_or_equal:today'],
            'schedule_time' => ['required', 'date_format:H:i'],
        ]);

        $user = User::where('schedule_token', $token)->firstOrFail();
        $appointmentDate = $validated['date'];
        $weekday = (int) \Illuminate\Support\Carbon::parse($appointmentDate)->dayOfWeek;

        $appointment = DB::transaction(function () use ($validated, $user, $appointmentDate, $weekday) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();

            if (FreeDay::where('user_id', $user->id)->whereDate('free_day', $appointmentDate)->exists()) {
                throw ValidationException::withMessages(['date' => 'Esta data está fechada para agendamentos.']);
            }

            $day = WeeklyAvailability::where('user_id', $user->id)->where('weekday', $weekday)->first();
            if (!$day || !in_array($validated['schedule_time'], $day->available_times ?? [], true)) {
                throw ValidationException::withMessages(['schedule_time' => 'Este horário não está disponível.']);
            }

            $alreadyBooked = Appointment::where('user_id', $user->id)
                ->whereDate('appointment_date', $appointmentDate)
                ->whereTime('appointment_time', $validated['schedule_time'])
                ->where('status', '!=', 'Cancelado')
                ->exists();

            if ($alreadyBooked) {
                throw ValidationException::withMessages(['schedule_time' => 'Este horário acabou de ser reservado. Escolha outro horário.']);
            }

            $patient = $this->patientService->createPatient([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'user_id' => $user->id,
            ]);

            return $this->appointmentService->createAppointment([
                'patient_id' => $patient->id,
                'user_id' => $user->id,
                'appointment_time' => $validated['schedule_time'],
                'appointment_date' => $appointmentDate,
                'phone' => $validated['phone'],
                'status' => 'A Confirmar',
            ]);
        });

        return redirect()->route('schedule.public', $appointment->user->schedule_token)->with('booking_success', [
            'name' => $validated['name'],
            'date' => $appointmentDate,
            'time' => $validated['schedule_time'],
        ]);
        
    }

}
