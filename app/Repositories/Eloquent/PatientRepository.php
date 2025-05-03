<?php

namespace App\Repositories\Eloquent;

use App\Models\Patient;
use App\Repositories\Contracts\UserRepositoryInterface;

class PatientRepository implements UserRepositoryInterface {
    protected $model;
    
    public function __construct(){
        $this->model = new Patient();
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
        $patient = $this->find($id)->findOrFail($id);
        $patient->update($data);
        return $patient;
    }

    public function delete($id){
        $patient = $this->find($id);
        return $patient->delete();
    }
}