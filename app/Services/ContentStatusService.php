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
    /**
     * Create a new class instance.
     */

    protected ContentStatusRepositoryInterface $_contentStatusRepository;

    public function __construct(ContentStatusRepositoryInterface $contentStatusRepository)
    {
        $this->_contentStatusRepository = $contentStatusRepository;
    }

    public function index()
    {
        return $this->_contentStatusRepository->index();
    }

    public function show($id)
    {
        return $this->_contentStatusRepository->show($id);
    }

    public function store(array $data)
    {
        return TryCatch::handle(
            function () use ($data) {
                return $this->_contentStatusRepository->store($data);
            }
        );
    }

    public function update(array $data, $id)
    {

        return TryCatch::handle(
            function () use ($data, $id) {
                return $this->_contentStatusRepository->update($data, $id);
            }
        );
    }

    public function destroy($id)
    {
        return TryCatch::handle(
            function () use ($id) {
                return $this->_contentStatusRepository->destroy($id);
            }
        );
    }
}
