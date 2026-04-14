<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\ResetPasswordService;
use App\Support\TryHttpCatch;
use Illuminate\Http\Response;


class ResetPasswordController extends Controller
{
    public function __construct(
        protected ResetPasswordService $resetPasswordService
    ) {}

    public function __invoke(ResetPasswordRequest $request)
    {
        return TryHttpCatch::handle(function () use ($request) {
            $validated = $request->validated();

            $this->resetPasswordService->reset($validated);



            return ApiResponseClass::sendResponse(
                null,
                'Contraseña restablecida correctamente',
                Response::HTTP_OK
            );
        }, 'Error al restablecer la contraseña');
    }
}
