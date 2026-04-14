<?php

namespace App\Repositories;

use App\Interfaces\ProgressStatusRepositoryInterface;
use App\Models\ProgressStatus;

class ProgressStatusRepository implements ProgressStatusRepositoryInterface
{
    private $active = true;

    public function indexForUser()
    {
        return ProgressStatus::where('status', $this->active)->get();
    }

    public function index(?array $with = [], ?array $filters = [], ?int $perPage = null)
    {
        $query = ProgressStatus::query();

        if (!empty($with)) {
            $query->with($with);
        }

        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                $query->where($field, $value);
            }
        }

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function show($id)
    {
        return ProgressStatus::where('id', $id)->firstOrFail();
    }

    public function store(array $data)
    {
        return ProgressStatus::create($data);
    }

    public function update(array $data, $id)
    {
        $progressStatus = $this->show($id);
        $progressStatus->update($data);
        return $progressStatus->fresh();
    }

    public function destroy($id)
    {
        $progressStatus = $this->show($id);
        return $progressStatus->update(['status' => false]);
    }
}
