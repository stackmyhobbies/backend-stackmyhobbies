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


    public function indexForUser()
    {
        return TryCatch::handle(
            function () {
                return $this->tagRepository->indexForUser();
            }
        );
    }

    public function index(?array $filters = [], ?int $perPage = null)
    {
        return TryCatch::handle(
            function () use ($filters, $perPage) {
                return $this->tagRepository->index(null, $filters, $perPage);
            }
        );
    }

    public function show($slug)
    {
        return $this->tagRepository->show($slug);
    }

    public function showForUser($slug, $userId)
    {
        return  TryCatch::handle(
            function () use ($slug, $userId) {
                return $this->tagRepository->showForUser($slug, $userId);
            }
        );
    }

    public function showTagWithContentItem(string $slug, $userId, ?int $perPage = null)
    {

        return $this->tagRepository->showTagWithContentItem($slug, $userId, $perPage);
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