<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\PatientService;


class PatientsController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
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


}
