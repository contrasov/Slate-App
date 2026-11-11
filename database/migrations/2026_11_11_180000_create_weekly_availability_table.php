<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->time('appointment_duration')->nullable();
        });

        Schema::create('user_weekly_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('weekday');
            $table->time('start_time');
            $table->unsignedSmallInteger('slot_count');
            $table->json('generated_times');
            $table->json('available_times');
            $table->timestamps();
            $table->unique(['user_id', 'weekday']);
        });

        Schema::table('free_days', function (Blueprint $table) {
            $table->unique(['user_id', 'free_day']);
        });

        $legacyRows = DB::table('user_work_time')
            ->orderBy('user_id')
            ->orderBy('weekday')
            ->orderBy('id')
            ->get();

        $weekly = [];
        $durationFrequency = [];

        foreach ($legacyRows as $row) {
            // The old form stored Monday as 0; the calendar and public booking use Sunday as 0.
            $weekday = ((int) $row->weekday + 1) % 7;
            $startMinutes = $this->toMinutes($row->start_time);
            $endMinutes = $this->toMinutes($row->end_time);
            $durationMinutes = $this->toMinutes($row->duration);
            $duration = substr((string) $row->duration, 0, 8);
            $key = $row->user_id . ':' . $weekday;

            if ($durationMinutes > 0 && $endMinutes > $startMinutes) {
                $times = [];
                for ($minute = $startMinutes; $minute < $endMinutes; $minute += $durationMinutes) {
                    $times[] = $this->toTime($minute);
                }

                if ($times !== []) {
                    if (!isset($weekly[$key])) {
                        $weekly[$key] = [
                            'user_id' => $row->user_id,
                            'weekday' => $weekday,
                            'start_time' => $this->toTime($startMinutes),
                            'times' => [],
                        ];
                    }

                    if ($startMinutes < $this->toMinutes($weekly[$key]['start_time'])) {
                        $weekly[$key]['start_time'] = $this->toTime($startMinutes);
                    }
                    $weekly[$key]['times'] = array_merge($weekly[$key]['times'], $times);
                }
            }

            $durationFrequency[$row->user_id][$duration]['count'] = ($durationFrequency[$row->user_id][$duration]['count'] ?? 0) + 1;
            $durationFrequency[$row->user_id][$duration]['weekday'] = min(
                $durationFrequency[$row->user_id][$duration]['weekday'] ?? 7,
                $weekday,
            );
        }

        foreach ($weekly as $schedule) {
            $times = array_values(array_unique($schedule['times']));
            sort($times);

            if ($times === []) {
                continue;
            }

            DB::table('user_weekly_availability')->insert([
                'user_id' => $schedule['user_id'],
                'weekday' => $schedule['weekday'],
                'start_time' => $schedule['start_time'],
                'slot_count' => count($times),
                'generated_times' => json_encode($times),
                'available_times' => json_encode($times),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($durationFrequency as $userId => $frequencies) {
            uasort($frequencies, function ($a, $b) {
                return ($b['count'] <=> $a['count'])
                    ?: ($a['weekday'] <=> $b['weekday']);
            });

            $duration = array_key_first($frequencies);
            DB::table('users')->where('id', $userId)->update(['appointment_duration' => $duration]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_weekly_availability');
        Schema::table('free_days', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'free_day']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('appointment_duration');
        });
    }

    private function toMinutes(string $time): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', substr($time, 0, 5)));
        return ($hours * 60) + $minutes;
    }

    private function toTime(int $minutes): string
    {
        return sprintf('%02d:%02d:00', intdiv($minutes, 60), $minutes % 60);
    }
};
