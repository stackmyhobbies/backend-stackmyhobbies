<?php

namespace App\Exceptions\Renderers;

use App\Classes\ApiResponseClass;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionRenderer
{
    public static function register(Exceptions $exceptions): void
    {
        // Validación fallida
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return ApiResponseClass::sendError(
                    'Errores de validación',
                    $e->errors(),
                    422
                );
            }
        });

        // No autenticado
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return ApiResponseClass::sendError(
                    'No autenticado',
                    $e->getMessage(),
                    401
                );
            }
        });

        // No autorizado
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->is('api/*')) {
                return ApiResponseClass::sendError(
                    'No autorizado',
                    [],
                    403
                );
            }
        });

        // Recurso no encontrado (modelo)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $previous = $e->getPrevious();
                if ($previous instanceof ModelNotFoundException) {
                    $model = class_basename($previous->getModel());

                    return ApiResponseClass::sendError("{$model} not found!", [], 404);
                }

                return ApiResponseClass::sendError('Recurso no encontrado', [], 404);
            }
        });

        // Método HTTP no permitido
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return ApiResponseClass::sendError(
                    'Método no permitido',
                    [],
                    405
                );
            }
        });

        // Excepciones HTTP generales
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return ApiResponseClass::sendError(
                    $e->getMessage() ?: 'Error HTTP',
                    [],
                    $e->getStatusCode()
                );
            }
        });

        // Fallback para errores no controlados
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                Log::error($e);

                if (config('app.debug')) {
                    return ApiResponseClass::sendError(
                        $e->getMessage() ?: 'Error interno del servidor',
                        [
                            'exception' => get_class($e),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => collect($e->getTrace())
                                ->map(fn ($trace) => Arr::except($trace, ['args']))
                                ->take(10)
                                ->all(),
                        ],
                        500
                    );
                }

                return ApiResponseClass::sendError(
                    'Error interno del servidor',
                    [],
                    500
                );
            }
        });

        // Puedes agregar más renders aquí si deseas (401, 403, etc.)
    }
}
