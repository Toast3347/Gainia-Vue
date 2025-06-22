<?php

namespace Repositories;

use models\exercise;

class ExerciseRepository extends BaseRepository
{
    public function create($exercise): bool   
    {
        $sql = "INSERT INTO Exercises (name, muscle_group, description) VALUES (:name, :muscle_group, :description)";
        $stmt = $this->connection->prepare($sql);

        $name = $exercise->getName();
        $muscle_group = $exercise->getMuscleGroup();
        $description = $exercise->getDescription();

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':muscle_group', $muscle_group);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM Exercises";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM Exercises WHERE exercise_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($exercise): bool
    {
        $sql = "UPDATE Exercises SET name = :name, muscle_group = :muscle_group, description = :description WHERE exercise_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $exercise['name']);
        $stmt->bindParam(':muscle_group', $exercise['muscle_group']);
        $stmt->bindParam(':description', $exercise['description']);
        $stmt->bindParam(':id', $exercise['id']);
        return $stmt->execute();
    }

    public function delete($exerciseId)
    {
        $sql = "DELETE FROM Exercises WHERE exercise_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $exerciseId);
        return $stmt->execute();
    }

    // get standard exercises (Managed by an admin) and user created ones
    public function getAllExercisesByUserId($id)
    {
        $sql = "
            SELECT 
                exercise_id AS id, 
                name, 
                muscle_group, 
                description, 
                'standard' AS type 
            FROM 
                Exercises
            UNION
            SELECT 
                custom_exercise_id AS id, 
                name, 
                muscle_group, 
                description, 
                'custom' AS type 
            FROM 
                CustomExercises
            WHERE 
                user_id = :user_id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllCustomExercisesByUserId($id): array
    {
        $sql = "SELECT * FROM CustomExercises WHERE user_id = :user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}