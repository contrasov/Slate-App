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

    public function createSchedule(array $data) {
        return $this->scheduleRepository->create($data);
    }

    public function updateSchedule($id, array $data) {
        return $this->scheduleRepository->update($id, $data);
    }

    public function deleteSchedule($id) {
        return $this->scheduleRepository->delete($id);
    }

}