<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{



    public function __construct(
        private UserService $userService
    ) {}
    //
    public function index()
    {
        return TryHttpCatch::handle(function () {
            $users =  $this->userService->index();
            return ApiResponseClass::sendResponse(UserResource::collection($users), "Users loaded successfully", Response::HTTP_OK);
        });
    }

    public function show($id)
    {
        $user = $this->userService->show($id);
        return ApiResponseClass::sendResponse(new UserResource($user), "User loaded successfully.", Response::HTTP_OK);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        return TryHttpCatch::handle(
            function () use ($validated) {
                $user = $this->userService->store($validated);
                return ApiResponseClass::sendResponse(new UserResource($user), 'User created successfully.', Response::HTTP_CREATED);
            }
        );
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        return TryHttpCatch::handle(
            function () use ($validated, $id) {
                $user = $this->userService->update($validated, $id);
                return ApiResponseClass::sendResponse(new UserResource($user), 'User updated successfully.', Response::HTTP_OK);
            }
        );
    }

    public function destroy($id)
    {
        return TryHttpCatch::handle(
            function () use ($id) {
                $this->userService->destroy($id);
                return ApiResponseClass::sendResponse(null, 'User deleted successfully', Response::HTTP_OK);
            }
        );
    }
}
