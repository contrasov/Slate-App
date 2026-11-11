<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WeeklyAvailability;
use Inertia\Inertia;

class OnboardingController extends Controller
{
    public function edit(Request $request)
    {
        if ($request->user()->onboarding_complete) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('auth/Onboarding', [
            'accountType' => $request->user()->account_type,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'public_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'specialty' => ['required', 'string', 'max:120'],
            'timezone' => ['required', 'timezone'],
            'duration' => ['nullable', 'required_with:work_times', 'date_format:H:i', 'not_in:00:00'],
            'work_times' => ['nullable', 'array', 'max:7'],
            'work_times.*.weekday' => ['required_with:work_times', 'integer', 'min:0', 'max:6', 'distinct'],
            'work_times.*.start_time' => ['required_with:work_times', 'date_format:H:i'],
            'work_times.*.end_time' => ['required_with:work_times', 'date_format:H:i', 'after:work_times.*.start_time'],
        ], [
            'timezone.timezone' => 'Selecione um fuso horário válido.',
            'duration.not_in' => 'A duração do atendimento precisa ser maior que zero.',
            'work_times.*.end_time.after' => 'O fim do expediente precisa ser depois do início.',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $user = $request->user();
            $user->update([
                'public_name' => $validated['public_name'],
                'phone' => $validated['phone'],
                'specialty' => $validated['specialty'],
                'timezone' => $validated['timezone'],
                'onboarding_complete' => true,
                'appointment_duration' => !empty($validated['work_times']) ? $validated['duration'] : null,
            ]);

            foreach ($validated['work_times'] ?? [] as $workTime) {
                [$startHour, $startMinute] = array_map('intval', explode(':', $workTime['start_time']));
                [$durationHour, $durationMinute] = array_map('intval', explode(':', $validated['duration']));
                $start = ($startHour * 60) + $startMinute;
                $duration = ($durationHour * 60) + $durationMinute;
                $end = (int) ((int) substr($workTime['end_time'], 0, 2) * 60) + (int) substr($workTime['end_time'], 3, 2);
                $times = [];

                for ($minute = $start; $duration > 0 && $minute < $end; $minute += $duration) {
                    $times[] = sprintf('%02d:%02d', intdiv($minute, 60), $minute % 60);
                }

                if ($times === []) {
                    continue;
                }

                WeeklyAvailability::create([
                    'user_id' => $user->id,
                    'weekday' => $workTime['weekday'],
                    'start_time' => $workTime['start_time'],
                    'slot_count' => count($times),
                    'generated_times' => $times,
                    'available_times' => $times,
                ]);
            }
        });

        return redirect()->route('dashboard');
    }
}
