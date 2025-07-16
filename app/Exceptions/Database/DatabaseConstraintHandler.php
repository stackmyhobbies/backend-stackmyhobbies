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
                ['title' => 'Ya existe un contenido con ese título, tipo y número de segmento para este usuario.'],
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
