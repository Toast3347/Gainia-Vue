<?php

namespace Controllers;

use Services\LoginService;

class Logincontroller extends Controller{
    private $service;

    public function __construct()
    {
        $this->service = new LoginService();
    }

    public function createUser($user)
    {
        $result = $this->service->createUser($user);
        return $this->respond($result);
    }

    public function getUserByUsername($username)
    {
        $user = $this->service->getUserByUsername($username);
        return $this->respond($user);
    }

    public function updateUser($user)
    {
        $result = $this->service->updateUser($user);
        return $this->respond($result);
    }

    public function deleteUser($user)
    {
        $result = $this->service->deleteUser($user);
        return $this->respond($result);
    }

    public function login($username, $password)
    {
        $result = $this->service->login($username, $password);
        return $this->respond($result);
    }


}