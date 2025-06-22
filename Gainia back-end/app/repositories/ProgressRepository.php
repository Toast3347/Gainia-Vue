<?php

namespace Repositories;

class ProgressRepository extends BaseRepository
{
    public function getAllByUserId($userId): array
    {
        $sql = "
            SELECT 
                pt.progress_id,
                pt.user_id,
                pt.weight,
                pt.reps,
                pt.record_date,
                COALESCE(e.name, ce.name) AS exercise_name
            FROM 
                ProgressTracking pt
            LEFT JOIN 
                Exercises e ON pt.exercise_id = e.exercise_id
            LEFT JOIN 
                CustomExercises ce ON pt.custom_exercise_id = ce.custom_exercise_id
            WHERE 
                pt.user_id = :user_id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create($userId, $exerciseId, $customExerciseId, $weight, $reps, $date): bool
    {
        $sql = "
            INSERT INTO ProgressTracking (user_id, exercise_id, custom_exercise_id, weight, reps, record_date)
            VALUES (:user_id, :exercise_id, :custom_exercise_id, :weight, :reps, :record_date)
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':exercise_id', $exerciseId);
        $stmt->bindParam(':custom_exercise_id', $customExerciseId);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':reps', $reps);
        $stmt->bindParam(':record_date', $date);
        return $stmt->execute();
    }

    public function update($progress): bool
    {
        $sql = "
            UPDATE ProgressTracking
            SET weight = :weight, reps = :reps, record_date = :record_date
            WHERE progress_id = :progress_id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':progress_id', $progress->progress_id);
        $stmt->bindParam(':weight', $progress->weight);
        $stmt->bindParam(':reps', $progress->reps);
        $stmt->bindParam(':record_date', $progress->record_date);
        return $stmt->execute();
    }

    public function delete($progressId): bool
    {
        $sql = "DELETE FROM ProgressTracking WHERE progress_id = :progress_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':progress_id', $progressId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
