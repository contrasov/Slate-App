<?php

namespace App\Repositories\Eloquent;

use App\Models\Schedule;
use App\Repositories\Contracts\UserRepositoryInterface;

class ScheduleRepository implements UserRepositoryInterface {
    protected $model;

    public function __construct() {
        $this->model = new Schedule();
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
        $schedule = $this->find($id);
        $schedule->update($data);
        return $schedule;
    }

    public function delete($id){
        $schedule = $this->find($id);
        return $schedule->delete();
    }
}
