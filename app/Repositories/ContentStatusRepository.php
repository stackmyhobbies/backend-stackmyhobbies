<?php

namespace App\Repositories;

use App\Interfaces\ContentStatusRepositoryInterface;
use App\Models\ContentStatus;

class ContentStatusRepository implements ContentStatusRepositoryInterface
{
    public function index()
    {
        return ContentStatus::all();
    }

    public function show($id)
    {
        return ContentStatus::where('id', $id)->where('status', true)->firstOrFail();
    }

    public function store(array $data)
    {
        return ContentStatus::create($data);
    }

    public function update(array $data, $id)
    {
        $contentStatus = $this->show($id);
        $contentStatus->update($data);

        return $contentStatus->fresh();
    }

    public function delete($id)
    {
        $contentType = $this->show($id); // Asegura que status sea true y que exista
        return  $contentType->update(['status' => false]);
    }
}
