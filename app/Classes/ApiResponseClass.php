<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ApiResponseClass
{

    public static function rollback($e, $message = "Something went wrong!", $statusCode = 500)
    {
        DB::rollBack();
        self::throw($e, $message, $statusCode);
    }

    //? revisar para otra respuesta
    public static function throw($e, $message = "Something went wrong!", $code = 500)
    {
        Log::error($e);
        throw new HttpResponseException(response()->json(["message" => $message], $code));
    }

    public static function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        return response()->json($response, $code);
    }

    public static function sendError($message, $errors = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
