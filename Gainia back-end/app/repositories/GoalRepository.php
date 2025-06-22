<?php

namespace Repositories;


class GoalRepository extends BaseRepository
{
    public function getGoalsByUserId(int $userId): array
    {
        $query = "
            SELECT 
                g.goal_id,
                g.user_id,
                g.target_weight,
                g.target_reps,
                g.deadline,
                g.achieved,
                g.created_at,
                COALESCE(e.name, ce.name) AS exercise_name
            FROM 
                Goals g
            LEFT JOIN 
                Exercises e ON g.exercise_id = e.exercise_id
            LEFT JOIN 
                CustomExercises ce ON g.custom_exercise_id = ce.custom_exercise_id
            WHERE 
                g.user_id = :user_id
        ";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createGoal($goal): bool
    {
        $query = "
            INSERT INTO Goals (user_id, exercise_id, custom_exercise_id, target_weight, target_reps, deadline, achieved)
            VALUES (:user_id, :exercise_id, :custom_exercise_id, :target_weight, :target_reps, :deadline, :achieved)
        ";
        $deadline = $goal->getDeadline()->format('Y-m-d H:i:s');
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(':user_id', $goal->user_id, \PDO::PARAM_INT);
        $stmt->bindParam(':exercise_id', $goal->exercise_id, \PDO::PARAM_INT);
        $stmt->bindParam(':custom_exercise_id', $goal->custom_exercise_id, \PDO::PARAM_INT);
        $stmt->bindParam(':target_weight', $goal->target_weight, \PDO::PARAM_STR);
        $stmt->bindParam(':target_reps', $goal->target_reps, \PDO::PARAM_INT);
        $stmt->bindParam(':deadline', $deadline);
        $stmt->bindParam(':achieved', $goal->achieved, \PDO::PARAM_BOOL);

        return $stmt->execute();
    }

    public function updateGoal(array $goal)
    {
        $query = "UPDATE Goals SET goal_type = :goal_type, target_value = :target_value, current_value = :current_value WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':goal_type', $goal['goal_type'], \PDO::PARAM_STR);
        $stmt->bindParam(':target_value', $goal['target_value'], \PDO::PARAM_STR);
        $stmt->bindParam(':current_value', $goal['current_value'], \PDO::PARAM_STR);
        $stmt->bindParam(':id', $goal['id'], \PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function deleteGoal(int $goalId)
    {
        $query = "DELETE FROM Goals WHERE goal_id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $goalId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}