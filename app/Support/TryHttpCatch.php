<?php

namespace App\Support;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Classes\ApiResponseClass;
use App\Exceptions\Database\DatabaseConstraintHandler;


class TryHttpCatch
{
    public static function handle(Closure $callback, string $message = 'Error')
    {
        try {
            return $callback();
        } catch (HttpResponseException $e) {
            return $e->getResponse();
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendError('Recurso no encontrado', [], 404);
        } catch (\Exception $e) {
            return self::handleExceptionByCode($e, $message);
        }
    }

    private static function handleExceptionByCode(\Exception $e, $message)
    {


        $code = (int) $e->getCode();

        return match ($code) {
            23505 => DatabaseConstraintHandler::handleUniqueConstraint($e),
            404 => ApiResponseClass::sendError('Recurso no encontrado', [], 404),
            default => ApiResponseClass::throw($e, $message)
        };
    }
}
