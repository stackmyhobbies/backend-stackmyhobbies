<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\DB;

class TryCatch
{
    public static function handle(Closure $callback, bool $transactional = true)
    {
        $startedTransaction = $transactional && DB::transactionLevel() === 0;

        try {
            if ($startedTransaction) {
                DB::beginTransaction();
            }

            $result = $callback();

            if ($startedTransaction) {
                DB::commit();
            }

            return $result;
        } catch (\Throwable $e) {
            if ($startedTransaction) {
                DB::rollBack();
            }

            throw $e;
        }
    }
}
