<?php

namespace Controllers;

use Services\ProgressService;

class ProgressController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new ProgressService();
    }

    public function getAllProgress($userId)
    {
        $progress = $this->service->getAllByUserId($userId);
        return $this->respond($progress);
    }

    public function createProgress()
    {
        try {
            $progress = $this->createObjectFromPostedJson("Models\\Progress");
            $result = $this->service->createProgress($progress);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $e->getMessage());
        }
    }

    public function updateProgress()
    {
        try {
            $progress = $this->createObjectFromPostedJson("Models\\Progress");
            $result = $this->service->updateProgress($progress);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $e->getMessage());
        }
    }

    public function deleteProgress($progressId)
    {
        $result = $this->service->deleteProgress($progressId);
        return $this->respond($result);
    }
}