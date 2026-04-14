<?php

namespace App\Services;

use App\Interfaces\ProgressStatusRepositoryInterface;
use App\Support\TryCatch;

class ProgressStatusService
{
    public function __construct(private ProgressStatusRepositoryInterface $progressStatusRepository) {}

    public function indexForUser()
    {
        return $this->progressStatusRepository->indexForUser();
    }

    public function index(?array $filters = [], ?int $perPage = null)
    {
        return TryCatch::handle(
            fn() => $this->progressStatusRepository->index(null, $filters, $perPage)
        );
    }

    public function show($id)
    {
        return $this->progressStatusRepository->show($id);
    }

    public function store(array $data)
    {
        return TryCatch::handle(fn() => $this->progressStatusRepository->store($data));
    }

    public function update(array $data, $id)
    {
        return TryCatch::handle(fn() => $this->progressStatusRepository->update($data, $id));
    }

    public function destroy($id)
    {
        return TryCatch::handle(fn() => $this->progressStatusRepository->destroy($id));
    }
}
