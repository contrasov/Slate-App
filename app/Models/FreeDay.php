<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreeDay extends Model
{
    protected $fillable = [
        'user_id',
        'free_day'
    ];

    protected function casts(): array
    {
        return ['free_day' => 'date:Y-m-d'];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
