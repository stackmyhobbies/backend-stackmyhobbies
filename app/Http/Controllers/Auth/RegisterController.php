<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegisterRequest;
use App\Http\Resources\Auth\RegisterResource;
use App\Interfaces\Auth\RegisterRepositoryInterface;
use App\Repositories\Auth\RegisterRepository;
use App\Services\Auth\RegisterService;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

//TODO ARREGLAR TODO LOS SERVICIOS, CONTROLLERS Y RESPOSITORIES
class RegisterController extends Controller
{
    public function __construct(private RegisterService $registerService) {}
    public function __invoke(StoreRegisterRequest $request)
    {

        $validated = $request->validated();

        return TryHttpCatch::handle(
            function () use ($validated) {
                $user = $this->registerService->store($validated);
                return ApiResponseClass::sendResponse(
                    result: ["user" => new RegisterResource($user)],
                    message: 'User registered successfully.',
                    code: Response::HTTP_CREATED
                );
            }
        );
    }
}
