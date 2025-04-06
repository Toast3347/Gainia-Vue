<?php

namespace Repositories;


class CustomExerciseRepository extends BaseRepository {

    public function create($exercise) {
        $sql = "INSERT INTO CustomExercises (name, muscle_group, description, user_id) VALUES (:name, :muscle_group, :description, :user_id)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $exercise->getName());
        $stmt->bindParam(':muscle_group', $exercise->getMuscleGroup());
        $stmt->bindParam(':description', $exercise->getDescription());
        $stmt->bindParam(':user_id', $exercise->getUserId());
        return $stmt->execute();
    }

    public function update($exercise) {
        $sql = "UPDATE CustomExercises SET name = :name, muscle_group = :muscle_group, description = :description WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $exercise['name']);
        $stmt->bindParam(':muscle_group', $exercise['muscle_group']);
        $stmt->bindParam(':description', $exercise['description']);
        $stmt->bindParam(':id', $exercise['id']);
        return $stmt->execute();
    }

    public function delete($exercise) {
        $sql = "DELETE FROM CustomExercises WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $exercise->getId());
        return $stmt->execute();
    }

    public function getAllByUserId($id) {
        $sql = "SELECT * FROM CustomExercises WHERE user_id = :user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':user_id', $id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
