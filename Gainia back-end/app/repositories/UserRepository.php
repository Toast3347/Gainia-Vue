<?php

namespace Repositories;

class UserRepository extends BaseRepository
{
    public function getAll()
    {
        $sql = "SELECT user_id, name, email, role, created_at FROM Users";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete(int $userId): bool
    {
        $sql = "DELETE FROM Users WHERE user_id = :user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        return $stmt->execute();
    }
}