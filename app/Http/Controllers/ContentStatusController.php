<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\ContentStatus\StoreContentStatusRequest;
use App\Http\Requests\ContentStatus\UpdateContentStatusRequest;
use App\Http\Resources\ContentStatus\ContentStatusResource;
use App\Services\ContentStatusService;
use App\Support\PaginationHelper;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContentStatusController extends Controller
{


    public function __construct(private ContentStatusService $contentStatusService) {}


    public function indexForUser()
    {
        return TryHttpCatch::handle(
            function () {
                $content_statuses = $this->contentStatusService->indexForUser();
                return ApiResponseClass::sendResponse(
                    result: ContentStatusResource::collection($content_statuses),
                    message: "statuses loaded successfully",
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
                $content_statuses = $this->contentStatusService->index(null, $perPage);
                $result = $perPage > 0
                    ? [
                        'items' => ContentStatusResource::collection($content_statuses),
                        'meta_data' => PaginationHelper::meta($content_statuses),
                    ]
                    : ContentStatusResource::collection($content_statuses);

                return ApiResponseClass::sendResponse(
                    result: $result,
                    message: "statuses loaded successfully",
                    code: Response::HTTP_OK
                );
            }
        );
    }

    public function show($id)
    {
        $content_status = $this->contentStatusService->show($id);
        return ApiResponseClass::sendResponse(
            result: new ContentStatusResource($content_status),
            message: "status loaded successfully",
            code: Response::HTTP_OK
        );
    }

    public function store(StoreContentStatusRequest $request)
    {
        $validated = $request->validated();
        return TryHttpCatch::handle(
            function () use ($validated) {
                $content_status = $this->contentStatusService->store($validated);
                return ApiResponseClass::sendResponse(
                    result: new ContentStatusResource($content_status),
                    message: "status created successfully",
                    code: Response::HTTP_CREATED
                );
            }
        );
    }


    public function update(UpdateContentStatusRequest $request, $id)
    {

        $validated = $request->validated();

        return TryHttpCatch::handle(
            function () use ($validated, $id) {
                $content_status = $this->contentStatusService->update($validated, $id);
                return ApiResponseClass::sendResponse(
                    result: new ContentStatusResource($content_status),
                    message: "status updated successfully",
                    code: Response::HTTP_OK
                );
            }
        );
    }


    public function destroy($id)
    {

        return TryHttpCatch::handle(
            function () use ($id) {
                $this->contentStatusService->destroy($id);
                return ApiResponseClass::sendResponse(
                    result: null,
                    message: "status deleted successfully",
                    code: Response::HTTP_OK
                );
            }
        );
    }
    //
}
