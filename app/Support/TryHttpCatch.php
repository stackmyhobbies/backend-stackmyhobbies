<?php

namespace App\Support;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

use App\Classes\ApiResponseClass;
use App\Exceptions\Database\DatabaseConstraintHandler;
use Illuminate\Auth\AuthenticationException;

class TryHttpCatch
{


    public static function handle(Closure $callback, string $message = 'Ha ocurrido un error inesperado. Intente más tarde.')
    {
        try {
            return $callback();
        } catch (HttpResponseException $e) {

            return $e->getResponse();
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendError('Recurso no encontrado', [], 404);
        } catch (AuthenticationException $e) {
            return ApiResponseClass::sendError($e->getMessage(), [], 401);
        } catch (ValidationException $e) {
            return ApiResponseClass::sendError('Error de validación', $e->errors(), 422);
        } catch (\Exception $e) {
            return self::handleExceptionByCode($e, $message);
        }
    }

    private static function handleExceptionByCode(\Exception $e, $message)
    {


        $code = (int) $e->getCode();

        return match ($code) {
            23505 => DatabaseConstraintHandler::handleUniqueConstraint($e),
            default => ApiResponseClass::throw($e, $message)
        };
    }
}
