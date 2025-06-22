<?php

namespace Services;

use Repositories\LoginRepository;
use Services\ValidationService;

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
        $passwordErrors = ValidationService::validatePassword($user->password);
        if (!empty($passwordErrors)) {
            throw new \InvalidArgumentException("Password is not strong enough.");
        }
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);
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

    public function login($loginDetails)
    {
        try{
            $username = $loginDetails->username;
            $password = $loginDetails->password;
            $user = $this->repository->getByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                return true;
            }
            return false; 
        } catch (\Exception $e) {
            error_log("Error in login: " . $e->getMessage());
            throw new \Exception("Login failed due to an internal error.");
        }
    }
}