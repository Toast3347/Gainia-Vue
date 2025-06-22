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

    //uses stdClass because the workout model is a PAIN IN THE ASS to get working
    public function createWorkout()
    {
        $data = $this->createObjectFromPostedJson("stdClass");

        $userId = $data->user_id ?? null;
        $workoutDate = $data->workout_date ?? null;
        $exercises = $data->exercises ?? [];

        if (!$userId || !$workoutDate) {
            return $this->respondWithError(400, "User ID and workout date are required.");
        }

        try {
            $this->service->createWorkout($userId, $workoutDate, $exercises);
            return $this->respond(['success' => true]);
        } catch (\Exception $e) {
            return $this->respondWithError(500, "Could not create workout: " . $e->getMessage());
        }
    }

    //see comment above :)
    public function updateWorkout($workoutId)
    {
        $data = $this->createObjectFromPostedJson("stdClass");
        
        $workoutDate = $data->workout_date ?? null;
        $exercises = $data->exercises ?? [];

        if (!$workoutDate) {
            return $this->respondWithError(400, "Workout date is required.");
        }

        try {
            $this->service->updateWorkout($workoutId, $workoutDate, $exercises);
            return $this->respond(['success' => true]);
        } catch (\Exception $e) {
            return $this->respondWithError(500, "Could not update workout: " . $e->getMessage());
        }
    }

    public function deleteWorkout($workoutId)
    {
        $result = $this->service->deleteWorkout($workoutId);
        return $this->respond($result);
    }

    public function getWorkout($workoutId)
    {
        $workout = $this->service->getWorkout($workoutId);
        return $this->respond($workout);
    }
}