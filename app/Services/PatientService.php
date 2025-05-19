<?php

namespace App\Services;

use App\Repositories\Eloquent\PatientRepository;
use Illuminate\Support\Facades\Auth;
class PatientService {
    protected $patientRepository;

    public function __construct(PatientRepository $patientRepository){
        $this->patientRepository = $patientRepository;
    }

    public function getAllPatients(){
        return $this->patientRepository->all()->where('user_id', Auth::id());
    }

    public function getPatientsById($id) {
        return $this->patientRepository->find($id);
    }

    public function findPatientId($column, $value) {
        return $this->patientRepository->where($column, $value)->first();
    }

    public function createPatient(array $data) {
        return $this->patientRepository->create($data);
    }

    public function updatePatient($id, array $data) {
        return $this->patientRepository->update($id, $data);
    }

    public function deletePatient($id) {
        return $this->patientRepository->delete($id);
    }
}
