<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Classes\ApiResponseClass;
use App\Interfaces\ContentTypeRepositoryInterface;
use App\Support\TryCatch;

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
        $contentTypes = $this->_contentTypeRepository->index();
        return $contentTypes;
    }


    public function show($id)
    {
        $contentType = $this->_contentTypeRepository->getById($id);
        return $contentType;
    }


    public function store(array $data)
    {
        return TryCatch::handle(
            function () use ($data) {
                return $this->_contentTypeRepository->store($data);
            },
        );
        //* TODO usar resource
        // DB::beginTransaction();
        // try {
        //     $contentType =  $this->_contentTypeRepository->store($data);
        //     DB::commit();
        //     return ApiResponseClass::sendResponse($contentType, 'ContentType creado exitosamente', 201);
        // } catch (\Exception $e) {
        //     return ApiResponseClass::throw($e, 'No se pudo crear el ContentType');
        // }
    }

    public function update(array $data, $id)
    {
        return TryCatch::handle(
            function () use ($data, $id) {
                return $this->_contentTypeRepository->update($data, $id);
            }
        );
        // DB::beginTransaction();
        // try {
        //     $contentType =  $this->_contentTypeRepository->update($data, $id);
        //     DB::commit();
        //     return ApiResponseClass::sendResponse($contentType, 'ContentType actualizado exitosamente');
        // } catch (\Exception $e) {
        //     return ApiResponseClass::throw($e, 'No se pudo actualizar el ContentType');
        // }
    }

    public function delete($id)
    {

        return TryCatch::handle(function () use ($id) {
            return $this->_contentTypeRepository->delete($id);
        });
        // try {
        //     $contentType = $this->_contentTypeRepository->delete($id);
        //     return ApiResponseClass::sendResponse($contentType, 'ContentType eliminado exitosamente', 204);
        // } catch (\Exception $e) {
        //     if ($e instanceof ModelNotFoundException) {
        //         return ApiResponseClass::sendError('Content Type not found or could not be deleted', [], 404);
        //     }
        //     return ApiResponseClass::throw($e, 'No se pudo eliminar el ContentType');
        // }
    }
}
