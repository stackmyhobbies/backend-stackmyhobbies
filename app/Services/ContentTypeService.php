<?php

namespace App\Services;

use App\Interfaces\ContentTypeRepositoryInterface;

class ContentTypeService
{

    protected ContentTypeRepositoryInterface $_contentTypeRepository;


    public function __construct(ContentTypeRepositoryInterface $contentTypeRepository)
    {
        $this->_contentTypeRepository = $contentTypeRepository;
        //
    }

    public function index()
    {
        return $this->_contentTypeRepository->index();
    }

    public function show($id)
    {
        return $this->_contentTypeRepository->getById($id);
    }

    public function store(array $data)
    {
        return $this->_contentTypeRepository->store($data);
    }

    public function update(array $data, $id)
    {
        return $this->_contentTypeRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->_contentTypeRepository->delete($id);
    }
}
