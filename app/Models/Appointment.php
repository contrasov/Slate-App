<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'weekday',
        'schedule_time',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    /* public function patient() {
        return $this->belongsTo(Patient::class);
    } */
}
