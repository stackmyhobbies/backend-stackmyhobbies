<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\ContentItem\StoreContentItemRequest;
use App\Http\Requests\contentItem\UpdateContentItemRequest;
use App\Http\Resources\ContentItemResource;
use App\Services\ContentItemService;
use App\Support\PaginationHelper;
use App\Support\TryHttpCatch;
use DragonCode\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Termwind\Components\Raw;

class ContentItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected ContentItemService $_contentItemService;

    public function __construct(ContentItemService $contentItemService)
    {
        $this->_contentItemService = $contentItemService;
    }


    public function index(Request $request)
    {

        // ?with=tags,contentType,contentStatus
        // $with = array_filter(explode(',', $request->query('with', '')));

        $perPage = $request->input('per_page');


        return TryHttpCatch::handle(
            function () use ($perPage) {
                $content_items = $this->_contentItemService->index([], $perPage);
                return ApiResponseClass::sendResponse(
                    [
                        "items" => ContentItemResource::collection($content_items),
                        "meta_data" => PaginationHelper::meta($content_items)
                    ],
                    'items loaded successfully',
                    Response::HTTP_OK
                );
            }
        );
    }

    public function store(StoreContentItemRequest $request)
    {

        $validated = $request->validated();
        return TryHttpCatch::handle(
            function () use ($validated) {
                $contentItem = $this->_contentItemService->store($validated);
                return ApiResponseClass::sendResponse(new ContentItemResource($contentItem), 'items creada exitosamnete', Response::HTTP_CREATED);
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $content_item = $this->_contentItemService->show($slug);
        return ApiResponseClass::sendResponse(new ContentItemResource($content_item), 'item loaded successfully', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreContentItemRequest $request, string $id)
    {
        $validated = $request->validated();


        return TryHttpCatch::handle(
            function () use ($validated, $id) {
                $tag = $this->_contentItemService->update($validated, $id);
                return ApiResponseClass::sendResponse($tag, 'etiqueta actualizada con exito', Response::HTTP_OK);
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return TryHttpCatch::handle(
            function () use ($id) {
                $this->_contentItemService->destroy($id);
                return ApiResponseClass::sendResponse(null, 'Etiqueta eliminada con éxito', Response::HTTP_OK);
            }
        );
    }
}
