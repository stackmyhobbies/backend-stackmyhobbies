<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\ProgressStatus\StoreProgressStatusRequest;
use App\Http\Requests\ProgressStatus\UpdateProgressStatusRequest;
use App\Http\Resources\ProgressStatus\ProgressStatusResource;
use App\Services\ProgressStatusService;
use App\Support\PaginationHelper;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProgressStatusController extends Controller
{
    public function __construct(private ProgressStatusService $progressStatusService) {}

    public function indexForUser()
    {
        return TryHttpCatch::handle(function () {
            $statuses = $this->progressStatusService->indexForUser();
            return ApiResponseClass::sendResponse(
                result: ProgressStatusResource::collection($statuses),
                message: "statuses loaded successfully",
                code: Response::HTTP_OK
            );
        });
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page');
        return TryHttpCatch::handle(function () use ($perPage) {
            $statuses = $this->progressStatusService->index(null, $perPage);
            $result = $perPage > 0
                ? ['items' => ProgressStatusResource::collection($statuses), 'meta_data' => PaginationHelper::meta($statuses)]
                : ProgressStatusResource::collection($statuses);

            return ApiResponseClass::sendResponse(
                result: $result,
                message: "statuses loaded successfully",
                code: Response::HTTP_OK
            );
        });
    }

    public function show($id)
    {
        $status = $this->progressStatusService->show($id);
        return ApiResponseClass::sendResponse(
            result: new ProgressStatusResource($status),
            message: "status loaded successfully",
            code: Response::HTTP_OK
        );
    }

    public function store(StoreProgressStatusRequest $request)
    {
        return TryHttpCatch::handle(function () use ($request) {
            $status = $this->progressStatusService->store($request->validated());
            return ApiResponseClass::sendResponse(
                result: new ProgressStatusResource($status),
                message: "status created successfully",
                code: Response::HTTP_CREATED
            );
        });
    }

    public function update(UpdateProgressStatusRequest $request, $id)
    {
        return TryHttpCatch::handle(function () use ($request, $id) {
            $status = $this->progressStatusService->update($request->validated(), $id);
            return ApiResponseClass::sendResponse(
                result: new ProgressStatusResource($status),
                message: "status updated successfully",
                code: Response::HTTP_OK
            );
        });
    }

    public function destroy($id)
    {
        return TryHttpCatch::handle(function () use ($id) {
            $this->progressStatusService->destroy($id);
            return ApiResponseClass::sendResponse(
                result: null,
                message: "status deleted successfully",
                code: Response::HTTP_OK
            );
        });
    }
}
