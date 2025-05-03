<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Repositories\Contracts\UserRepositoryInterface;

class AppointmentRepository implements UserRepositoryInterface {
    protected $model;

    public function __construct(){
        $this->model = new Appointment();
    }

    public function all(){
        return $this->model->all();
    }

    public function find($id){
        return $this->model->find($id);
    }

    public function create(array $data){
        return $this->model->create($data);
    }

    public function update($id, array $data){
        $appointment = $this->find($id)->findOrFail($id);
        $appointment->update($data);
        return $appointment;
    }

    public function delete($id){
        $appointment = $this->find($id);
        return $appointment->delete();
    }
}