<?php

namespace App\Services;

use App\Models\Appointment;
use App\Repositories\Eloquent\AppointmentRepository;
use Illuminate\Support\Facades\Auth;

class AppointmentService {
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository){
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAllAppointments(){
        return $this->appointmentRepository->all()->where('user_id', Auth::id());
    }

    /* no sense talvez */
    public function getAppointmentsById($id) {  
        return $this->appointmentRepository->find($id)->where('user_id', Auth::id());
    }

    public function createAppointment(array $data) {
        return $this->appointmentRepository->create($data);
    }

    public function updateAppointment($id, array $data) {
        return $this->appointmentRepository->update($id, $data);
    }

    public function deleteAppointment($id) {
        return $this->appointmentRepository->delete($id);
    }
}