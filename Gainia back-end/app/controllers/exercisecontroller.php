<?php

namespace Controllers;

use Services\ExerciseService;
use Helpers\JwtHelper;
use Exception;

class ExerciseController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new ExerciseService();
    }

    public function getAllStandardExercises()
    {
        try {
            $exercises = $this->service->getAllStandardExercises();
            if (empty($exercises)) {
                return $this->respondWithError(404, 'No standard exercises found.');
            }
            return $this->respond($exercises);
        } catch (\Exception $e) {
            return $this->respondWithError(500, 'Internal Server Error: ' . $e->getMessage());
        }
    }

    public function getAllCustomExercisesByUserId($userId)
    {
        $exercises = $this->service->getAllCustomExercisesByUserId($userId);
        return $this->respond($exercises);
    }

    public function createStandardExercise()
    {
        try {
            $exercise = $this->createObjectFromPostedJson("Models\Exercise");
            $exercise = $this->service->createStandardExercise($exercise);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respondCreated($exercise);
    }

    public function createCustomExercise()
    {
        $exercise = $this->createObjectFromPostedJson("Models\\CustomExercise");
        $result = $this->service->createCustomExercise($exercise);
        
        if (!$result) {
            return $this->respondWithError(500, "Failed to create custom exercise.");
        }

        return $this->respondCreated(['success' => true]);
    }

    public function updateStandardExercise($exercise)
    {
        $result = $this->service->updateStandardExercise($exercise);
        return $this->respond($result);
    }

    public function updateCustomExercise($exercise)
    {
        $result = $this->service->updateCustomExercise($exercise);
        return $this->respond($result);
    }

    public function deleteStandardExercise($exerciseID)
    {
        $result = $this->service->deleteStandardExercise($exerciseID);
        return $this->respond($result);
    }

    public function deleteCustomExercise($exerciseId)
    {
        $result = $this->service->deleteCustomExercise($exerciseId);
        return $this->respond($result);
    }

    public function getAllExercisesByUserId($userId)
    {
        $exercises = $this->service->getAllExercisesByUserId($userId);
        return $this->respond($exercises);
    }

    public function getExerciseDetails($exerciseId)
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        $token = null;

        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (!$token || !($payload = JwtHelper::validateToken($token))) {
             return $this->respondWithError(401, 'Unauthorized');
        }

        $userRole = $payload['role'] ?? 'user';

        $exercise = $this->service->getExerciseForEdit($exerciseId, $userRole);

        if (!$exercise) {
            return $this->respondWithError(404, 'Exercise not found.');
        }

        return $this->respond($exercise);
    }

    public function updateExerciseDetails($exerciseId)
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        $token = null;

        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (!$token || !($payload = JwtHelper::validateToken($token))) {
             return $this->respondWithError(401, 'Unauthorized');
        }

        $userRole = $payload['role'] ?? 'user';
        $data = (array) $this->createObjectFromPostedJson("stdClass");

        $success = $this->service->updateExercise($exerciseId, $data, $userRole);

        if (!$success) {
            return $this->respondWithError(500, 'Could not update exercise.');
        }

        return $this->respond(['success' => true]);
    }

    public function deleteExercise(int $exerciseId)
    {
        // Implementation of deleteExercise method
    }
}
