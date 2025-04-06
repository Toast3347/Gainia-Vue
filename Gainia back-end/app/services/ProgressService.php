<?php

namespace Services;

use Repositories\ProgressRepository;

class ProgressService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new ProgressRepository();
    }

    public function getAllByUserId($userId)
    {
        return $this->repository->getAllByUserId($userId);
    }

    public function createProgress($progress): bool
    {
        return $this->repository->create($progress);
    }
    public function updateProgress($progress): bool
    {
        return $this->repository->update($progress);
    }
    public function deleteProgress($progress): bool
    {
        return $this->repository->delete($progress);
    }
}