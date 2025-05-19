<?php

namespace App\Services;

use App\Repositories\Eloquent\FreeDayRepository;
use Illuminate\Support\Facades\Auth;


class FreeDayService {
    protected $freeDayRepository;

    public function __construct(FreeDayRepository $freeDayRepository)
    {
        $this->freeDayRepository = $freeDayRepository;
    }

    public function getAllFreeDays()
    {
        return $this->freeDayRepository->all()->where('user_id', Auth::id());
    }

    public function createFreeDay($data)
    {
        return $this->freeDayRepository->create($data);
    }

    public function deleteFreeDay($id)
    {
        return $this->freeDayRepository->delete($id);
    }
}