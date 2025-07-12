<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Services\TagService;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected TagService $_tagService;

    public function __construct(TagService $tagService)
    {
        $this->_tagService = $tagService;
    }


    public function index()
    {
        return TryHttpCatch::handle(
            function () {
                $tags = $this->_tagService->index();
                return ApiResponseClass::sendResponse($tags, 'etiquetas cargadas exitosamente', Response::HTTP_OK);
            }
        );
    }

    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();
        return TryHttpCatch::handle(
            function () use ($validated) {
                $tag = $this->_tagService->store($validated);
                return ApiResponseClass::sendResponse($tag, 'Eqieuta creada exitosamnete', Response::HTTP_CREATED);
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $tag = $this->_tagService->show($slug);
        return ApiResponseClass::sendResponse(new TagResource($tag), 'Tag loaded successfully', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, string $id)
    {
        $validated = $request->validated();
        return TryHttpCatch::handle(
            function () use ($validated, $id) {
                $tag = $this->_tagService->update($validated, $id);
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
                $this->_tagService->destroy($id);
                return ApiResponseClass::sendResponse(null, 'Etiqueta eliminada con éxito', Response::HTTP_OK);
            }
        );
    }
}
