<?php

namespace App\Exceptions\Database;

use App\Classes\ApiResponseClass;

class DatabaseConstraintHandler
{
    public static function handleUniqueConstraint(\Exception $e)
    {
        $message = $e->getMessage();

        return match (true) {
            str_contains($message, 'unique_user_title_segment') =>
            ApiResponseClass::sendError(
                'Violación de restricción única',
                ['title' => 'Ya existe un contenido con ese título', 'segment_type' => 'Ya existe un contenido con ese tipo segmento', 'segment_number' => 'Ya existe un contenido con ese número de segmento'],
                422
            ),
            default => ApiResponseClass::sendError(
                'Violación de restricción única',
                ['error' => 'Ya existe un registro con los mismos valores en campos únicos.'],
                422
            )
        };
    }
}