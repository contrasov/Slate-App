<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientsController extends Controller
{
    public function create(Request $request){
        return Inertia::render('patients/Patients');
    }
}
