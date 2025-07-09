<?php

namespace App\Support;

use App\Classes\ApiResponseClass;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class TryCatch
{
    public static function handle(
        Closure $callback,
        string $message = 'Error',
        bool $transactional = true,
        string $name_model = "",
        ?string $action = null
    ) {
        try {
            if ($transactional) {
                DB::beginTransaction();
            }

            $result = $callback();

            if ($transactional) {
                DB::commit();
            }

            return $result;
        } catch (\Exception $e) {
            if ($transactional) {
                DB::rollBack();
            }


            if ($e instanceof ModelNotFoundException) {
                return ApiResponseClass::sendError(
                    message: "$name_model no puede ser encontrado",
                    errors: [],
                    code: 404
                );
            }

            return ApiResponseClass::throw($e, $message);
        }
    }
}