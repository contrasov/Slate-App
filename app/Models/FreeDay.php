<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreeDay extends Model
{
    protected $fillable = [
        'user_id',
        'free_day'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
