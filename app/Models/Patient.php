<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $casts = [
        'birth_date' => 'date:Y-m-d',
    ];

    protected $fillable = [
        'name',
        'user_id',
        'phone',
        'birth_date',
        'gender',
        'email',
        'address',
        'city',
        'cep_code',
        'state',
        'note',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
