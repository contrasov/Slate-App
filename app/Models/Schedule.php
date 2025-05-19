<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'user_work_time';

    protected $fillable = [
        'user_id',
        'weekday',
        'start_time',
        'end_time',
        'duration'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
