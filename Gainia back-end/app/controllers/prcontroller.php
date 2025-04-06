<?php

namespace Controllers;

use Services\PrService;

class PrController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new PrService();
    }

    public function getAllPrs($userId)
    {
        $prs = $this->service->getAllPrs($userId);
        return $this->respond($prs);
    }

    public function createPr($pr)
    {
        $result = $this->service->createPr($pr);
        return $this->respond($result);
    }

    public function updatePr($pr)
    {
        $result = $this->service->updatePr($pr);
        return $this->respond($result);
    }

    public function deletePr($prId)
    {
        $result = $this->service->deletePr($prId);
        return $this->respond($result);
    }
}