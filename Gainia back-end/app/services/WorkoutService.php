<?php

namespace Services;

use Repositories\WorkoutRepository;

class WorkoutService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new WorkoutRepository();
    }

    public function createWorkout($workout, $exercises = []): bool
    {
        return $this->repository->create($workout, $exercises);
    }

    public function getAllWorkoutsByUserId($userId): array
    {
        return $this->repository->getAllByUserId($userId);
    }

    public function getExercisesByWorkoutId($workoutId): array
    {
        return $this->repository->getExercisesByWorkoutId($workoutId);
    }

    public function updateWorkout($workout, $exercises = []): bool
    {
        return $this->repository->update($workout, $exercises);
    }

    public function deleteWorkout($workoutId): bool
    {
        return $this->repository->delete($workoutId);
    }
}