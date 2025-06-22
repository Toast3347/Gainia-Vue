<?php

namespace Repositories;

class WorkoutRepository extends BaseRepository
{
    public function create($userId, $workoutDate, $exercises = []): bool
    {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO Workouts (user_id, workout_date) VALUES (:user_id, :workout_date)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':user_id', $userId);
            $stmt->bindValue(':workout_date', $workoutDate);
            $stmt->execute();

            $workoutId = $this->connection->lastInsertId();

            foreach ($exercises as $exercise) {
                $this->addExerciseToWorkout($workoutId, (array)$exercise);
            }

            $this->connection->commit();
            return true;
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function addExerciseToWorkout($workoutId, $exercise): bool
    {
        $sql = "INSERT INTO WorkoutExercises (workout_id, exercise_id, custom_exercise_id, sets, reps, weight)
                VALUES (:workout_id, :exercise_id, :custom_exercise_id, :sets, :reps, :weight)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':workout_id', $workoutId);
        $stmt->bindParam(':exercise_id', $exercise['exercise_id']);
        $stmt->bindParam(':custom_exercise_id', $exercise['custom_exercise_id']);
        $stmt->bindParam(':sets', $exercise['sets']);
        $stmt->bindParam(':reps', $exercise['reps']);
        $stmt->bindParam(':weight', $exercise['weight']);
        return $stmt->execute();
    }

    public function getAllByUserId($userId): array
    {
        $sql = "SELECT * FROM Workouts WHERE user_id = :user_id ORDER BY workout_date DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getExercisesByWorkoutId($workoutId): array
    {
        $sql = "
            SELECT 
                we.exercise_id,
                we.custom_exercise_id,
                we.sets,
                we.reps,
                we.weight,
                COALESCE(e.name, ce.name) AS exercise_name,
                COALESCE(e.muscle_group, ce.muscle_group) AS muscle_group
            FROM 
                WorkoutExercises we
            LEFT JOIN 
                Exercises e ON we.exercise_id = e.exercise_id
            LEFT JOIN 
                CustomExercises ce ON we.custom_exercise_id = ce.custom_exercise_id
            WHERE 
                we.workout_id = :workout_id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':workout_id', $workoutId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete($workoutId): bool
    {
        try {
            $this->connection->beginTransaction();

            $sql = "DELETE FROM WorkoutExercises WHERE workout_id = :workout_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':workout_id', $workoutId);
            $stmt->execute();

            $sql = "DELETE FROM Workouts WHERE workout_id = :workout_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':workout_id', $workoutId);
            $stmt->execute();

            $this->connection->commit();
            return true;
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function update($workoutId, $workoutDate, $exercises = []): bool
    {
        try {
            $this->connection->beginTransaction();

            $sql = "UPDATE Workouts SET workout_date = :workout_date WHERE workout_id = :workout_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':workout_date', $workoutDate);
            $stmt->bindValue(':workout_id', $workoutId);
            $stmt->execute();

            $sql = "DELETE FROM WorkoutExercises WHERE workout_id = :workout_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':workout_id', $workoutId);
            $stmt->execute();

            foreach ($exercises as $exercise) {
                $this->addExerciseToWorkout($workoutId, (array)$exercise);
            }

            $this->connection->commit();
            return true;
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function getById($workoutId)
    {
        $sql = "SELECT * FROM Workouts WHERE workout_id = :workout_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':workout_id', $workoutId);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}