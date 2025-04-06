<?php

namespace Controllers;

use Services\WorkoutService;

class WorkoutController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new WorkoutService();
    }

    public function getAllWorkoutsByUserId($userId)
    {
        $workouts = $this->service->getAllWorkoutsByUserId($userId);
        return $this->respond($workouts);
    }

    public function getWorkoutExercises($workoutId)
    {
        $exercises = $this->service->getExercisesByWorkoutId($workoutId);
        return $this->respond($exercises);
    }

    public function createWorkout($workout, $exercises = [])
    {
        $result = $this->service->createWorkout($workout, $exercises);
        return $this->respond($result);
    }

    public function updateWorkout($workout, $exercises = [])
    {
        $result = $this->service->updateWorkout($workout, $exercises);
        return $this->respond($result);
    }

    public function deleteWorkout($workoutId)
    {
        $result = $this->service->deleteWorkout($workoutId);
        return $this->respond($result);
    }
}