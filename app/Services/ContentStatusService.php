<?php

namespace App\Services;

use App\Classes\ApiResponseClass;
use App\Interfaces\ContentStatusRepositoryInterface;
use App\Interfaces\ContentTypeRepositoryInterface;
use App\Support\TryCatch;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ContentStatusService
{

    public function __construct(private ContentStatusRepositoryInterface $contentStatusRepository) {}


    public function indexForUser()
    {
        return $this->contentStatusRepository->indexForUser();
    }


    public function index(?array $filters = [], ?int $perPage = null)
    {
        return TryCatch::handle(
            function () use ($filters, $perPage) {
                return $this->contentStatusRepository->index(null, $filters, $perPage);
            }
        );
    }

    public function show($id)
    {
        return $this->contentStatusRepository->show($id);
    }

    public function store(array $data)
    {
        return TryCatch::handle(
            function () use ($data) {
                return $this->contentStatusRepository->store($data);
            }
        );
    }

    public function update(array $data, $id)
    {

        return TryCatch::handle(
            function () use ($data, $id) {
                return $this->contentStatusRepository->update($data, $id);
            }
        );
    }

    public function destroy($id)
    {
        return TryCatch::handle(
            function () use ($id) {
                return $this->contentStatusRepository->destroy($id);
            }
        );
    }
}
