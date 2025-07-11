<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        return TryHttpCatch::handle(function () {
            $users =  $this->_userService->index();
            return ApiResponseClass::sendResponse($users, "Users loaded successfully", Response::HTTP_OK);
        });
    }

    public function show($id)
    {
        $user = $this->_userService->show($id);
        return ApiResponseClass::sendResponse($user, "User loaded successfully.", Response::HTTP_OK);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        return TryHttpCatch::handle(
            function () use ($validated) {
                $user = $this->_userService->store($validated);
                return ApiResponseClass::sendResponse($user, 'User created successfully.', Response::HTTP_CREATED);
            }
        );
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        return TryHttpCatch::handle(
            function () use ($validated, $id) {
                $user = $this->_userService->update($validated, $id);
                return ApiResponseClass::sendResponse($user, 'User updated successfully.', Response::HTTP_OK);
            }
        );
    }

    public function destroy($id)
    {
        return TryHttpCatch::handle(
            function () use ($id) {
                $this->_userService->destroy($id);
                return ApiResponseClass::sendResponse(null, 'User deleted successfully', Response::HTTP_OK);
            }
        );
    }
}
