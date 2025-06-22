<?php

namespace Repositories;

class Loginrepository extends BaseRepository{
    public function create($user): bool{
        $sql = "INSERT INTO Users (name, password, email) VALUES (:name, :password, :email)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':name', $user->name);
        $stmt->bindValue(':password', $user->password);
        $stmt->bindValue(':email', $user->username);
        return $stmt->execute();
    }

    public function getByUsername($username){
        $sql = "SELECT * FROM Users WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $username);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($user){
        $sql = "UPDATE Users SET username = :username, password = :password WHERE user_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':username', $user['username']);
        $stmt->bindParam(':password', $user['password']);
        $stmt->bindParam(':id', $user['ID']);
        return $stmt->execute();
    }

    public function delete($user){
        $sql = "DELETE FROM Users WHERE user_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $user->getId());
        return $stmt->execute();
    }
}