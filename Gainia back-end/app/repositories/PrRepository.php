<?php

namespace Repositories;

class PrRepository extends BaseRepository
{
    public function create($userId, $exerciseId, $customExerciseId, $weight, $reps, $date)
    {
        $sql = "INSERT INTO PersonalRecords (user_id, exercise_id, custom_exercise_id, max_weight, max_reps, record_date) 
                VALUES (:user_id, :exercise_id, :custom_exercise_id, :max_weight, :max_reps, :record_date)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':exercise_id', $exerciseId);
        $stmt->bindParam(':custom_exercise_id', $customExerciseId);
        $stmt->bindParam(':max_weight', $weight);
        $stmt->bindParam(':max_reps', $reps);
        $stmt->bindParam(':record_date', $date);
        return $stmt->execute();
    }

    public function update($prId, $weight, $reps, $date)
    {
        $sql = "UPDATE PersonalRecords 
                SET max_weight = :max_weight, max_reps = :max_reps, record_date = :record_date 
                WHERE pr_id = :pr_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':pr_id', $prId);
        $stmt->bindParam(':max_weight', $weight);
        $stmt->bindParam(':max_reps', $reps);
        $stmt->bindParam(':record_date', $date);
        return $stmt->execute();
    }

    public function delete($prId)
    {
        $sql = "DELETE FROM PersonalRecords WHERE pr_id = :pr_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':pr_id', $prId);
        return $stmt->execute();
    }

    public function getAllByUserId($user_id): array
    {
        $sql = "
            SELECT 
                pr.pr_id,
                pr.user_id,
                pr.max_weight,
                pr.max_reps,
                pr.record_date,
                COALESCE(e.name, ce.name) AS exercise_name
            FROM 
                PersonalRecords pr
            LEFT JOIN 
                Exercises e ON pr.exercise_id = e.exercise_id
            LEFT JOIN 
                CustomExercises ce ON pr.custom_exercise_id = ce.custom_exercise_id
            WHERE 
                pr.user_id = :user_id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByUserAndExercise($userId, $exerciseId, $customExerciseId)
    {
        $sql = "SELECT * FROM PersonalRecords WHERE user_id = :user_id";
        if ($exerciseId) {
            $sql .= " AND exercise_id = :exercise_id";
        } elseif ($customExerciseId) {
            $sql .= " AND custom_exercise_id = :custom_exercise_id";
        } else {
            return null; 
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        if ($exerciseId) {
            $stmt->bindParam(':exercise_id', $exerciseId);
        }
        if ($customExerciseId) {
            $stmt->bindParam(':custom_exercise_id', $customExerciseId);
        }
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}