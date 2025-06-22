<?php

namespace Services;

use Repositories\UserRepository;

class UserService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function getAllUsers()
    {
        return $this->repository->getAll();
    }

    public function deleteUser(int $userId): bool
    {
        return $this->repository->delete($userId);
    }
}