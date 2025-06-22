<?php

namespace Services;
use Repositories\CustomExerciseRepository;
use Repositories\ExerciseRepository;

class ExerciseService{
    private $repository;
    private $customRepository;
    public function __construct()
    {
        $this->repository = new ExerciseRepository();
        $this->customRepository = new CustomExerciseRepository();
    }


    // Get all exercises for a user (so standarad and custom exercises) :)
    public function getAllExercisesByUserId($id): array
    {
        $exercises = $this->repository->getAllExercisesByUserId($id);
        return $exercises;
    }


    //start default exercises (Works with the default exercises that everybody sees)
    public function updateStandardExercise($exercise): bool
    {
        $exercise = $this->repository->update($exercise);
        return $exercise;
    }

    public function deleteStandardExercise($exerciseID): bool
    {
        $exercise = $this->repository->delete($exerciseID);
        return $exercise;
    }

    public function getAllStandardExercises(): array
    {
        $exercise = $this->repository->getAll();
        return $exercise;
    }

    public function createStandardExercise($exercise): bool
    {
        $exercise = $this->repository->create($exercise);
        return $exercise;
    }


    //start custom exercises (exercises that are created by a specific user)
    public function getAllCustomExercisesByUserId($id): array
    {
        $customExercises = $this->customRepository->getAllByUserId($id);
        return $customExercises;
    }
    public function createCustomExercise($exercise): bool
    {
        $exercise = $this->customRepository->create($exercise);
        return $exercise;
    }
    public function updateCustomExercise($exercise): bool
    {
        $exercise = $this->customRepository->update($exercise);
        return $exercise;
    }
    public function deleteCustomExercise($exerciseId): bool
    {
        return $this->customRepository->delete($exerciseId);
    }
    
    public function updateExercise($exerciseId, $data, $userRole)
    {
        $data['id'] = $exerciseId;
        if ($userRole === 'admin') {
            return $this->repository->update($data);
        } else {
            return $this->customRepository->update($data);
        }
    }
    
    //retrieves either a standard exercise (for an admin) or a custom exercise (for a user) to edit
    public function getExerciseForEdit(int $exerciseId, string $userRole)
    {
        if ($userRole === 'admin') {
            return $this->repository->getById($exerciseId);
        } else {
            return $this->customRepository->getById($exerciseId);
        }
    }
}