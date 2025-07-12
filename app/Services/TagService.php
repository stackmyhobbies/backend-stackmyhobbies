<?php

namespace App\Services;

use App\Classes\ApiResponseClass;
use App\Interfaces\TagRepositoryInterface;
use App\Support\TryCatch;
use App\Support\TryHttpCatch;
use Illuminate\Http\Response;

class TagService
{

    protected TagRepositoryInterface $_tagRepository;
    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->_tagRepository = $tagRepository;
    }

    public function index()
    {

        return $this->_tagRepository->index();
    }

    public function show($slug)
    {
        return $this->_tagRepository->show($slug);
    }

    public function store(array $data)
    {
        return TryCatch::handle(
            function () use ($data) {
                return $this->_tagRepository->store($data);
            }
        );
    }

    public function update(array $data, $id)
    {
        return TryCatch::handle(function () use ($data, $id) {
            return $this->_tagRepository->update($data, $id);
        });
    }

    public function destroy($id)
    {
        return TryCatch::handle(function () use ($id) {
            return $this->_tagRepository->destroy($id);
        });
    }
}