<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyAvailability extends Model
{
    protected $table = 'user_weekly_availability';

    protected $fillable = [
        'user_id',
        'weekday',
        'start_time',
        'slot_count',
        'generated_times',
        'available_times',
    ];

    protected function casts(): array
    {
        return [
            'weekday' => 'integer',
            'slot_count' => 'integer',
            'generated_times' => 'array',
            'available_times' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
