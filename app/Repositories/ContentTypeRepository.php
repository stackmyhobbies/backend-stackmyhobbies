<?php

namespace App\Repositories;

use App\Interfaces\ContentTypeRepositoryInterface;
use App\Models\ContentType;

class ContentTypeRepository implements ContentTypeRepositoryInterface
{

    public function __construct() {}

    public function index()
    {
        return ContentType::where(["status" => true])->get();
    }

    public function getById($id)
    {
        return ContentType::where('id', $id)->where('status', true)->firstOrFail();
    }

    public function store(array $data)
    {
        return ContentType::create($data);
    }
    public function update(array $data, $id)
    {
        $contentType = ContentType::findOrFail($id);
        $contentType->update($data);

        return $contentType->fresh();
    }

    public function delete($id)
    {
        $contentType = $this->getById($id); // Asegura que status sea true y que exista
        return  $contentType->update(['status' => false]);
    }
}
