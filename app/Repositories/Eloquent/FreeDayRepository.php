<?php

namespace App\Repositories\Eloquent;

use App\Models\FreeDay;
use App\Repositories\Contracts\UserRepositoryInterface;

class FreeDayRepository implements UserRepositoryInterface {
    protected $model;

    public function __construct(){
        $this->model = new FreeDay();
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
        $freeDay = $this->find($id)->findOrFail($id);
        $freeDay->update($data);
        return $freeDay;
    }

    public function delete($id){
        $freeDay = $this->find($id);
        return $freeDay->delete();
    }
}