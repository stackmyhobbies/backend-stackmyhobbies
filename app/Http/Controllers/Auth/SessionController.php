<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreSessionRequest;
use App\Http\Resources\Auth\SessionResource;
use App\Services\Auth\SessionService;
use App\Support\TryHttpCatch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    //


    public function __construct(protected SessionService $sessionService) {}
    public function store(StoreSessionRequest $request)
    {
        $credentials = $request->validated();

        return TryHttpCatch::handle(
            function () use ($credentials) {
                $result = $this->sessionService->store($credentials);
                return ApiResponseClass::sendResponse(
                    result: [
                        'user' => new SessionResource($result['user']),
                        'token' => $result['token']
                    ],
                    message: 'Log in Sucessfully',
                    code: Response::HTTP_OK
                );
            }
        );
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        $success = $this->sessionService->destroy($user);

        if (! $success) {
            return ApiResponseClass::sendError('No se encontró un token activo', [], 400);
        }

        return ApiResponseClass::sendResponse(null, 'Sesion cerrada correctamente');
    }
}
