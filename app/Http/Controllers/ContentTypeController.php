<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\ContentType\StoreContentTypeRequest;
use App\Http\Requests\ContentType\UpdateContentTypeRequest;
use App\Models\ContentType;
use App\Services\ContentTypeService;
use Illuminate\Http\Request;

class ContentTypeController extends Controller
{

    protected ContentTypeService $_contentTypeService;

    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->_contentTypeService = $contentTypeService;
    }

    public function index()
    {
        return $this->_contentTypeService->index();
    }

    public function show($id)
    {
        return $this->_contentTypeService->show($id);
    }

    public function store(StoreContentTypeRequest $request)
    {
        $validated = $request->validated();

        return $this->_contentTypeService->store($validated);
    }


    public function update(UpdateContentTypeRequest $request, $id)
    {
        $validated = $request->validated();

        return $this->_contentTypeService->update($validated, $id);
    }


    public function destroy(ContentType $contenttype)
    {
        return $this->_contentTypeService->delete($contenttype->id);
    }
}
