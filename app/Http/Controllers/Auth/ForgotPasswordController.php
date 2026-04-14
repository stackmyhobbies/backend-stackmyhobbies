<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\Auth\ForgotPasswordService;
use App\Support\TryHttpCatch;
use Illuminate\Http\Response;

class ForgotPasswordController extends Controller
{

    public function __construct(protected ForgotPasswordService $forgotPasswordService) {}

    public function __invoke(ForgotPasswordRequest $request)
    {

        return TryHttpCatch::handle(function () use ($request) {
            $validated = $request->validated();

            $this->forgotPasswordService->sendResetLink($validated['email']);

            return ApiResponseClass::sendResponse(
                null,
                'Se ha enviado el enlace para restablecer la contraseña, revisa tu correo.',
                Response::HTTP_OK
            );
        });
    }
}
