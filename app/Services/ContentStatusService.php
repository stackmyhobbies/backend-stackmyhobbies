<?php

namespace App\Services;

use App\Classes\ApiResponseClass;
use App\Interfaces\ContentStatusRepositoryInterface;
use App\Interfaces\ContentTypeRepositoryInterface;
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
        $contentStatuses = $this->_contentStatusRepository->index();

        return ApiResponseClass::sendResponse($contentStatuses, 'Estados cargados exitosamente', Response::HTTP_OK);
    }

    public function show($id)
    {
        $contentStatus = $this->_contentStatusRepository->show($id);
        return ApiResponseClass::sendResponse($contentStatus, 'Estado cargado exitosamente', Response::HTTP_OK);
    }

    public function store(array $data)
    {
        //* TODO usar resource
        DB::beginTransaction();
        try {
            $contentStatus =  $this->_contentStatusRepository->store($data);
            DB::commit();
            return ApiResponseClass::sendResponse($contentStatus, 'Estado creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponseClass::throw($e, 'No se pudo crear el ContentType');
        }
    }

    public function update(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $contentStatus =  $this->_contentStatusRepository->update($data, $id);
            DB::commit();
            return ApiResponseClass::sendResponse($contentStatus, 'estado actualizado exitosamente');
        } catch (\Exception $e) {
            return ApiResponseClass::throw($e, 'No se pudo actualizar el estado');
        }
    }

    public function delete($id)
    {
        try {
            $contentStatus = $this->_contentStatusRepository->delete($id);
            return ApiResponseClass::sendResponse($contentStatus, 'estado eliminado exitosamente', Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return ApiResponseClass::sendError('Status not found or could not be deleted', [], Response::HTTP_NOT_FOUND);
            }
            return ApiResponseClass::throw($e, 'No se pudo eliminar el estado');
        }
    }
}
