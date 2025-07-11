<?php

namespace App\Support;

use App\Classes\ApiResponseClass;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class TryCatch
{
    public static function handle(Closure $callback, bool $transactional = true)
    {
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

            throw $e;
        }
    }
}
