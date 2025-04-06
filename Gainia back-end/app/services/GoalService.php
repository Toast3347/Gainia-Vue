<?php

namespace Services;

use Repositories\GoalRepository;

class GoalService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new GoalRepository();
    }

    public function getAllGoals($userId)
    {
        return $this->repository->getGoalsByUserId($userId);
    }

    public function createGoal($goal): bool
    {
        return $this->repository->createGoal($goal);
    }
    public function updateGoal($goal): bool
    {
        return $this->repository->updateGoal($goal);
    }
    public function deleteGoal($goal): bool
    {
        return $this->repository->deleteGoal($goal);
    }
}
