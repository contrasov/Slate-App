<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\AppointmentService;

class DashboardController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService){
        $this->appointmentService = $appointmentService;
    }

    public function dashboard(Request $request)
    {
        $userAppointments = $this->appointmentService->getAllAppointments()->load('patient');  //retornar dados junto com tabela patient
        
        return Inertia::render('Dashboard', [
            'appointments' => $userAppointments
        ]);

    }
}   
