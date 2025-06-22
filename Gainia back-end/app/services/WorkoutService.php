<?php

namespace Services;

use Repositories\WorkoutRepository;
use Services\PrService;

class WorkoutService
{
    private $repository;
    private $prService;

    public function __construct()
    {
        $this->repository = new WorkoutRepository();
        $this->prService = new PrService();
    }

    public function createWorkout($userId, $workoutDate, $exercises = []): bool
    {
        $result = $this->repository->create($userId, $workoutDate, $exercises);
        if ($result) {
            foreach ($exercises as $exercise) {
                $this->prService->checkForNewPr($userId, (array)$exercise, $workoutDate);
            }
        }
        return $result;
    }

    public function getAllWorkoutsByUserId($userId): array
    {
        return $this->repository->getAllByUserId($userId);
    }

    public function getExercisesByWorkoutId($workoutId): array
    {
        return $this->repository->getExercisesByWorkoutId($workoutId);
    }

    public function updateWorkout($workoutId, $workoutDate, $exercises = []): bool
    {
        return $this->repository->update($workoutId, $workoutDate, $exercises);
    }

    public function deleteWorkout($workoutId): bool
    {
        return $this->repository->delete($workoutId);
    }

    public function getWorkout($workoutId)
    {
        return $this->repository->getById($workoutId);
    }
}