<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Classes\ApiResponseClass;
use App\Interfaces\ContentTypeRepositoryInterface;
use App\Support\TryCatch;
use Illuminate\Database\Eloquent\Collection;

class ContentTypeService
{

    public function __construct(private ContentTypeRepositoryInterface $contentTypeRepository) {}

    public function indexForUser(): Collection
    {
        return TryCatch::handle(
            function () {
                return $this->contentTypeRepository->indexForUser();
            }
        );
    }

    public function index(?array $filters = [], ?int $perPage)
    {
        return TryCatch::handle(
            function () use ($filters, $perPage) {
                return $this->contentTypeRepository->index(null, $filters, $perPage);
            }
        );
    }


    public function show($id)
    {
        $contentType = $this->contentTypeRepository->getById($id);
        return $contentType;
    }


    public function store(array $data)
    {
        return TryCatch::handle(
            function () use ($data) {
                return $this->contentTypeRepository->store($data);
            },
        );
    }

    public function update(array $data, $id)
    {
        return TryCatch::handle(
            function () use ($data, $id) {
                return $this->contentTypeRepository->update($data, $id);
            }
        );
    }

    public function delete($id)
    {

        return TryCatch::handle(function () use ($id) {
            return $this->contentTypeRepository->delete($id);
        });
    }
}
