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
    case SINGLE = 'single';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::SEASON  => 'Temporada',
            self::VOLUME  => 'Volumen',
            self::PART    => 'Parte',
            self::EDITION => 'Edición',
            self::MOVIE   => 'Película',
            self::SINGLE  => 'Single'
        };
    }

    public static function labels(): array
    {
        return [
            self::SEASON->value => 'Temporada',
            self::VOLUME->value => 'Volumen',
            self::PART->value => 'Parte',
            self::EDITION->value => 'Edición',
            self::MOVIE->value => 'Película',
            self::SINGLE  => 'Single'
        ];
    }
}
