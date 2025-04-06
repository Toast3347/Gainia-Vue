<?php

namespace Services;

use Repositories\PrRepository;

class PrService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new PrRepository();
    }

    public function getAllPrs($userId)
    {
        return $this->repository->getAllByUserId($userId);
    }

    public function createPr($pr): bool
    {
        return $this->repository->create($pr);
    }


    public function updatePr($pr): bool
    {
        return $this->repository->update($pr);
    }

    public function deletePr($pr): bool
    {
        return $this->repository->delete($pr);
    }
}
