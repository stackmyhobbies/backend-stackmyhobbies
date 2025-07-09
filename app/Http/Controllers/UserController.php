<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private UserService $_userService;

    public function __construct(UserService $userService)
    {
        $this->_userService = $userService;
    }
    //
    public function index()
    {
        return $this->_userService->index();
    }

    public function show($id)
    {
        return $this->_userService->show($id);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        return $this->_userService->store($validated);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        return $this->_userService->update($validated, $id);
    }

    public function destroy($id)
    {
        return $this->_userService->destroy($id);
    }
}