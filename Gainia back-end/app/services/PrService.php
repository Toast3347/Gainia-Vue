<?php

namespace Services;

use Repositories\PrRepository;

class PrService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new PrRepository();
    }

    public function getAllPrs($userId)
    {
        return $this->repository->getAllByUserId($userId);
    }

    public function deletePr($pr): bool
    {
        return $this->repository->delete($pr);
    }

    public function checkForNewPr($userId, array $exercise, $workoutDate)
    {
        $existingPr = $this->repository->findByUserAndExercise($userId, $exercise['exercise_id'], $exercise['custom_exercise_id']);

        if ($existingPr) {
            if ($exercise['weight'] > $existingPr['max_weight']) {
                $this->repository->update($existingPr['pr_id'], $exercise['weight'], $exercise['reps'], $workoutDate);
            }
        } else {
            $this->repository->create($userId, $exercise['exercise_id'], $exercise['custom_exercise_id'], $exercise['weight'], $exercise['reps'], $workoutDate);
        }
    }
}
