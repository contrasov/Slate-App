<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;


class ScheduleController extends Controller
{
    public function show(Request $request){
        return Inertia::render('appointments/Schedule');
    }
}
