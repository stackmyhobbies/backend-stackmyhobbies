<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponseClass;
use App\Dto\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreSessionRequest;
use App\Http\Resources\Auth\SessionResource;
use App\Services\Auth\SessionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SessionController extends Controller
{
    //

    public function __construct(protected SessionService $sessionService) {}

    public function store(StoreSessionRequest $request)
    {
        $credentials = $request->validated();

        $result = $this->sessionService->store($credentials);

        $result = ResponseDTO::from($result);

        // Validación tradicional del email
        if (! $result->emailVerified) {
            $result->errors['email'] = 'Email no verificado';
        }

        // Si falló
        if (! $result->success) {
            return ApiResponseClass::sendError(
                errors: $result->errors,
                data: [
                    'user' => $result->user,
                    'token' => $result->token,
                    ...($result->data ?? []),
                ],
                message: $result->message ?? 'Ocurrió un problema',
                code: Response::HTTP_FORBIDDEN
            );
        }

        return ApiResponseClass::sendResponse(
            result: [
                'user' => new SessionResource($result->user),
                'token' => $result->token,
            ],
            message: 'Log in Successfully',
            code: Response::HTTP_OK
        );
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        $success = $this->sessionService->destroy($user);

        if (! $success) {
            return ApiResponseClass::sendError(message: 'No se encontró un token activo', errors: [], code: 400);
        }

        return ApiResponseClass::sendResponse(result: null, message: 'Sesion cerrada correctamente');
    }

    public function check(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return ApiResponseClass::sendError(
                message: 'Token inválido o expirado',
                errors: [],
                code: Response::HTTP_UNAUTHORIZED
            );
        }

        return ApiResponseClass::sendResponse(
            message: 'Sesión activa',
            result: [
                'user' => new SessionResource($user),
            ],
            code: Response::HTTP_OK
        );
    }
}
