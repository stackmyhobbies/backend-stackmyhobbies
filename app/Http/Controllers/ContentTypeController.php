<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\ContentType\StoreContentTypeRequest;
use App\Http\Requests\ContentType\UpdateContentTypeRequest;
use App\Http\Resources\ContentType\ContentTypeResource;
use App\Http\Resources\TagResource;
use App\Models\ContentType;
use App\Services\ContentTypeService;
use App\Support\PaginationHelper;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ContentTypeController extends Controller
{


    public function __construct(private ContentTypeService $contentTypeService) {}

    public function indexForUser()
    {
        return TryHttpCatch::handle(
            function () {
                $contentTypes = $this->contentTypeService->indexForUser();
                return ApiResponseClass::sendResponse(
                    result: $contentTypes,
                    message: 'Types loaded successfully',
                    code: Response::HTTP_OK
                );
            }
        );
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page');

        return TryHttpCatch::handle(
            function () use ($perPage) {
                $contentTypes = $this->contentTypeService->index(null, $perPage);
                $result = $perPage > 0
                    ? [
                        'items' => ContentTypeResource::collection($contentTypes),
                        'meta_data' => PaginationHelper::meta($contentTypes),
                    ]
                    : ContentTypeResource::collection($contentTypes);

                return ApiResponseClass::sendResponse(
                    result: $result,
                    message: 'Types loaded successfully',
                    code: Response::HTTP_OK
                );
            }
        );
    }

    public function show($id)
    {
        $contentType = $this->contentTypeService->show($id);
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
                $contentType = $this->contentTypeService->store($validated);
                return ApiResponseClass::sendResponse(new ContentTypeResource($contentType), 'Eqieuta creada exitosamnete', Response::HTTP_CREATED);
            }
        );
    }


    public function update(UpdateContentTypeRequest $request, $id)
    {
        $validated = $request->validated();

        return TryHttpCatch::handle(
            function () use ($validated, $id) {
                $contentType = $this->contentTypeService->update($validated, $id);
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
                $this->contentTypeService->delete($contenttype->id);
                return ApiResponseClass::sendResponse(
                    result: null,
                    message: 'Type deleted successfully',
                    code: Response::HTTP_OK
                );
            }
        );
    }
}
