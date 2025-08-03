<?php

namespace App\Services;

use App\Classes\ApiResponseClass;
use App\Interfaces\TagRepositoryInterface;
use App\Support\TryCatch;
use App\Support\TryHttpCatch;
use Illuminate\Http\Response;

class TagService
{


    public function __construct(private TagRepositoryInterface $tagRepository) {}

    public function index()
    {

        return $this->tagRepository->index();
    }

    public function show($slug)
    {
        return $this->tagRepository->show($slug);
    }

    public function store(array $data)
    {
        return TryCatch::handle(
            function () use ($data) {
                return $this->tagRepository->store($data);
            }
        );
    }

    public function update(array $data, $id)
    {
        return TryCatch::handle(function () use ($data, $id) {
            return $this->tagRepository->update($data, $id);
        });
    }

    public function destroy($id)
    {
        return TryCatch::handle(function () use ($id) {
            return $this->tagRepository->destroy($id);
        });
    }
}
