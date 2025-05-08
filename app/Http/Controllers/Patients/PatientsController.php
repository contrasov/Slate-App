<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\PatientService;
use App\Services\AppointmentService;


class PatientsController extends Controller
{
    protected $patientService;
    protected $appointmentService;

    public function __construct(PatientService $patientService, AppointmentService $appointmentService)
    {
        $this->patientService = $patientService;
        $this->appointmentService = $appointmentService;
    }

    public function create(Request $request)
    {
        $patients = $this->patientService->getAllPatients();
        return Inertia::render('patients/Patients', [
            'patients' => $patients
        ]);
    }

    public function delete(Request $request)
    {
        $this->patientService->deletePatient($request->id);
        return to_route('patients');
    }

    public function showServicePatient(Request $request)
    {
        $patient = $this->patientService->findPatientId('id', $request->id);
        $appointments = $this->appointmentService->getAppointmentsById($request->id);

        return Inertia::render('patients/PatientsService', [
            'patient' => $patient,
            'appointments' => $appointments
        ]);
    }
}
