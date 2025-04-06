<?php

namespace Repositories;

class PrRepository extends BaseRepository
{
    public function create($pr)
    {
        $sql = "INSERT INTO PersonalRecords (user_id, exercise_id, weight, date) VALUES (:user_id, :exercise_id, :weight, :date)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $pr->user_id);
        $stmt->bindParam(':exercise_id', $pr->exercise_id);
        $stmt->bindParam(':weight', $pr->weight);
        $stmt->bindParam(':date', $pr->date);
        return $stmt->execute();
    }

    public function update($pr)
    {
        $sql = "UPDATE PersonalRecords SET weight = :weight, date = :date WHERE user_id = :user_id AND exercise_id = :exercise_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':weight', $pr->weight);
        $stmt->bindParam(':date', $pr->date);
        $stmt->bindParam(':user_id', $pr->user_id);
        $stmt->bindParam(':exercise_id', $pr->exercise_id);
        return $stmt->execute();
    }

    public function delete($pr)
    {
        $sql = "DELETE FROM PersonalRecords WHERE user_id = :user_id AND exercise_id = :exercise_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $pr->user_id);
        $stmt->bindParam(':exercise_id', $pr->exercise_id);
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
}