<?php

namespace Repositories;

class Loginrepository extends BaseRepository{
    public function create($user): bool{
        $sql = "INSERT INTO Users (username, password) VALUES (:username, :password)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':username', $user->getUsername());
        $stmt->bindParam(':password', $user->getPassword());
        return $stmt->execute();
    }

    public function getByUsername($username){
        $sql = "SELECT * FROM Users WHERE username = :username";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($user){
        $sql = "UPDATE Users SET username = :username, password = :password WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':username', $user['username']);
        $stmt->bindParam(':password', $user['password']);
        $stmt->bindParam(':id', $user['ID']);
        return $stmt->execute();
    }

    public function delete($user){
        $sql = "DELETE FROM Users WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $user->getId());
        return $stmt->execute();
    }
}