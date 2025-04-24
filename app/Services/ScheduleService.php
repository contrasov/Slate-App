<?php

namespace App\Services;

use App\Repositories\Eloquent\ScheduleRepository;

class ScheduleService {
    protected $scheduleRepository;

    public function __construct(ScheduleRepository $scheduleRepository){
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getAllSchedules() {
        return $this->scheduleRepository->all();
    }

    public function getSchedulesById($id) {
        return $this->scheduleRepository->find($id);
    }

    public function createSchedules(array $data) {
        return $this->scheduleRepository->create($data);
    }

    public function updateSchedules($id, array $data) {
        return $this->scheduleRepository->update($id, $data);
    }

    public function deleteSchedules($id) {
        return $this->scheduleRepository->delete($id);
    }

}