<?php

namespace App\Support;

use App\Classes\ApiResponseClass;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;

class TryHttpCatch
{
    public static function handle(Closure $callback, string $message = 'Error')
    {
        try {
            return $callback();
        } catch (HttpResponseException $e) {
            dd($e);
            return $e->getResponse();
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendError('Recurso no encontrado', [], 404);
        } catch (\Exception $e) {
            // dd($message);
            return ApiResponseClass::throw($e, $message);
        }
    }
}
