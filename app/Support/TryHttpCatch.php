<?php

namespace App\Support;

use App\Classes\ApiResponseClass;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TryHttpCatch
{
    public static function handle(Closure $callback, string $message = 'Error')
    {
        try {
            return $callback();
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendError('Recurso no encontrado', [], 404);
        } catch (\Exception $e) {
            return ApiResponseClass::throw($e, $message);
        }
    }
}
