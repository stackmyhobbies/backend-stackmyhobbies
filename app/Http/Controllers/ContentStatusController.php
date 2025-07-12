<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\ContentStatus\StoreContentStatusRequest;
use App\Http\Requests\ContentStatus\UpdateContentStatusRequest;
use App\Http\Resources\ContentStatusResource;
use App\Services\ContentStatusService;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContentStatusController extends Controller
{
    protected ContentStatusService $_contentStatusService;

    public function __construct(ContentStatusService $contentStatusService)
    {
        $this->_contentStatusService = $contentStatusService;
    }

    public function index()
    {
        return TryHttpCatch::handle(
            function () {
                $content_statuses = $this->_contentStatusService->index();
                return ApiResponseClass::sendResponse(
                    result: ContentStatusResource::collection($content_statuses),
                    message: "statuses loaded successfully",
                    code: Response::HTTP_OK
                );
            }
        );
    }

    public function show($id)
    {
        $content_status = $this->_contentStatusService->show($id);
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
                $content_status = $this->_contentStatusService->store($validated);
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
                $content_status = $this->_contentStatusService->update($validated, $id);
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
                $this->_contentStatusService->destroy($id);
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