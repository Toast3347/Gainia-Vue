<?php

namespace Controllers;

use Services\GoalService;

class GoalController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new GoalService();
    }

    public function getAllGoals($userId)
    {
        $goals = $this->service->getAllGoals($userId);
        if (empty($goals)) {
            return $this->respondWithError(404, 'No goals found for this user.');
        }
        return $this->respond($goals);
    }

    public function createGoal()
    {
        try {
            sleep(2);
            error_log("enetering createGoal()");
            $goal = $this->createObjectFromPostedJson("Models\Goal");
            error_log("Goal: " . json_encode($goal));
            $confirm = $this->service->createGoal($goal);
            if (!$confirm) {
                return $this->respondWithError(500, 'Failed to create goal.');
            }
        } catch (\Exception $e) {
            return $this->respondWithError(500, $e->getMessage());
        }
        return $this->respondCreated($confirm);
    }

    public function updateGoal($goal)
    {
        $result = $this->service->updateGoal($goal);
        return $this->respond($result);
    }

    public function deleteGoal($goalId)
    {
        $result = $this->service->deleteGoal($goalId);
        return $this->respond($result);
    }
}
