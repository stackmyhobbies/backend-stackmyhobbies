<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Resources\ContentItemLiteResource;
use App\Http\Resources\TagResource;
use App\Services\TagService;
use App\Support\PaginationHelper;
use App\Support\TryHttpCatch;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function __construct(private TagService $tagService) {}

    public function indexForUser()
    {
        return TryHttpCatch::handle(
            function () {
                $tags = $this->tagService->indexForUser();
                return ApiResponseClass::sendResponse(
                    result: TagResource::collection($tags),
                    message: "Tags loaded successfully",
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
                $tags = $this->tagService->index(null, $perPage);
                $result = $perPage > 0
                    ? [
                        'items' => TagResource::collection($tags),
                        'meta_data' => PaginationHelper::meta($tags),
                    ]
                    : TagResource::collection($tags);

                return ApiResponseClass::sendResponse(
                    result: $result,
                    message: 'Tags loaded successfully',
                    code: Response::HTTP_OK
                );
            }
        );
    }

    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();
        return TryHttpCatch::handle(
            function () use ($validated) {
                $tag = $this->tagService->store($validated);
                return ApiResponseClass::sendResponse($tag, 'Eqieuta creada exitosamnete', Response::HTTP_CREATED);
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $tag = $this->tagService->show($slug);
        return ApiResponseClass::sendResponse(new TagResource($tag), 'Tag loaded successfully', Response::HTTP_OK);
    }

    public function showForUser(string $slug)
    {
        $userId = Auth::user()->id;

        $tag = $this->tagService->showForUser($slug, $userId);

        return ApiResponseClass::sendResponse(new TagResource($tag), 'Tag loaded successfully', Response::HTTP_OK);
    }

    public function showTagWithContentItem(string $slug, Request $request)
    {

        $perPage = $request->input('per_page');
        $userId = Auth::user()->id;

        return TryHttpCatch::handle(function () use ($perPage, $userId, $slug) {
            $tag = $this->tagService->showTagWithContentItem($slug, $userId, $perPage);
            $result = $perPage > 0
                ? [
                    'items' => ContentItemLiteResource::collection($tag),
                    'meta_data' => PaginationHelper::meta($tag),
                ]
                : ContentItemLiteResource::collection($tag);

            return ApiResponseClass::sendResponse($result, "", 200);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, string $id)
    {
        $validated = $request->validated();
        return TryHttpCatch::handle(
            function () use ($validated, $id) {
                $tag = $this->tagService->update($validated, $id);
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
                $this->tagService->destroy($id);
                return ApiResponseClass::sendResponse(null, 'Etiqueta eliminada con éxito', Response::HTTP_OK);
            }
        );
    }
}
