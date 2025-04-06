<?php

namespace Controllers;

use Services\ExerciseService;

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
            sleep(2);
            $exercise = $this->createObjectFromPostedJson("Models\\Exercise");
            $exercise = $this->service->createStandardExercise($exercise);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($exercise);
    }

    public function createCustomExercise($exercise)
    {
        $result = $this->service->createCustomExercise($exercise);
        return $this->respond($result);
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

    public function deleteCustomExercise($exercise)
    {
        $result = $this->service->deleteCustomExercise($exercise);
        return $this->respond($result);
    }

    public function getAllExercisesByUserId($userId)
    {
        $exercises = $this->service->getAllExercisesByUserId($userId);
        return $this->respond($exercises);
    }
}
