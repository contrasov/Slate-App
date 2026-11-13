<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\PatientService;
use App\Models\Appointment;


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

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'birth_date' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'gender' => ['sometimes', 'nullable', 'string', 'max:100'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'cep_code' => ['sometimes', 'nullable', 'string', 'max:255'],
            'state' => ['sometimes', 'nullable', 'string', 'max:255'],
            'note' => ['sometimes', 'nullable', 'string', 'max:5000'],
        ]);

        $this->patientService->updatePatientForCurrentUser($id, $validated);

        return back()->with('success', 'Dados do paciente atualizados.');
    }

    public function showServicePatient(Request $request)
    {
        $patient = $this->patientService->findPatientForCurrentUser($request->id);
        $appointmentQuery = Appointment::query()
            ->where('user_id', $request->user()->id)
            ->where('patient_id', $patient->id);

        $appointmentId = $request->integer('appointment_id');
        $appointments = $appointmentId
            ? $appointmentQuery->findOrFail($appointmentId)
            : $appointmentQuery->latest('appointment_date')->latest('appointment_time')->firstOrFail();

        return Inertia::render('patients/PatientsService', [
            'patient' => $patient,
            'appointments' => $appointments
        ]);
    }
}
