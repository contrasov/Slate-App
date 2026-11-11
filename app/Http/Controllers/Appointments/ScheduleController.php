<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\AppointmentService;
use App\Models\Appointment;
use App\Models\FreeDay;
use App\Models\WeeklyAvailability;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class ScheduleController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService){
        $this->appointmentService = $appointmentService;
    }

    public function show(Request $request){

        $userId = $request->user()->id;
        $appointments = Appointment::with('patient')->where('user_id', $userId)->get();
        $weeklyAvailability = WeeklyAvailability::where('user_id', $userId)->orderBy('weekday')->get();
        $freeDays = FreeDay::where('user_id', $userId)
            ->orderBy('free_day')
            ->get(['id', 'free_day'])
            ->map(fn (FreeDay $freeDay) => [
                'id' => $freeDay->id,
                'free_day' => $freeDay->free_day->format('Y-m-d'),
            ])
            ->values();
        $bookedDates = Appointment::query()
            ->where('user_id', $userId)
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->where('status', '!=', 'Cancelado')
            ->selectRaw('appointment_date, COUNT(*) as appointments_count')
            ->groupBy('appointment_date')
            ->pluck('appointments_count', 'appointment_date');

        return Inertia::render('appointments/Schedule', [
            'appointments' => $appointments,
            'weeklyAvailability' => $weeklyAvailability,
            'appointmentDuration' => substr($request->user()->appointment_duration ?? '00:30:00', 0, 5),
            'freeDays' => $freeDays,
            'bookedDates' => $bookedDates,
            'successMessage' => $request->session()->get('success'),
        ]);
    }

    public function availability(Request $request)
    {
        $userId = $request->user()->id;
        $weeklyAvailability = WeeklyAvailability::where('user_id', $userId)->orderBy('weekday')->get();
        $freeDays = FreeDay::where('user_id', $userId)
            ->orderBy('free_day')
            ->get(['id', 'free_day'])
            ->map(fn (FreeDay $freeDay) => [
                'id' => $freeDay->id,
                'free_day' => $freeDay->free_day->format('Y-m-d'),
            ])
            ->values();
        $bookedDates = Appointment::query()
            ->where('user_id', $userId)
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->where('status', '!=', 'Cancelado')
            ->selectRaw('appointment_date, COUNT(*) as appointments_count')
            ->groupBy('appointment_date')
            ->pluck('appointments_count', 'appointment_date');

        return Inertia::render('appointments/Availability', [
            'weeklyAvailability' => $weeklyAvailability,
            'appointmentDuration' => substr($request->user()->appointment_duration ?? '00:30:00', 0, 5),
            'freeDays' => $freeDays,
            'bookedDates' => $bookedDates,
        ]);
    }

    public function saveAvailability(Request $request)
    {
        $validated = $request->validate([
            'appointment_duration' => ['required', 'date_format:H:i'],
            'availability' => ['required', 'array', 'size:7'],
            'closed_days' => ['present', 'array'],
            'closed_days.*' => ['required', 'date', 'distinct'],
            'availability.*.weekday' => ['required', 'integer', 'min:0', 'max:6', 'distinct'],
            'availability.*.enabled' => ['required', 'boolean'],
            'availability.*.start_time' => ['nullable', 'date_format:H:i'],
            'availability.*.slot_count' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'availability.*.generated_times' => ['nullable', 'array', 'max:1440'],
            'availability.*.generated_times.*' => ['required', 'date_format:H:i'],
            'availability.*.available_times' => ['nullable', 'array', 'max:1440'],
            'availability.*.available_times.*' => ['required', 'date_format:H:i'],
        ]);

        $durationMinutes = $this->timeToMinutes($validated['appointment_duration']);
        if ($durationMinutes < 1) {
            throw ValidationException::withMessages([
                'appointment_duration' => 'A duração média precisa ser maior que zero.',
            ]);
        }

        foreach ($validated['availability'] as $day) {
            $generated = $day['generated_times'] ?? [];
            $available = $day['available_times'] ?? [];

            if (count($generated) !== count(array_unique($generated)) || count($available) !== count(array_unique($available))) {
                throw ValidationException::withMessages([
                    'availability' => 'Cada dia precisa ter horários sem repetições.',
                ]);
            }

            if (!$day['enabled']) {
                continue;
            }

            if (empty($day['start_time']) || empty($day['slot_count']) || count($generated) !== (int) $day['slot_count']) {
                throw ValidationException::withMessages([
                    'availability' => 'Confira os horários gerados para cada dia ativo.',
                ]);
            }

            if (array_diff($available, $generated) !== []) {
                throw ValidationException::withMessages([
                    'availability' => 'Os horários ativos precisam fazer parte da prévia gerada.',
                ]);
            }
        }

        DB::transaction(function () use ($request, $validated) {
            $user = $request->user();
            $user->update(['appointment_duration' => $validated['appointment_duration']]);

            foreach ($validated['availability'] as $day) {
                if (!$day['enabled']) {
                    $user->weeklyAvailability()->where('weekday', $day['weekday'])->delete();
                    continue;
                }

                $user->weeklyAvailability()->updateOrCreate(
                    ['weekday' => $day['weekday']],
                    [
                        'start_time' => $day['start_time'],
                        'slot_count' => $day['slot_count'],
                        'generated_times' => $day['generated_times'],
                        'available_times' => $day['available_times'],
                    ],
                );
            }

            $requestedClosedDays = collect($validated['closed_days'])->unique()->values();
            $existingClosedDays = DB::table('free_days')
                ->where('user_id', $user->id)
                ->pluck('free_day')
                ->map(fn ($date) => (string) $date);

            $toReopen = $existingClosedDays->diff($requestedClosedDays);
            if ($toReopen->isNotEmpty()) {
                DB::table('free_days')
                    ->where('user_id', $user->id)
                    ->whereIn('free_day', $toReopen->all())
                    ->delete();
            }

            foreach ($requestedClosedDays->diff($existingClosedDays) as $date) {
                FreeDay::create([
                    'user_id' => $user->id,
                    'free_day' => $date,
                ]);
            }
        });

        return redirect()->route('schedule')->with('success', 'Horários e datas fechadas foram salvos.');
    }

    private function timeToMinutes(string $time): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', $time));
        return ($hours * 60) + $minutes;
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
