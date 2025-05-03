<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'phone',
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
