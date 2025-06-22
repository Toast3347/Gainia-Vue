<?php

namespace Controllers;

use Services\LoginService;
use Helpers\JwtHelper;

class Logincontroller extends Controller{
    private $service;

    public function __construct()
    {
        $this->service = new LoginService();
    }

    public function createUser()
    {
        try {
            $user = $this->createObjectFromPostedJson("Models\\Requests\\CreateAccount");
            $result = $this->service->createUser($user);
            return $this->respond($result);
        }catch(\InvalidArgumentException $e) {
            return $this->respondWithError(400, $e->getMessage());
        }
        catch (\Exception $e) {
            return $this->respondWithError(500, $e->getMessage());
        }
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

    public function login()
    {
        try {
            $loginDetails = $this->createObjectFromPostedJson("Models\\requests\\Login");
            $user = $this->service->getUserByUsername($loginDetails->username);

            if ($user && password_verify($loginDetails->password, $user['password'])) {
                unset($user['password']); // Remove password from user data for security purposes

                $payload = [
                    'user_id' => $user['user_id'],
                    'username' => $user['name'],
                    'role' => $user['role']
                ];
                $token = JwtHelper::generateToken($payload);

                return $this->respond([
                    'token' => $token,
                    'user' => $user
                ]);
            } else {
                return $this->respondWithError(401, 'Invalid credentials');
            }
        } catch (\Exception $e) {
            return $this->respondWithError(500, $e->getMessage());
        }
    }

}