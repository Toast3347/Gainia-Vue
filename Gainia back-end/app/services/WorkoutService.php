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

    public function createWorkout($userId, $workoutDate, $exercises = []): bool
    {
        return $this->repository->create($userId, $workoutDate, $exercises);
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