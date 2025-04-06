<?php

namespace Services;

use Repositories\LoginRepository;

class Loginservice
{
    private $repository;
    public function __construct()
    {
        $this->repository = new LoginRepository();
    }

    public function getUserByUsername($username)
    {
        $user = $this->repository->getByUsername($username);
        return $user;
    }

    public function createUser($user): bool
    {
        $user = $this->repository->create($user);
        return $user;
    }
    public function updateUser($user)
    {
        $user = $this->repository->update($user);
        return $user;
    }
    public function deleteUser($user)
    {
        $user = $this->repository->delete($user);
        return $user;
    }

    public function login($username, $password)
    {
        $user = $this->repository->getByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false; 
    }
}