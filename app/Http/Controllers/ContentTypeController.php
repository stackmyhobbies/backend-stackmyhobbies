<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\ContentType\StoreContentTypeRequest;
use App\Http\Requests\ContentType\UpdateContentTypeRequest;
use App\Http\Resources\ContentType\ContentTypeResource;
use App\Http\Resources\TagResource;
use App\Models\ContentType;
use App\Services\ContentTypeService;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContentTypeController extends Controller
{

    protected ContentTypeService $_contentTypeService;

    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->_contentTypeService = $contentTypeService;
    }

    public function index()
    {
        return TryHttpCatch::handle(
            function () {
                $contentTypes = $this->_contentTypeService->index();
                return ApiResponseClass::sendResponse(
                    result: TagResource::collection($contentTypes),
                    message: 'Types loaded successfully',
                    code: Response::HTTP_OK
                );
            }
        );
    }

    public function show($id)
    {
        $contentType = $this->_contentTypeService->show($id);
        return ApiResponseClass::sendResponse(
            result: new ContentTypeResource($contentType),
            message: 'Type loaded successfully',
            code: Response::HTTP_OK
        );
    }

    public function store(StoreContentTypeRequest $request)
    {
        $validated = $request->validated();
        return TryHttpCatch::handle(
            function () use ($validated) {
                $contentType = $this->_contentTypeService->store($validated);
                return ApiResponseClass::sendResponse($contentType, 'Eqieuta creada exitosamnete', Response::HTTP_CREATED);
            }
        );
    }


    public function update(UpdateContentTypeRequest $request, $id)
    {
        $validated = $request->validated();

        return TryHttpCatch::handle(
            function () use ($validated, $id) {
                $contentType = $this->_contentTypeService->update($validated, $id);
                return ApiResponseClass::sendResponse(
                    result: new ContentTypeResource($contentType),
                    message: "Type updated successfully",
                    code: Response::HTTP_OK
                );
            }
        );
    }


    public function destroy(ContentType $contenttype)
    {
        return TryHttpCatch::handle(
            function () use ($contenttype) {
                $this->_contentTypeService->delete($contenttype->id);
                return ApiResponseClass::sendResponse(
                    result: null,
                    message: 'Type deleted successfully',
                    code: Response::HTTP_OK
                );
            }
        );
    }
}
