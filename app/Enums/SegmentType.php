<?php
// app/Enums/SegmentType.php

namespace App\Enums;

enum SegmentType: string
{
    case SEASON = 'season';
    case VOLUME = 'volume';
    case PART = 'part';
    case EDITION = 'edition';
    case MOVIE = 'movie';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return [
            self::SEASON->value => 'Temporada',
            self::VOLUME->value => 'Volumen',
            self::PART->value => 'Parte',
            self::EDITION->value => 'Edición',
        ];
    }
}
