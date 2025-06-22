<?php

namespace Services;

use Repositories\ProgressRepository;

class ProgressService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new ProgressRepository();
    }

    public function getAllByUserId($userId)
    {
        return $this->repository->getAllByUserId($userId);
    }

    public function updateProgress($progress): bool
    {
        return $this->repository->update($progress);
    }
    public function deleteProgress($progress): bool
    {
        return $this->repository->delete($progress);
    }

    public function addProgressEntry($userId, array $exercise, $workoutDate)
    {
        $this->repository->create(
            $userId,
            $exercise['exercise_id'],
            $exercise['custom_exercise_id'],
            $exercise['weight'],
            $exercise['reps'],
            $workoutDate
        );
    }
}