<?php

namespace App\Repositories;

use App\Interfaces\ContentTypeRepositoryInterface;
use App\Models\ContentType;

class ContentTypeRepository implements ContentTypeRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return ContentType::all();
    }
    public function getById($id)
    {
        return ContentType::findOrFail($id);
    }
    public function store(array $data)
    {
        return ContentType::create($data);
    }
    public function update(array $data, $id)
    {

        $contentTyoe = $this->getById($id);
        return $contentTyoe->update($data);
    }
    public function delete($id)
    {
        $contentType = $this->getById($id);
        return $contentType->update(['status' => false]);
    }
}
