<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentStatus\StoreContentStatusRequest;
use App\Http\Requests\ContentStatus\UpdateContentStatusRequest;
use App\Services\ContentStatusService;
use Illuminate\Http\Request;

class ContentStatusController extends Controller
{
    protected ContentStatusService $_contentStatusService;

    public function __construct(ContentStatusService $contentStatusService)
    {
        $this->_contentStatusService = $contentStatusService;
    }

    public function index()
    {
        return $this->_contentStatusService->index();
    }

    public function show($id)
    {

        return $this->_contentStatusService->show($id);
    }

    public function store(StoreContentStatusRequest $request)
    {
        $validated = $request->validated();
        return $this->_contentStatusService->store($validated);
    }


    public function update(UpdateContentStatusRequest $request, $id)
    {

        $validated = $request->validated();

        return $this->_contentStatusService->update($validated, $id);
    }


    public function destroy($id)
    {

        return $this->_contentStatusService->delete($id);
    }
    //
}
