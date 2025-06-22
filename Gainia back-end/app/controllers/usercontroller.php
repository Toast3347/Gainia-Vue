<?php

namespace Controllers;

use Services\UserService;

class UserController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function getAll()
    {
        $users = $this->service->getAllUsers();
        return $this->respond($users);
    }

    public function delete(int $userId)
    {
        $success = $this->service->deleteUser($userId);
        if (!$success) {
            return $this->respondWithError(500, 'Could not delete user.');
        }
        return $this->respond(['success' => true]);
    }
}