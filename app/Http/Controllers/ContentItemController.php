<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\ContentItem\StoreContentItemRequest;
use App\Http\Requests\ContentItem\UpdateContentItemRequest;
use App\Http\Resources\ContentItemResource;
use App\Models\ContentItem;
use App\Services\ContentItemService;
use App\Support\PaginationHelper;
use App\Support\TryHttpCatch;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ContentItemController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private ContentItemService $contentItemService) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', ContentItem::class);

        $perPage = $request->input('per_page', 15);

        $filters = $request->only([
            'search',
            'user_id',
            'content_type',
            'is_active',
            'progress',
            'tags',
        ]);

        return TryHttpCatch::handle(
            function () use ($perPage, $filters) {
                $content_items = $this->contentItemService->index($filters, $perPage);

                $resource = ContentItemResource::collection($content_items);

                $result = $perPage > 0 ? [
                    'items' => $resource,
                    'meta_data' => PaginationHelper::meta($content_items),
                ] : $resource;

                return ApiResponseClass::sendResponse($result, 'Admin items loaded successfully');
            }
        );
    }

    public function indexForUser(Request $request)
    {
        $perPage = $request->input('per_page');
        $user_id = Auth::id();
        $filters = $request->only([
            'search',
            'content_type',
            'progress',
            'tags',
        ]);

        return TryHttpCatch::handle(
            function () use ($user_id, $perPage, $filters) {
                $content_items = $this->contentItemService->indexForUser($user_id, $filters, $perPage);

                $result = $perPage > 0 ? [
                    'items' => ContentItemResource::collection($content_items),
                    'meta_data' => PaginationHelper::meta($content_items),
                ] : ContentItemResource::collection($content_items);

                return ApiResponseClass::sendResponse(
                    result: $result,
                    message: 'items loaded successfully',
                    code: Response::HTTP_OK
                );
            }
        );
    }

    public function store(StoreContentItemRequest $request)
    {
        $this->authorize('create', ContentItem::class);

        $user_admin_id = Auth::id();
        $validated = $request->validated();

        return TryHttpCatch::handle(
            function () use ($user_admin_id, $validated) {
                $contentItem = $this->contentItemService->store($user_admin_id, $validated);

                return ApiResponseClass::sendResponse(new ContentItemResource($contentItem), 'items creada exitosamnete', Response::HTTP_CREATED);
            }
        );
    }

    public function storeForUser(StoreContentItemRequest $request)
    {
        $this->authorize('create', ContentItem::class);

        $user_id = Auth::id();
        $validated = $request->validated();

        $image = $request->file('image');

        return TryHttpCatch::handle(
            function () use ($user_id, $validated, $image) {
                $contentItem = $this->contentItemService->storeForUser($user_id, $validated, $image);

                return ApiResponseClass::sendResponse(
                    new ContentItemResource($contentItem),
                    'Item creado exitosamente',
                    Response::HTTP_CREATED
                );
            }
        );
    }

    public function show(string $slug)
    {
        return TryHttpCatch::handle(
            function () use ($slug) {
                $content_item = $this->contentItemService->show($slug);

                $this->authorize('view', $content_item);

                return ApiResponseClass::sendResponse(new ContentItemResource($content_item), 'item loaded successfully', Response::HTTP_OK);
            }
        );
    }

    public function showForUser(string $slug)
    {
        $user_id = Auth::id();

        return TryHttpCatch::handle(
            function () use ($user_id, $slug) {
                $content_item = $this->contentItemService->showForUser($user_id, $slug);

                $this->authorize('view', $content_item);

                return ApiResponseClass::sendResponse(new ContentItemResource($content_item), 'item loaded successfully', Response::HTTP_OK);
            }
        );
    }

    public function update(UpdateContentItemRequest $request, string $id)
    {
        $contentItem = $this->contentItemService->find($id);
        $this->authorize('update', $contentItem);

        $validated = $request->validated();

        return TryHttpCatch::handle(
            function () use ($validated, $contentItem) {
                $content_item = $this->contentItemService->update($validated, $contentItem);

                return ApiResponseClass::sendResponse($content_item, 'content item actualizada con exito', Response::HTTP_OK);
            }
        );
    }

    public function updateForUser(UpdateContentItemRequest $request, string $id)
    {
        $user_id = Auth::id();

        $contentItem = $this->contentItemService->findForUser($user_id, $id);
        $this->authorize('update', $contentItem);

        $validated = $request->validated();

        $image = $request->file('image');

        return TryHttpCatch::handle(
            function () use ($validated, $contentItem, $image) {
                $content_item = $this->contentItemService->updateForUser($validated, $contentItem, $image);

                return ApiResponseClass::sendResponse($content_item, 'content_item actualizada con exito', Response::HTTP_OK);
            }
        );
    }

    public function destroy(string $id)
    {
        $contentItem = $this->contentItemService->find($id);
        $this->authorize('delete', $contentItem);

        return TryHttpCatch::handle(
            function () use ($contentItem) {
                $this->contentItemService->destroy($contentItem);

                return ApiResponseClass::sendResponse(null, 'Content_item eliminada con éxito', Response::HTTP_OK);
            }
        );
    }

    public function destroyForUser(string $id)
    {
        $user_id = Auth::id();

        $contentItem = $this->contentItemService->findForUser($user_id, $id);
        $this->authorize('delete', $contentItem);

        return TryHttpCatch::handle(
            function () use ($contentItem) {
                $this->contentItemService->destroyForUser($contentItem);

                return ApiResponseClass::sendResponse(null, 'Content_item eliminada con éxito', Response::HTTP_OK);
            }
        );
    }
}
