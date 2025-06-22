<?php

namespace Repositories;


class CustomExerciseRepository extends BaseRepository {

    public function create($exercise) {
        $sql = "INSERT INTO CustomExercises (name, muscle_group, description, user_id) VALUES (:name, :muscle_group, :description, :user_id)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $exercise->name);
        $stmt->bindParam(':muscle_group', $exercise->muscle_group);
        $stmt->bindParam(':description', $exercise->description);
        $stmt->bindParam(':user_id', $exercise->user_id);
        return $stmt->execute();
    }

    public function update($exercise) {
        $sql = "UPDATE CustomExercises SET name = :name, muscle_group = :muscle_group, description = :description WHERE custom_exercise_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $exercise['name']);
        $stmt->bindParam(':muscle_group', $exercise['muscle_group']);
        $stmt->bindParam(':description', $exercise['description']);
        $stmt->bindParam(':id', $exercise['id']);
        return $stmt->execute();
    }

    public function delete($exerciseId) {
        $sql = "DELETE FROM CustomExercises WHERE custom_exercise_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $exerciseId);
        return $stmt->execute();
    }

    public function getAllByUserId($id) {
        $sql = "SELECT * FROM CustomExercises WHERE user_id = :user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM CustomExercises WHERE custom_exercise_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}
